<?php

define('LOCAL', false);

define('COMPANY_NAME', "CVSi MotorSports");
define('SITE_TITLE', COMPANY_NAME);
define('SITE_URL','http://localhost/cvsi/');
define('SITE_PATH', '/home/clearvie/public_html/2015/');
define('MANAGE_PATH', dirname(__FILE__) .'/');

define('ROOT_PATH', SITE_PATH);
define('ROOT_URL', 'http://cvsimotorsports.com/2015/');

define('UPLOAD_DIR_URL', ROOT_URL."files/");
define('UPLOAD_DIR', ROOT_PATH.'files/');


//Store Paths
define('STORE_IMAGE_URL', UPLOAD_DIR_URL . 'store/');
define('STORE_IMAGE_PATH', UPLOAD_DIR . 'store/');

define("UPLOAD_DIR_STORE", UPLOAD_DIR . 'store/');
define('UPLOAD_DIR_NEWSLETTER', ROOT_PATH.'email/');
define('UPLOAD_DIR_NEWSLETTER_URL', ROOT_URL.'email/');
define('NEWSLETTER_UNSUBSCRIBE_URL', ROOT_URL."newsletter/unsubscribe");

define('UPLOAD_DIR_BANNER', ROOT_PATH.'files/banners/');
define('UPLOAD_BANNER_URL', ROOT_URL.'files/banners/');

define('MEDIA_LIBRARY_PATH', ROOT_PATH.'files');

if(LOCAL)
{
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'michael5');
	define('DB_DATABASE', 'cvsi');
}
else
{
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'clearvie_dbadmin');
	define('DB_PASSWORD', 'db@dm1n');
	define('DB_DATABASE', 'clearvie_site');
}

ini_set("session.gc_maxlifetime","3600");

date_default_timezone_set('America/Chicago');

session_start();