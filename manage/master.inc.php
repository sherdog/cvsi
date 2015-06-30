<?php
define('COMPANY_NAME', "CVSi MotorSports");
define('SITE_TITLE', COMPANY_NAME);
define('SITE_URL','http://localhost/cvsi/');
define('SITE_PATH', ' /Library/WebServer/Documents/cvsi/');
define('MANAGE_PATH', '/Library/WebServer/Documents/cvsi/manage/');

define('ROOT_PATH', SITE_PATH);
define('ROOT_URL', 'http://localhost/cvsi/');

define('UPLOAD_DIR', ROOT_PATH.'files/');
define('UPLOAD_DIR_NEWSLETTER', ROOT_PATH.'email/');
define('UPLOAD_DIR_NEWSLETTER_URL', ROOT_URL.'email/');
define('NEWSLETTER_UNSUBSCRIBE_URL', ROOT_URL."newsletter/unsubscribe");

define('UPLOAD_DIR_URL', ROOT_URL."files/");
define('UPLOAD_DIR_BANNER', ROOT_PATH.'files/banners/');
define('UPLOAD_BANNER_URL', ROOT_URL.'files/banners/');

define('MEDIA_LIBRARY_PATH', ROOT_PATH.'files');

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'michael5');
define('DB_DATABASE', 'cvsi');

ini_set("session.gc_maxlifetime","3600");

session_start();