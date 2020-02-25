server 'test-licensing.iowa.gov', :app, :web, :db, :primary => true

#
agency = fetch(:agency)

#
set :domain,        'test-licensing.iowa.gov'
set :deploy_to,     "/portal/iowa/apps/test-licensing.iowa.gov/#{agency}"
set :branch,        ENV['BRANCH'] || 'uat'
set :user,          'deploy'
set :cake_version,  '2.2.1'
set :repository_base, "/portal/iowa/apps/test-licensing.iowa.gov/deploy/REPOS/"
set :deploy_via, 'copy'

#
set :www_user, 'www-data'
set :www_group, 'www-data'
set :users_group, 'deploy'