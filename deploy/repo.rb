
#
# Repository-related operations
#

before 'repo:mirror:init', 'repo:parse', 'repo:get_all_repos'
before 'repo:mirror:sync', 'repo:parse'

namespace :repo do
    desc <<-EOS
        Parses :repository and returns user, host, path, etc.
    EOS
    task :parse do
        repo_user_host, repo_path = (fetch :repository).split(':')
        repo_user, repo_host = repo_user_host.split('@')

        set :repo, {
            :user => repo_user,
            :host => repo_host,
            :path => repo_path,
            :rel_path => repo_path.sub(/\/enterprise_licensing\.git$/, ''),
            :prefix => '/opt/git'
        }
    end # end repo:parse

    desc <<-EOS
        Retrieves all enterprise licensing repos from origin.
    EOS
    task :get_all_repos do
        # grab from gitweb since we can't simply rsync
        gitweb = fetch :gitweb
        set :all_repos, capture("curl --insecure --silent #{gitweb}/?a=project_index | egrep 'ELF|CakePHP'").split("\n")
    end # end repo:get_all_repoos

    namespace :mirror do
        desc <<-EOS
            Initializes a new mirror of the ELF repos on our remote host.
        EOS
        task :init do
            repo = fetch :repo

            mirror_exists = capture("[ -d #{repo[:prefix]} ] && echo 1 || echo 0").to_i
            if mirror_exists == 1
                next if Capistrano::CLI.ui.ask('Remote mirror already exists, continue? [yN] > ') =~ /N|^$/i
            end

            run "rm -fr #{repo[:prefix]} && mkdir -p #{repo[:prefix]}"

            # grab all our enterprise licensing repos from gitweb since we can't simply rsync from origin
            # clone each repo as a bare repo
            (fetch :all_repos).each { |current_repo|
                src = "#{repo[:user]}@#{repo[:host]}:#{current_repo}"
                dest = "#{repo[:prefix]}/#{current_repo}"
                run "git clone --bare -q #{src} #{dest}"
            }
        end # end repo:mirror:init
    end # end repo:mirror
end # end repo
