This project uses the Laravel 9 Framework and assumes the following development stack is available

PHP 8.1 or later
Apache
Node v16

The application follows typical Laravel 9 conventions.


***************************************************
Hosting Environment
***************************************************
This application is assumed to be running on a Linode managed by Serverpilot.io
Once initial configuration within the Serverpilot control panel has been performed, some further tasks are needed to
prepare the server for automated deployments using the included Deployer.org deploy.php script



***************************************************
Creating a Deployer user
***************************************************
ssh root@139.162.210.187
# Add user called deployer
adduser deployer
# Add to sudoers
usermod -aG sudo deployer

# To allow the deployer user to restart  php-fpm services without entering a password
visudo
# Paste the lines below into the visudo editor

# Allow specified users to restart php-fpm without a password
deployer  ALL=(ALL) NOPASSWD: /usr/sbin/service php*.*-fpm-sp restart

# Copy local SSH Key over to server
ssh-copy-id -i ~/.ssh/id_rsa.pub deployer@139.162.210.187
# Check you can login without needing a password
ssh deployer@139.162.210.187
# Check you can restart php iwthout entering a password
echo "" | sudo -S service php8.1-fpm-sp restart


***************************************************
Changing php-cli to v8.1
***************************************************
# See https://serverpilot.io/docs/how-to-change-the-version-of-the-php-command/
sudo apt-get install sp-php-cli
sudo dpkg-reconfigure sp-php-cli
# Choose PHP8.1


***************************************************
SQL Server config tweak
***************************************************
ssh root@139.162.210.187
nano /etc/mysql/mysql.conf.d/mysqld.cnf
#Add  the line below
sql-mode=STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION
#exit editor, restart mysql
service mysql restart


***************************************************
Install Node for automated frontend builds
***************************************************
ssh root@139.162.210.187
curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash - &&sudo apt-get install -y nodejs
exit
ssh root@139.162.210.187
node --version
#should say 16.19.1


***************************************************
Create Laravel/Deployer folder structure
***************************************************
cd ~/apps/<serverpilot-application-name>
rm -R public
mkdir -p site/releases
mkdir -p site/shared/logs
mkdir -p site/shared/web/app/uploads
cd site/shared
touch .env auth.json web/.htaccess
cd ../../
ln -s site/current/public/ public


***************************************************
Deployer.org configuration
***************************************************
Details for the remote server(s) are changed in deploy_hosts_config.yml


***************************************************
Initial Deployment
***************************************************
# This will install the initial required dependencies and create the first release folder
dep deploy_first


***************************************************
To deploy changes to remote server using Deployer (deployer.org )
***************************************************
Push changes to remote git repository. Changes to be pushed to Dev servers should be committed to the develop branch. Changes to Live servers should be pushed to Master
Run dep deploy, then choose the remote server you wish to deploy to


***************************************************
To install necessary DB data into the application run
***************************************************

# Create database structure, install Roles, then install remaining seed data
php artisan migrate:fresh --seed --seeder=RolesSeeder
php artisan migrate:fresh --seed --seeder=EntitiesSeeder
#A number of other seeders are available


***************************************************
To start a local dev server run from Terminal/Console
***************************************************
npm run dev


***************************************************
To compile frontend assets for production. This is typically run by Deployer automatically
***************************************************
npm run build


***************************************************
Code style
***************************************************
Ideally run ./vendor/bin/pint before committing any changes to the project repository to maintain a consistent code style. Please see https://laravel.com/docs/9.x/pint for more


***************************************************
Changing application configuration
***************************************************
The bulk of configuration changes can be made via SFTP in ~/apps/<app-name>>/site/shared/.env

Once a configuration change has been made you will need to clear the configuration cache. This can be done via the Laraval Artisan CLI tool or by hitting a specific endpoint

php artisan clear config
or
https://<site url>/deployment/clearconfig?key=<JW145_DEPLOYMENT_SECURITY_KEY>

If hitting the url you will need to look for the JW145_DEPLOYMENT_SECURITY_KEY defined in ~/apps/<app-name>>/site/shared/.env

***************************************************
Manually making changes directly on the server
***************************************************
It is much preferred that all changes should first be committed into the git repository and then pushed to the server using Deployer.
Editing files directly on the server enables people to not record those changes in git - before you know it you've broken something and you don't know why...

You can however make changes directly on the server via SFTP and then get those changes active on the server by hitting specific "deployment" urls.
Please be aware that pretty much any change on the frontend will need to be compiled before it shows up on the site, so even making small changes to css or vue templates will need recompilation.

1). Open the <webroot>/shared/.env file and set the JW145_DEPLOYMENT_SECURITY_KEY to something long and secure. This will be used as a querystring parameter to secure the deployment endpoints. You should only need to set this once.

2). Open the <webroot>/releases/<HIGHEST NUMBER> folder. When Deployer runs it creates a unix timestamped folder in <webroot>/releases/<some long number>. The folder with the highest number will almost always be the current release folder. This is where you should make changes via SFTP.

3). Make changes to the site. the bulk of these will either be in
a). <webroot>/shared/.env
b). <webroot>/resources/css
c). <webroot>/resources/js

4). Visit the deployment urls to allow Laravel to recompile and/or clear caches.
a). Frontend changes can be deployed by visiting <site url>/deployment/clearfrontend?key=<JW145_DEPLOYMENT_SECURITY_KEY> e.g.
For example https://mysite.com/deployment/clearfrontend?key=5tvdg5gdbr6kmldskljsdrlje4g
b). Config changes or changes to .blade.php templates can be deploy by visiting <site url>/deployment/clearbackend?key=<JW145_DEPLOYMENT_SECURITY_KEY> e.g.
For example https://mysite.com/deployment/clearbackend?key=5tvdg5gdbr6kmldskljsdrlje4g

4). Once you have confirmed that the change works as expected COMMIT THOSE CHANGES INTO GIT.
