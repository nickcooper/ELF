server 'red', :app, :web, :db, :primary => true

#
agency = fetch(:agency)

#
set :domain,        'elf_qa.iowai.loc'
set :deploy_to,     "/var/www/sites/nightly_builds/elf_uat/#{agency}"
set :branch,        ENV['BRANCH'] || 'qa'
set :user,          'deploy'
set :cake_version,  '2.2.1'
set :deploy_via, :remote_cache

set :bz2_location, 'red'

#
set :www_user, 'apache'
set :www_group, 'apache'
set :users_group, 'deploy'

#
set :keep_releases, 2