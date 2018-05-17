# OFOS

## Slim: 3.* & Angular Base Script

## Requirements:

* Nginix 
* PHP >= 5.5.9
* Enable extensions in **php.ini** file(OpenSSL PHP Extension, PDO PHP Extension, Mbstring PHP Extension, curl)
* Nodejs
* Composer
* Bower
* Grunt


## Server Side:
### Composer Updation:

* To Update the Composer, please run the below command in following path `/ofos/server/php/Slim`.  

        composer update
    
* The above Updation doesn't work to you, need to install Composer, please refer this link **https://getcomposer.org/**  for "**How to install Composer**".

## Import db: (use Import OR Migration)

1. ofos/sql/ofos_empty_data.sql

## Migration 

* Migrate:

 **php vendor/bin/phinx migrate -c config-phinx.php**

* Rollback:

 **php vendor/bin/phinx rollback -c config-phinx.php**

## Seeds

* Run (It will run all)

  **php vendor/bin/phinx seed:run**

* It will run particular or specified

 **php vendor/bin/phinx seed:run -s UserSeeder**

  Also we can run multi

 **php vendor/bin/phinx seed:run -s UserSeeder -s UserSeeder1 -s UserSeeder2**

## Front Side:

* You need to install nodejs, bower, grunt.

* Go to "/ofos/client" path in command prompt.

* Run the below command, the bower used to download and installed all front-end development libraries.

        bower install

* The npm used to install the all dependencies in the local node_modules folder. [Click here](http://git8.ahsan.in/root/LaravelBase/blob/master/trunk/lumen/docs/Npm.md) for more npm details.

        npm install    


## default folder create and give permission 

        /tmp
        /media

# upload to server

* need to create json, "**/ofos/client/builds/XXX.json**" [already dev.json available, take one copy and update server & db details]
* If modify the files in local, you should run the below command for further updation.  
  
  cd "/ofos/client/"

        grunt build:xxx      

