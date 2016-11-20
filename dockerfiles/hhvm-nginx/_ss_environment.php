<?php
/* What kind of environment is this: development, test, or live (ie, production)? */
define('SS_ENVIRONMENT_TYPE', 'dev');

/* Database connection */
define('SS_DATABASE_SERVER', 'db');
define('SS_DATABASE_NAME', getenv('MYSQL_DATABASE'));
define('SS_DATABASE_USERNAME', 'root');
define('SS_DATABASE_PASSWORD', getenv('MYSQL_ROOT_PASSWORD'));

/* Configure a default username and password to access the CMS on all sites in this environment. */
define('SS_DEFAULT_ADMIN_USERNAME', 'admin');
define('SS_DEFAULT_ADMIN_PASSWORD', 'password');

// define('SS_SEND_ALL_EMAILS_TO', 'example+ss_send_all_emails_to@gmail.com');
// define('SS_SEND_ALL_EMAILS_FROM', 'example+ss_send_all_emails_from@gmail.com');

define('SS_ERROR_LOG', 'silverstripe.log');

// This is used by sake to know which directory points to which URL
global $_FILE_TO_URL_MAPPING;
$_FILE_TO_URL_MAPPING['/var/www/site'] = 'http://localhost';
