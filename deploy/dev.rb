
#
# Development-related operations
#

before 'dev:outdated_submodules', 'dev:get_submodules'
before 'dev:validate_submodules', 'dev:get_submodules'
before 'dev:remote:add', 'repo:parse', 'dev:get_submodules'
before 'dev:remote:delete', 'dev:get_submodules'
before 'dev:setup:submodule_origins', 'repo:parse', 'dev:get_submodules'
before 'dev:setup:remote_working_directory', 'repo:parse'
before 'dev:sync:submodules', 'dev:get_submodules'

namespace :dev do
    desc <<-EOS
        Retrieves a list of all submodules.
        Puts value in :submodules.
        Excludes third-party submodules in /plugins
    EOS
    task :get_submodules do
        submodules = []
        (run_locally "git submodule").split("\n").each { |line|
            dir = line.split(" ")[1]
            next if dir =~ /^plugins/
            submodules << dir
        }

        set :submodules, submodules
    end # end dev:get_submodules

    desc <<-EOS
        Checks for oudated submodules on your local working directory compared against the stage branch on beaker.
        Assumes each submodule has a remote named "beaker" with a branch named "stage."
    EOS
    task :outdated_submodules do
        outdatedSubmodules = []
        (fetch :submodules).each { | dir |
            command = <<-EOS
                pushd #{Dir.pwd}/#{dir} >/dev/null
                    submodule_url="$(git remote show beaker | grep 'Fetch URL' | awk '{print $3}')"
                    commish="$(git ls-remote ${submodule_url} stage | grep stage$ | awk '{print $1}')"
                    git branch -r --contains ${commish} 2>/dev/null | grep beaker\/stage$ >/dev/null
                    [ $? -eq 0 ] && echo true || echo false
                popd >/dev/null
            EOS

            Open3.popen3(command) do |stdin, stdout, stderr, wait_thr|
                if stdout.readlines.join("\n").strip! == 'false'
                    outdatedSubmodules << dir
                end
            end
        }

        if outdatedSubmodules.length > 0
            puts
            print "The following submodules are out of date on your local copy compared to beaker:\n"
            outdatedSubmodules.each  { |dir|
                print "   * #{dir}\n"
            }
        end
    end

    desc <<-EOS
        Validates that all submodule indicies in the main repo are pointing to a valid commit.
        This assumes that each submodule has a remote named 'beaker' and a stage branch.
    EOS
    task :validate_submodules do
        nonExistentCommits = {}
        (fetch :submodules).each { | submodule |
            command = <<-EOS
                commish=$(git ls-tree qa #{submodule} | awk '{print $3}')
                pushd #{Dir.pwd}/#{submodule} >/dev/null ;
                    merge_base="$(git merge-base ${commish} remotes/beaker/stage)"
                    echo "${commish} ${merge_base}"
                popd >/dev/null
            EOS

            Open3.popen3(command) do |stdin, stdout, stderr, wait_thr|
                commish, merge_base = stdout.readlines.join("\n").strip!.split(' ')
                if merge_base != commish
                    nonExistentCommits[submodule] = { :reported => commish, :actual => merge_base }
                end
            end
        }

        if nonExistentCommits.length > 0
            puts
            print "The following submodules have incorrect indicies:\n"
            nonExistentCommits.each { |submodule, info|
                print "   * #{submodule}\n"
                print "       * Reported: #{info[:reported]}\n"
                print "       * Actual:   #{info[:actual]}\n"
            }
            puts
            puts "This is most likely caused by pushing out a submodule index before pushing out "
            puts "the submodule itself out to beaker."
            puts Color.red, "This *will* cause any deployments to fail unless this is fixed first.", Color.reset
        end
    end

    namespace :database do
        task :init do
            phpCode = <<-EOS.gsub(/(^\s+|\n)/, ' ')
            <?php
                include_once "#{dev_dir}/app/Config/database.php";
                $db = new DATABASE_CONFIG();
                echo json_encode($db->default);
            EOS
            output = capture("echo '#{phpCode}' | php --")
            db_config = JSON.parse(output)

            cmd = <<-EOS.gsub(/(^\s+|\n)/, ' ')
                mysqladmin -h #{db_config['host']} -u #{db_config['login']} -p -f drop #{db_config['database']} &&
                mysqladmin -h #{db_config['host']} -u #{db_config['login']} -p -f create #{db_config['database']} &&
                cat #{dev_dir}/source/database/#{database_create_script_filename} | \
                    mysql -h #{db_config['host']} -u #{db_config['login']} -p\"${_MYSQL_PASSWORD}\" #{db_config['database']}
            EOS

            run cmd, :env => {'_MYSQL_PASSWORD' => Escape.shell_command([db_config['password']])} do |ch, stream, out|
                ch.send_data "#{db_config['password']}\n" if out =~ /^Enter password/
            end
        end
    end

    namespace :remote do
        desc <<-EOS
            Adds a new remote to all submodules.
        EOS
        task :add do
            ##########################################################################
            # NOTE: The remote name specified *must* match an actual hostname in DNS #
            ##########################################################################

            repo = fetch :repo
            oldloc = "#{repo[:user]}@#{repo[:host]}:"

            puts
            remotename = Capistrano::CLI.ui.ask('Enter remote name to add > ')

            (fetch :submodules).each { |dir|
                # check if the remote already exists in our submodule
                remote_exists = run_locally <<-EOS
                    pushd #{Dir.pwd}/#{dir} >/dev/null &&
                    git remote show &&
                    popd >/dev/null
                EOS

                if remote_exists.split("\n").include?(remotename)
                    puts Color.red, "Remote \"#{remotename}\" for #{dir} already exists; skipping", Color.reset
                    next
                end

                remoteurl = run_locally <<-EOS
                    pushd #{Dir.pwd}/#{dir} >/dev/null &&
                    git remote show origin | grep 'Fetch URL' &&
                    popd >/dev/null
                EOS

                remoteurl = remoteurl.sub("Fetch URL: ", "").strip!

                if remoteurl.include?(oldloc)
                    # submodule is still looking at beaker/git
                    remoteurl = remoteurl.sub(oldloc, '')
                else
                    # submodule is looking at some other remote
                    host = remoteurl.split(":")[0]
                    remoteurl = remoteurl.sub("#{host}:#{repo[:prefix]}/", "")
                end

                url = "#{remotename}:#{repo[:prefix]}/#{remoteurl}"
                url = "#{oldloc}#{remoteurl}" if remotename =~ /beaker|git/i

                run_locally <<-EOS
                    pushd #{Dir.pwd}/#{dir} &&
                    git remote add #{remotename} #{url} &&
                    popd >/dev/null
                EOS
            }
        end # end dev:remote:add

        desc <<-EOS
            Deletes a remote from all submodules.
        EOS
        task :delete do
            puts
            remotename = Capistrano::CLI.ui.ask('Enter remote name to delete > ')

            (fetch :submodules).each { |dir|
                run_locally <<-EOS
                    pushd #{Dir.pwd}/#{dir} >/dev/null &&
                    git remote show | grep #{remotename} >/dev/null &&
                    [ $? -eq 0 ] && git remote rm #{remotename} &&
                    popd >/dev/null
                EOS
            }
        end # end dev:remote:delete
    end # end dev:remote

    namespace :setup do
        desc <<-EOS
            Sets up staging node configuration.
        EOS
        task :stage do
            puts
            nodename = Capistrano::CLI.ui.ask("Enter name of remote node > ")
            username = Capistrano::CLI.ui.ask("Enter remote node user > ")
            domain = Capistrano::CLI.ui.ask("Enter domain (optional) > ")
            deploy_to = Capistrano::CLI.ui.ask("Enter deploy path (optional) > ")
            dev_dir = Capistrano::CLI.ui.ask("Enter remote development directory > ")
            branch = Capistrano::CLI.ui.ask("Enter deploy branch [qa] > ")

            # defaults
            username = Etc.getlogin if username =~ /^$/
            domain = "enterprise.licensing.#{nodename}.iowai.org" if domain =~ /^$/
            deploy_to = "/var/www/sites/#{domain}" if deploy_to =~ /^$/
            dev_dir = "/home/#{username}/Workspace/iowai/enterprise_licensing" if dev_dir =~ /^$/
            branch = "qa" if branch =~ /^$/
            cakeVersion = '2.2.1' # hardcode for now to account for Windows users

            conf = <<-EOS.gsub(/^\s+/, '')
                server '#{nodename}', :app, :web, :db, :primary => true
                #
                set :user, "#{username}"
                set :domain, "#{domain}"
                set :deploy_to, "#{deploy_to}"
                set :dev_dir, "#{dev_dir}"
                set :branch, "#{branch}"
                set :cake_version, "#{cakeVersion}"
                #
                role :web, "#{nodename}"
                role :app, "#{nodename}"
                role :db, "#{nodename}", :primary => true
            EOS

            fp = File.open("#{Dir.pwd}/config/deploy/#{nodename}.rb", "w")
            fp.write(conf)
            fp.close()

            puts Color.green, "Generated #{Dir.pwd}/config/deploy/#{nodename}.rb", Color.reset
        end # end dev:setup:stage

        desc <<-EOS
            Updates all submodule origins to point back to your app node repos.
        EOS
        task :submodule_origins do
            repo = fetch :repo
            remotehost = capture("echo $CAPISTRANO:HOST$").strip

            (fetch :submodules).each { |dir|
                remoteurl = run_locally <<-EOS
                    pushd #{Dir.pwd}/#{dir} >/dev/null &&
                    git remote show origin &&
                    popd >/dev/null
                EOS

                remoteurl = remoteurl.split("\n")[1]
                remoteurl = remoteurl.sub("Fetch URL: ", "").strip!
                remoteurl = remoteurl.sub("#{repo[:user]}@#{repo[:host]}:", "")
                url = "#{remotehost}:#{repo[:prefix]}/#{remoteurl}"

                run_locally <<-EOS
                    pushd #{Dir.pwd}/#{dir} >/dev/null &&
                    git remote set-url origin #{url} &&
                    popd >/dev/null
                EOS
            }
        end # end dev:setup:submodule_origins

        desc <<-EOS
            Sets up the working directory on the remote host.
            Working directory path comes from :dev_dir defined in your stage.rb file.
        EOS
        task :working_directory do
            repo = fetch :repo
            remote_repo = "#{repo[:prefix]}/#{repo[:path]}"

            dir_exists = capture("[ -d #{dev_dir} ] && echo 1 || echo 0").to_i
            if dir_exists == 1
                answer = Capistrano::CLI.ui.ask("Remote working directory already exists. Continue? [yN] > ")
                next if answer =~ /n|no|^$/i
            end

            run <<-EOS
                git clone #{remote_repo} #{dev_dir} &&
                pushd #{dev_dir} >/dev/null &&
                git submodule update -q --init --recursive &&
                popd >/dev/null
            EOS
        end # end dev:setup:working_directory

        desc <<-EOS
            Sets up remote working copy for legacy data import.
            Requires :dev_dir set in stage config.
        EOS
        task :import do
            run <<-EOS
                mkdir -p #{dev_dir}/app/webroot/files/accounts/photos &&
                find #{dev_dir}/app/webroot/files/accounts/photos -type f -delete &&
                find #{dev_dir}/app/tmp -type f -delete &&
                mkdir -p #{dev_dir}/app/tmp/logs/import_errors &&
            EOS

            run_locally <<-EOS
                mkdir -p #{Dir.pwd}/app/webroot/files/accounts/photos &&
                find #{Dir.pwd}/app/webroot/files/accounts/photos -type f -delete
            EOS
        end # end dev:setup:import

        desc <<-EOS
            Sets up remote working directory for normal web development.
            Requires :dev_dir set in stage config.
        EOS
        task :web do
            commands = <<-EOS
                #{sudo} chgrp -R apache #{dev_dir}/app/tmp &&
                #{sudo} chgrp -R apache #{dev_dir}/app/webroot/files &&
                #{sudo} find #{dev_dir}/app/tmp -type d -print0 | xargs -0 chmod 775 &&
                #{sudo} find #{dev_dir}/app/webroot/files -type d -print0 | xargs -0 chmod 775
            EOS

            run commands, {:pty => true}
        end # end dev:setup:web

        desc <<-EOS
            Sets up shared directories on the remote working directory.
        EOS
        task :remote_shared_dirs do
            basedir = "/tmp/cake/shared/#{domain}"
            # create cache dirs and make them writable by the webserver
            run "mkdir -p #{basedir}/tmp/{cache/{models,persistent,views},logs,sessions,tests}"
            run "#{sudo} chgrp -R apache #{basedir}/tmp && #{sudo} chmod -R 775 #{basedir}/tmp", {:pty => true}
            run "rm -fr #{dev_dir}/app/tmp && ln -s #{basedir}/tmp #{dev_dir}/app/tmp"
            # create file upload directory and symlink
            run "mkdir -p #{basedir}/files"
            run "#{sudo} chgrp -R apache #{basedir}/files && #{sudo} chmod -R 775 #{basedir}/files", {:pty => true}
            run "rm -fr #{dev_dir}/app/webroot/files && ln -s #{basedir}/files #{dev_dir}/app/webroot/files"
        end

        desc <<-EOS
            Initializes your development environment.
        EOS
        task :environment do
            repo.mirror.init
            dev.setup.submodule_origins
            dev.setup.working_directory
            dev.sync.working_directory
        end # end dev:setup:environment
    end # end dev:setup

    namespace :sync do
        desc <<-EOS
            Syncs local directory to remote.
            Requires :dev_dir set in stage
        EOS
        task :working_directory do
            remotehost = capture("echo $CAPISTRANO:HOST$").strip

            args = ["rsync", "-az", "#{Dir.pwd}/", "#{user}@#{remotehost}:#{dev_dir}/"]
            flags = %w(--delete)
            excludes = %w(
                app/Config/database.php
                app/tmp
                app/webroot/files
                app/webroot/files/accounts/photos
                source/database/backups
                source/database/legacy_data_files
                source/photos
            )

            command = args
            command += flags
            command += excludes.map { |d| "--exclude #{d}" }
            rsync = command.join(' ')

            run_locally rsync
        end # end dev:sync:working_directory

        desc <<-EOS
            Syncs imported account photos to our remote working copy.
        EOS
        task :photos do
            remotehost = capture("echo $CAPISTRANO:HOST$").strip

            args = [
                "rsync",
                "-az",
                "#{Dir.pwd}/app/webroot/files/accounts/photos/",
                "#{user}@#{remotehost}:#{shared_path}/files/accounts/photos/"
            ]
            flags = %w(--delete)

            command = args
            command += flags
            rsync = command.join(' ')

            run_locally rsync
        end # end dev:sync:photos

        desc <<-EOS
            Syncs submodules with specified remote.
            Automatically fetches the stage branch for all submodules, user is responsible for merging in case
            there are merge conflicts that have to be resolved manually anyway.
        EOS
        task :submodules do
            puts
            remotename = Capistrano::CLI.ui.ask('Enter remote to sync from [beaker] > ')
            remotename = 'beaker' if remotename =~ /^$/

            branchname = Capistrano::CLI.ui.ask('Enter branch to fetch [stage] > ')
            branchname = 'stage' if branchname =~ /^$/
            puts

            regex = Regexp.new(" #{remotename}/#{branchname}$")

            submodules_to_update = []
            (fetch :submodules).each { |dir|
                # `git fetch/pull` sends output to STDERR for some reason...
                command = <<-EOS
                    pushd #{Dir.pwd}/#{dir} >/dev/null ;
                    git fetch -v #{remotename} 3>&1 1>/dev/null 2>&3 ;
                    popd >/dev/null
                EOS

                print "checking #{remotename}/#{branchname} :: #{dir}..."

                Open3.popen3(command) do |stdin, stdout, stderr, wait_thr|
                    stdout.readlines.each { |line|
                        next if line =~ /^From /
                        next if ! regex.match(line) # only grab our specified branch

                        if line =~ /up to date/
                            print Color.green, 'OK', Color.reset
                            break
                        end

                        submodules_to_update << dir
                        print Color.green, 'MERGE_NEEDED', Color.reset
                        break
                    }
                end

                print "\n"
            }

            print "\n"

            submodules_to_update.each { |dir|
                print "Found updated ", Color.green, branchname, Color.reset, " in submodule ", Color.green, dir, Color.reset
                print " on remote ", Color.green, remotename, Color.reset, "\n"
                print "    To view diff: $ cd ./#{dir}; git diff #{remotename}/#{branchname}\n"
                print "    To merge:     $ cd ./#{dir}; git merge #{remotename}/#{branchname}\n\n"
            }
        end
    end # end dev:sync
end # end dev
