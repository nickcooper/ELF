server 'license-prod1.iowa.vipnet.org', :app, :web, :db, :primary => true
#
agency = fetch(:agency)

#
set :domain,        'licensing.iowa.gov'
set :deploy_to,     "/portal/iowa/apps/licensing.iowa.gov/#{agency}"
set :branch,        'master'
set :user,          'deploy'
set :cake_version,  '2.2.1'
set :deploy_via, 'copy'

#
set :www_user, 'www-data'
set :www_group, 'www-data'
set :users_group, 'deploy'
