=begin
    Deployment settings
=end
set :application, "Enterprise Licensing"
set :agency, ENV['agency'] || nil
set :keep_releases, 5
set :use_sudo,  false
set :local, ENV['local'] || nil
set :backup_db, ENV['backup_db'] || nil
default_run_options[:shell] = '/bin/bash'


=begin
    Repository settings
=end
set :repository_base, "git@git.iowai.loc:IOWAI/ELF"
set :repository,  "#{repository_base}/enterprise_licensing.git"
set :gitweb, "https://git.iowai.loc"
set :scm, :git
set :git_enable_submodules, true
set :deploy_via, 'remote_cache'

set :copy_exclude, [
    ".git", ".svn", ".hg", ".bzr", "CVS",
    ".DS_Store", "Thumbs.db",
    "app/Config/core.php", "app/Config/database.php", "app/Config/ii_core.php", "app/Config/agency_config.php",
    "app/tmp", "app/webroot/files/", "app/Plugins/OutputDocuments/Vendor/tcpdf/cache",
    "lib",
    "Capfile", "Gemfile", "Gemfile.lock",
    "build.xml", "build.properties"
]


=begin
    Callbacks.
=end
after "deploy:setup", "deploy:setup_shared_dir"

=begin
    Define agency deployable versions
=end
def get_versions (agency)
    if agency == 'ehsp'
        versions = ['1.11.2.3', '1.12.0.0', '1.12.1.0']
    else
        versions = []
    end

    return versions
end

=begin
    Deploy namespace.
