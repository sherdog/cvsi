<?php

define('COMPANY_NAME', "CVSi MotorSports");

if(LOCAL)
{
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'michael5');
	define('DB_DATABASE', 'cvsi');

	define('SITE_URL','http://localhost/cvsi2/');
	define('SITE_PATH', 'H:\\htdocs\\cvsi2\\');
}
else
{
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'clearvie_dbadmin');
	define('DB_PASSWORD', 'db@dm1n');
	define('DB_DATABASE', 'clearvie_site');

	define('SITE_URL','http://cvsimotorsports.com/');
	define('SITE_PATH', '/home/clearvie/public_html/');
}

define('MANAGE_PATH', dirname(__FILE__) .'/');

define('UPLOAD_DIR_URL', SITE_URL."files/");
define('UPLOAD_DIR', SITE_PATH.'files/');


//Store Paths
define('STORE_IMAGE_URL', UPLOAD_DIR_URL . 'store/');
define('STORE_IMAGE_PATH', UPLOAD_DIR . 'store/');

define("UPLOAD_DIR_STORE", UPLOAD_DIR . 'store/');
define('UPLOAD_DIR_NEWSLETTER', SITE_PATH.'email/');
define('UPLOAD_DIR_NEWSLETTER_URL', SITE_URL.'email/');
define('NEWSLETTER_UNSUBSCRIBE_URL', SITE_URL."newsletter/unsubscribe");

define('UPLOAD_DIR_BANNER', SITE_PATH.'files/banners/');
define('UPLOAD_BANNER_URL', SITE_URL.'files/banners/');

define('MEDIA_LIBRARY_PATH', SITE_PATH.'files');

ini_set("session.gc_maxlifetime","3600");

date_default_timezone_set('America/Chicago');

session_start();