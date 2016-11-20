# Usage

## First Run
1. Clone this repository
    ```
    git clone https://github.com/ehyland/docker-silverstripe-dev.git
    ```
2. Change into the project directory
    ```
    cd ./docker-silverstripe-dev
    ```
3. Create a directory to house the silverstripe project and mount in the docker container
    ```
    mkdir -p ./volumes/site
    ```
4. Start docker containers (and run as a daemon)  
    - This will be slow on first run while dependancies are downloaded & installed, subsequent runs will be much faster  
    - If containers fail to launch, ensure you **don't** have web or mysql servers attached to ports 80 or 3306
    ```
    docker-compose up -d
    ```
5. Create a new silverstripe project  
    ```
    docker-compose run web composer create-project silverstripe/installer .
    ```
6. Navigate to [http://localhost](http://localhost) and you will see your new silverstripe project project  
    - Site files can be found (end edited) in `./volumes/site` 

## Connect to MYSQL Database
1. Lanuch [Sequel Pro](https://www.sequelpro.com/) or your favorite database management application
2. Use the following settings to connect:  
    - Host: 127.0.0.1
    - Username: root
    - Password: password
    - Database: silverstripe
    - Port: 3306

## Run commands within the docker dontainer
1. Attach terminal to docker container  
    *think of this as ssh-ing into your container*
    ```
    docker-compose exec web bash
    ```
2. Run any command you like, such as
    - `composer update`
    - `composer require <sick-hot-dependancy>`
    - `bash ./framework/sake /dev/build "flush=all"`
    - `bash ./framework/sake /dev/tests/all`
    - `npm install` - if you have a package.json

## Debug - Set breakpoints
1. For breakpoints to work, xdebug needs the IP address that will resolve to the host running your IDE.  
    This can be achived using on of the following methods:
    1. on osx, add `export DOCKERHOST="$(ipconfig getifaddr en0)"` to ~/.bash_profile
    2. or, replace `${DOCKERHOST}` with your local network ip address. e.g. 192.168.x.x
2. Restart your terminal window and run the following from this projects root directory
    ```
    docker-composer up --build
    ``` 
3. Open vscode at ./volumes/site
    ```
    code ./volumes/site/
    ```
4. Install the [PHP Debug extension](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-debug)
5. Click on the debug tab -> settings cog and select PHP
6. Update `.vscode/launch.json` to match:
    ```json
    {
        "version": "0.2.0",
        "configurations": [
            {
                "name": "Listen for XDebug",
                "type": "php",
                "request": "launch",
                "port": 9000,
                "stopOnEntry": false,
                "localSourceRoot": "${workspaceRoot}",
                "serverSourceRoot": "/var/www/site",
                "log": false
            },
            {
                "name": "Launch currently open script",
                "type": "php",
                "request": "launch",
                "program": "${file}",
                "cwd": "${fileDirname}",
                "port": 9000
            }
        ]
    }
    ```
7. Click the play icon in the debug tab, to start the debugger.
8. Set a breakpoint in code, trigger an action that will trip the breakpoint.
9. Feel good.

## Bring your own _ss_environment.php
1. Create a new config file in `./volumes/site/_ss_environment.php`
2. Copy mysql settings from the [default _ss_environment.php file](dockerfiles/php5-apache2/_ss_environment.php)

## Install extra server dependancies  
1. Make changes to [Dockerfile](dockerfiles/php5-apache2/) that builds the container silverstripe runs in.
2. Update the running containers
    ```
    docker-compose up --build
    ```