=end
namespace :deploy do
    desc <<-EOS
        Override finalize_update. Creates symbolic links for files kept in the shared/agency directories.
    EOS
    task :finalize_update, :roles => :app do
        # Link configuration files
        run "ln -s #{shared_path}/Config/core.php #{latest_release}/app/Config/core.php"
        run "ln -s #{shared_path}/Config/database.php #{latest_release}/app/Config/database.php"

        # Link uploaded files.
        run "ln -s #{shared_path}/files #{latest_release}/app/webroot/files"

        # Link tmp
        run "rm -rf #{latest_release}/app/tmp"
        run "ln -s #{shared_path}/tmp #{latest_release}/app/tmp"

        # Link cake libs
        run "ln -s #{shared_path}/cake_lib_#{cake_version} #{latest_release}/lib"

        # Link the ii_core file
        run "ln -s #{shared_path}/Config/ii_core.php #{latest_release}/app/Config/ii_core.php"

        # Link the event_listeners file
        cmd = <<-EOS.gsub(/(^\s+|\n)/, ' ')
            [ -f #{latest_release}/source/deployment/vanilla/event_listeners.php ]
            && ln -s #{latest_release}/source/deployment/vanilla/event_listeners.php #{latest_release}/app/Config
            || echo 'File did not exist, did not sym link.'
        EOS
        run cmd

        # Link the agency_config file
        run "ln -s #{latest_release}/source/deployment/#{agency}/agency_config.php #{latest_release}/app/Config"

        # Link the PDF cache directory
        run "rm -fr #{latest_release}/app/Plugin/OutputDocuments/Vendor/tcpdf/cache"
        run "ln -s #{shared_path}/tmp/cache/pdfs #{latest_release}/app/Plugin/OutputDocuments/Vendor/tcpdf/cache"

        # Copy the agency's logo
        run "cp #{latest_release}/source/deployment/#{agency}/img/logo.png #{latest_release}/app/webroot/img/graphics"

        # Link the agency's output documents
        run "ln -s #{latest_release}/source/deployment/#{agency}/output_documents/Elements #{latest_release}/app/Plugin/OutputDocuments/View/Elements/agency"
        run "ln -s #{latest_release}/source/deployment/#{agency}/output_documents/Layouts #{latest_release}/app/Plugin/OutputDocuments/View/Layouts/agency"

        # Link the agency's home_page file
        run "ln -s #{latest_release}/source/deployment/#{agency}/home.ctp #{latest_release}/app/Plugin/Pages/View/Pages/"

        # Link Debugkit and AclExtras from plugins/ to app/Plugins
        debugkit.link
        aclextras.link

        # remove older deployed versions, keep a maximum of :num_releases
        cleanup
    end # finalize_update

    # ------------------------------------------------------

    desc <<-EOS
        Full ELF deployment (Codebase, Database, Permission and Maintenance modes.
    EOS
    task :full, :roles => :db, :only => { :primary => true } do
        # verify the inputs (environment, agency, version)
        domain = fetch(:domain)

        agency = fetch(:agency)
        if agency.nil?
            error = CommandError.new("Agency not defined.")
            raise error
        end

        version = ENV['version']
        if version.nil?
            error = CommandError.new("Version not defined.")
            raise error
        end

        # double check the version is deployable
        if !get_versions agency.include? version
            error = CommandError.new("Version is not deployable.")
            raise error
        end

        # maintenance mode on
        maintenance.on

        # define the branch as the version number
        set :branch, version

        if local == 'true'
            puts 'performing full deployment to local app node'
            # do a full deploy to local app node for full integration testing

#            set :repository_base, ENV['repository_base']
#            set :repository_base, "twoface:/opt/git/IOWAI/ELF"

            deploy.default

        else
            # get the new codebase from git
            deploy.default
        end

        # reset the .htaccess files to set the site back in maintenance mode for the remainder of the deployment
        run "cp #{shared_path}/system/Maintenance/maintenance.html #{latest_release}/app/webroot/maintenance.html"
        run "rm -f #{latest_release}/app/.htaccess"
        run "ln -s #{latest_release}/app/webroot/.htaccess.save #{latest_release}/app/.htaccess"
        run "rm -f #{latest_release}/app/webroot/.htaccess"
        run "ln -s #{shared_path}/system/Maintenance/.htaccess_Maint #{latest_release}/app/webroot/.htaccess"

        # database
        if domain == 'licensing.iowa.gov'
            # production env
            # do not use the snap database

            # skip creating backup copy of db during local deployments
            if backup_db.nil? || backup_db == true
                # backup the database
                if current_release
                    data.backup
                end
            end

            # run the sql scripts for the specified version
            data.scripts

            # run cake shell scripts for the specified version
            deploy.cake_shells.scripts

            # reset the acl permissions
            data.acl

        else
            # uat, qa, local dev nodes
            data.reset
        end

        # clear the cake cache
        deploy.cache.clear

        # maintenance off
        maintenance.off

    end # deploy.full

    # ------------------------------------------------------

    desc <<-EOS
        Sets up the shared dir
    EOS
    task :setup_shared_dir do

        remotehost = capture("echo $CAPISTRANO:HOST$").strip!

        # Copy up our current version of cake
        run "mkdir -p #{shared_path}/cake_lib_#{cake_version}"
        run_locally "rsync -az #{Dir.pwd}/lib/Cake #{user}@#{remotehost}:#{shared_path}/cake_lib_#{cake_version}/"

        # Create subdirs
        run "mkdir -p #{shared_path}/system/Maintenance #{shared_path}/Config #{shared_path}/files/accounts/photos/"
        run "mkdir -p #{shared_path}/tmp/{logs,sessions,tests,cache/{models,persistent,views,pdfs}}"

        # Copy maintenance files
        run_locally "rsync -az #{Dir.pwd}/source/deployment/Maintenance/ #{user}@#{remotehost}:#{shared_path}/system/Maintenance/"

        # Write .htaccess files with our rewrite base
        rewriteBase = File.basename("#{deploy_to}")
        htaccess = {
            :normal => "
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /#{rewriteBase}/
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

",
            :maintenance => "
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /#{rewriteBase}/
    RewriteRule ^(.*)$ maintenance.html [QSA,L]
</IfModule>

"
        }

        put htaccess[:normal], "#{shared_path}/system/Maintenance/.htaccess.save"
        put htaccess[:maintenance], "#{shared_path}/system/Maintenance/.htaccess_Maint"

        # Copy config files
        top.upload("#{Dir.pwd}/app/Config/ii_core.php.default", "#{shared_path}/Config/ii_core.php.default")
        top.upload("#{Dir.pwd}/app/Config/core.php", "#{shared_path}/Config/core.php.default")
        top.upload("#{Dir.pwd}/app/Config/database.php.default", "#{shared_path}/Config/database.php.default")

        # Fix directory permissions (requires sudo)
        run "#{sudo} chgrp -R #{www_group} #{shared_path}/tmp && find #{shared_path}/tmp -type d -print0 | xargs -0 chmod 775", {:pty => true}
        run "#{sudo} chgrp -R #{www_group} #{shared_path}/files && find #{shared_path}/files -type d -print0 | xargs -0 chmod 775", {:pty => true}
        run "#{sudo} find #{shared_path}/files -type f -exec chmod 644 {} \\;", {:pty => true}

        # Print warning messages to instruct user to edit remote host's database credentials, A&A configuration, etc.
        messages = [
            "Please edit the following files before running the initial deployment to match your environment setup:",
            "    * #{shared_path}/Config/core.php",
            "    * #{shared_path}/Config/ii_core.php",
            "    * #{shared_path}/Config/database.php",
        ]

        puts
        messages.each { |msg| print Color.red, "#{msg}\n", Color.reset }
        puts
    end # deploy:setup_shared_dir

    # ------------------------------------------------------

    namespace :cache do
        desc <<-EOS
            Clears application cache.
        EOS

        task :clear do
            # change cache files to be writable by deploy user
            # depends on sudo setup properly on remote server
            run "#{sudo} chgrp -R #{users_group} #{latest_release}/app/tmp/cache", {:pty => true}
            run "for FILE in $(find #{latest_release}/app/tmp/cache -type f); do #{sudo} chmod g+w $FILE; done", {:pty => true}
            run "#{latest_release}/app/Console/cake -app #{latest_release}/app clear_cache"
            run "#{sudo} chgrp -R #{www_group} #{latest_release}/app/tmp/cache", {:pty => true}
        end # deploy:cache:clear
    end # deploy:cache

    namespace :cake_shells do
        desc <<-EOS
            Runs the specified cake shell scripts
        EOS

        task :scripts do
            # build the vanilla user accounts for all environments except Production
            if domain == 'licensing.iowa.gov'
                # production env
                # do not run any cake shell scripts on Production
            else
                run "#{latest_release}/app/Console/cake vanilla_account_import"
            end

            if ENV['version'] == '1.12.0.0' || ENV['version'] == '1.12.1.0'
              run "#{latest_release}/app/Console/cake fix_application_submission_ids"
              run "#{latest_release}/app/Console/cake fix_missing_current_application_ids"
              run "#{latest_release}/app/Console/cake add_questions_to_answers"
              run "#{latest_release}/app/Console/cake fix_payment_item_records"
              run "#{latest_release}/app/Console/cake ehsp_billing_items_update"
            end
        end # deploy:cake_shells:scripts
    end # deploy:cake_shells
end # namespace deploy



=begin
    Data namespace
=end
namespace :data do
    desc <<-EOS
        Calls all the tasks necessary to reset the database to the data for the code version specified.
    EOS
    task :reset do
        version = ENV['version']
        puts "running data:reset for the following version: #{version}"

        # skip creating backup copy of db during local deployments
        if backup_db.nil? || backup_db == true
            # backup the database
            if current_release
                data.backup
            end
        end

        # re-initialize the database using the earliest deployable version's snapshot
        data.snap

        # run the sql scripts for the specified version
        data.scripts

        # run cake shell scripts for the specified version
        deploy.cake_shells.scripts

        # reset the acl permissions
        data.acl
    end # data:reset

    # ------------------------------------------------------

    desc <<-EOS
        Retrieve remote database configuration.
        Extracts the default datasource credentials and puts them in :db_config to be used in other tasks.
    EOS
    task :read_db_config do
        phpCode = <<-EOS.gsub(/(^\s+|\n)/, ' ')
        <?php            include_once "#{deploy_to}/shared/Config/database.php";
            $db = new DATABASE_CONFIG();
            echo json_encode($db->default);
        EOS

        output = capture("echo '#{phpCode}' | php --")

        set :db_config, JSON.parse(output)
    end # data:read_db_config

    # ------------------------------------------------------

    desc <<-EOS
        Backup remote database to remote current release directory.
    EOS
    task :backup, :roles => :db, :only => { :primary => true } do
        read_db_config

        db_config = fetch :db_config

        now = Time.now.strftime('%Y%m%d%H%M%S')
        filename = "#{deploy_to}/current/#{db_config['database']}-#{now}.sql.bz2"

        # full DB dump
        cmd = <<-EOS.gsub(/(^\s+|\n)/, ' ')
            mysqldump --lock-tables=false -h #{db_config['host']} -u #{db_config['login']} -p #{db_config['database']} | bzip2 -9 > #{filename}
        EOS

        run cmd do |ch, stream, out|
            ch.send_data "#{db_config['password']}\n" if out =~ /^Enter password/
        end
    end # data:backup

    # ------------------------------------------------------

    desc <<-EOS
        Installs remote database for Elf installations.
        Loads the snap.sql.bz2 file from host red or from the local app node.
    EOS
    task :snap, :roles => :db, :only => { :primary => true } do
        # verify the inputs (environment, agency, version)
        domain = fetch(:domain)

        agency = fetch(:agency)
        if agency.nil?
            error = CommandError.new("Agency not defined.")
            raise error
        end

        # grab the earliest deployable version number as the snap version number
        all_versions = get_versions agency
        version = all_versions[0]

        # get the remote db config
        read_db_config
        db_config = fetch :db_config

        if domain == 'licensing.iowa.gov'
            # don't run snap on production
            cmd = <<-EOS.gsub(/(^\s+|\n)/, "")
            EOS
        elsif domain == 'elf.qa.iowai.loc'
            # QA sql commands
            cmd = <<-EOS.gsub(/(^\s+|\n)/, "")
                bzcat ~/nightly_build_snaps/#{agency}-#{version}.sql.bz2 | \
                    mysql -h #{db_config['host']} -u #{db_config['login']} -p\"${_MYSQL_PASSWORD}\" #{db_config['database']};
            EOS
        elsif domain == 'test-licensing.iowa.gov'
            # UAT sql commands
            cmd = <<-EOS.gsub(/(^\s+|\n)/, "")
                bzcat ~/#{agency}-#{version}.sql.bz2 | \
                    mysql -h #{db_config['host']} -u #{db_config['login']} -p\"${_MYSQL_PASSWORD}\" #{db_config['database']};
            EOS
        else
            # DEV sql commands
            cmd = <<-EOS.gsub(/(^\s+|\n)/, "")
                bzcat /tmp/#{agency}-#{version}.sql.bz2 | \
                    mysql -h #{db_config['host']} -u #{db_config['login']} -p\"${_MYSQL_PASSWORD}\" #{db_config['database']};
            EOS
        end

        run cmd, :env => {'_MYSQL_PASSWORD' => Escape.shell_command([db_config['password']])} do |ch, stream, out|
            ch.send_data "#{db_config['password']}\n" if out =~ /^Enter password/
        end
    end # data:snap

    # ------------------------------------------------------

    desc <<-EOS
        Load the specified vanilla and agency scripts on target database.
    EOS
    task :scripts, :roles => :db, :only => { :primary => true } do
        agency = fetch(:agency)
        if agency.nil?
            error = CommandError.new("Agency not defined.")
            raise error
        end

        version = ENV['version']
        if version.nil?
            error = CommandError.new("Version not defined.")
            raise error
        end

        # grab the snap version
        all_versions = get_versions agency
        snap_version = all_versions[0]

        # get the remote db config
        read_db_config
        db_config = fetch :db_config

        # run scripts flag
        flag = false

        # run the correct scripts
        for v in get_versions agency
            cvbits = v.split('.')
            vbits = version.split('.')

            length = cvbits.length
            if vbits.length > cvbits.length
                length = vbits.length
            end

            while cvbits.length < length
                cvbits[cvbits.length] = 0
            end

            while vbits.length < length
                vbits[vbits.length] = 0
            end

            # if the loop version is greater than the deployment version break out of loop
            if length > 3 && cvbits[0] == vbits[0] && cvbits[1] == vbits[1] && cvbits[2].to_i == vbits[2].to_i && cvbits[3].to_i > vbits[3].to_i
                break
            end

            if length > 2 && cvbits[0] == vbits[0] && cvbits[1] == vbits[1] && cvbits[2].to_i > vbits[2].to_i
                break
            end

            if length > 1 && cvbits[0] == vbits[0] && cvbits[1].to_i > vbits[1].to_i
                break
            end

            if cvbits[0].to_i > vbits[0].to_i
                break
            end

            # if run flag is true then attempt to run vanilla and agency version scripts
            if flag
                # check to see if remote vanilla file exists
                file_exists = false
                invoke_command("if [ -e '#{latest_release}/source/deployment/vanilla/sql_scripts/#{v}.sql' ]; then echo -n 'true'; fi") do |ch, stream, out|
                    file_exists = []
                    file_exists << (out == 'true')
                end

                if file_exists
                    # run the script
                    cmd = <<-EOS.gsub(/(^\s+|\n)/, ' ')
                        mysql -h #{db_config['host']} -u #{db_config['login']} -p\"${_MYSQL_PASSWORD}\" #{db_config['database']} <
                            #{latest_release}/source/deployment/vanilla/sql_scripts/#{v}.sql;
                    EOS

                    run cmd, :env => {'_MYSQL_PASSWORD' => Escape.shell_command([db_config['password']])} do |ch, stream, out|
                        ch.send_data "#{db_config['password']}\n" if out =~ /^Enter password/
                    end
                end

                # check to see if remote agency file exists
                file_exists = false
                invoke_command("if [ -e '#{latest_release}/source/deployment/#{agency}/sql_scripts/#{v}.sql' ]; then echo -n 'true'; fi") do |ch, stream, out|
                    file_exists = []
                    file_exists << (out == 'true')
                end

                if file_exists
                    # run the script
                    cmd = <<-EOS.gsub(/(^\s+|\n)/, ' ')
                        mysql -h #{db_config['host']} -u #{db_config['login']} -p\"${_MYSQL_PASSWORD}\" #{db_config['database']} <
                            #{latest_release}/source/deployment/#{agency}/sql_scripts/#{v}.sql;
                    EOS

                    run cmd, :env => {'_MYSQL_PASSWORD' => Escape.shell_command([db_config['password']])} do |ch, stream, out|
                        ch.send_data "#{db_config['password']}\n" if out =~ /^Enter password/
                    end
                end
            end

            # we only want to run scripts for verions after the snap version.
            # otherwise the scripts will fail and fail our deployment.
            if v == snap_version
                flag = true
            end
        end
    end # data:scripts

    # ------------------------------------------------------

    desc <<-EOS
        Runs the permission sql scripts on target database.
        This updates the aros, acos, and aros_acos tables.
    EOS
    task :acl, :roles => :db, :only => { :primary => true } do
        agency = fetch(:agency)

        if agency.nil?
            error = CommandError.new("Agency is not defined.")
            raise error
        end

        run [
            "pushd #{latest_release} >/dev/null",
            "#{latest_release}/app/Console/cake  AclExtras.AclExtras aco_sync",
            "popd >/dev/null"
        ].join(' && ')

        run [
            "pushd #{latest_release} >/dev/null",
            "#{latest_release}/app/Console/cake  acl_setup setPermissions #{latest_release}/source/deployment/#{agency}/permissions.csv",
            "popd >/dev/null"
        ].join(' && ')
    end # data:acl

    # ------------------------------------------------------

    desc <<-EOS
        Initializes target database for ELF installations.
        Drops the schema, creates it agaain, loads vanilla data, loads agency data.
    EOS
    task :build, :roles => :db, :only => { :primary => true } do
        agency = fetch(:agency)

        if agency.nil?
            error = CommandError.new("Agency is not defined.")
            raise error
        end

        data.vanilla   # load the vanilla data
        data.agency    # load the agency's deploy.csv
    end # data:build

    # ------------------------------------------------------

    desc <<-EOS
        Loads the vanilla data.
    EOS
    task :vanilla do
        run [
            "pushd #{latest_release} >/dev/null",
            "#{latest_release}/app/Console/cake Deploy",
            "popd >/dev/null"
        ].join(' && ')
    end # data:vanilla

    # ------------------------------------------------------

    desc <<-EOS
        Loads the agency data for a specific agency
    EOS
    task :agency do
        agency = fetch(:agency)

        if agency.nil?
            error = CommandError.new("Agency is not defined.")
            raise error
        end

        run [
            "pushd #{latest_release} >/dev/null",
            "#{latest_release}/app/Console/cake Deploy --agency #{agency}",
            "popd >/dev/null"
        ].join(' && ')
    end # data:agency
end # namespce data


=begin
    Maintenance namespace
=end
namespace :maintenance do
    desc <<-EOS
        Puts remote site in maintenance mode.
    EOS
    task :on do
        # if there isn't a current release then use the latest release dir
        if current_release
            num_releases = capture("ls -x1 #{deploy_to}/releases | wc -l").to_i
            if num_releases > 0
                run "rm -f #{current_release}/app/webroot/.htaccess"
                run "cp #{shared_path}/system/Maintenance/maintenance.html #{current_release}/app/webroot/maintenance.html"
                run "cp #{shared_path}/system/Maintenance/.htaccess_Maint #{current_release}/app/webroot/.htaccess_Maint"
                run "ln -s #{current_release}/app/webroot/.htaccess_Maint #{current_release}/app/webroot/.htaccess"
            end
        end

    end # maintenance:on

    # ------------------------------------------------------

    desc <<-EOS
        Removes remote site from maintenance mode.
    EOS
    task :off do
        num_releases = capture("ls -x1 #{deploy_to}/releases | wc -l").to_i
        if num_releases > 0
            run "rm -f #{latest_release}/app/webroot/.htaccess"
            run "rm -f #{latest_release}/app/webroot/maintenance.html"
            run "cp #{shared_path}/system/Maintenance/.htaccess.save #{latest_release}/app/webroot/"
            run "ln -s #{latest_release}/app/webroot/.htaccess.save #{latest_release}/app/webroot/.htaccess"
         end
    end # maintenance:off
end # namespace maintenance



namespace :debugkit do
    desc <<-EOS
        Links to the debug kit.
    EOS

    task :link do
        run "ln -s #{latest_release}/plugins/DebugKit/ #{latest_release}/app/Plugin/"
    end # deploy:debugkit:link
end # deploy:debugkit



namespace :aclextras do
    desc <<-EOS
        Links to the acl extras plugin.
    EOS

    task :link do
        run "ln -s #{latest_release}/plugins/AclExtras/ #{latest_release}/app/Plugin/"
    end # deploy:aclextras:link
end # deploy:aclextras