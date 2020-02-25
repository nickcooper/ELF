server 'qa', :app, :web, :db, :primary => true

#
set :server, 'qa'

#
agency = fetch(:agency)

# backup db before deployment?
set :backup_db, false

#
set :domain,        'elf.qa.iowai.loc'
set :deploy_to,     "/raid/sites/enterprise_licensing/#{agency}"
set :branch,        ENV['BRANCH'] || 'qa'
set :user,          'deploy'
set :cake_version,  '2.2.1'
set :deploy_via, :remote_cache

#
set :www_user, 'apache'
set :www_group, 'apache'
set :users_group, 'deploy'

#
set :bz2_location, 'qa'

##
role :web, "qa"
role :app, "qa"
role :db,  "qa", :primary => true

#
#after "deploy:finalize_update", "deploy:cakephp:testsuite"

#
set :keep_releases, 2