<?php
	/**
	 * Eazy file manager platform for TinyMCE
	 * ezfilemanager v.0.5
	 * @author Nazaret Armenagian (Naz)
	 * @link www.webnaz.net
	 * @since August-2008
	 * Usual and unnecessary GNU General Public License should follow
	 */

	/**
	 * Uncomment to set script time out if you wish
	 * This might not work if it's more than the default PHP max_execution_time, 
	 * you might be able to use php.ini or htaccess file
	 * but check with your hosting provider for info
	 * use echo ini_get('max_execution_time') to see your default exec. time
	 * but some hosts disable ini_get
	 * read more http://www.php.net/manual/en/ini.php
	 */
//@set_time_limit(20*60); // 20 minutes execution time 
	/**
	 * Define lang (translate langs/en.inc.php for other langs)
	 * Default en
	 * Define valid characters for Files and Directories
	 * Use PHP regex for Valid Chars
	 * Define Paths and URL
	 * Important Paths and URL must end with trailing slash /
	 */
define('LANG','en');
define('VALID_DIR_CHAR','[^0-9a-zA-Z_-]'); //allow only alphanumeric(upper/lower) and _- 
define('VALID_FILE_CHAR','[^0-9a-zA-Z_.-]');//allow only alphanumeric(upper/lower) and ._- 
define('DATE_FORMAT','d/m/Y H:i');
define('FILE_CHAR_TO_SHOW','25');//filename will be truncated to 25chars if it's longer
define("WN_URL","http://chillsthrills.com/new/");//your domain url
define('WN_PATH','/home/chillsth/public_html/new/');//domain root absolute path 
define("IMG_PATH",WN_PATH."UserFiles/");//Image dir url 
define('IMG_URL',WN_URL.'UserFiles/');//Image dir absolute path 
define('FILE_PATH',WN_PATH.'UserFiles/');//File dir url 
define('FILE_URL',WN_URL.'UserFiles/');//File dir absolute path 
define('MEDIA_PATH',WN_PATH.'UserFiles/');//Media dir url 
define('MEDIA_URL',WN_URL.'UserFiles/');//Media dir absolute path 
	/**
	 * ezfilemanager array
	 * Add Max File upload size  to ezfilemanager array
	 * Set Max File upload size limit in B
	 * This will not work if your settings are more than the default PHP settings, 
	 * or if the script times out, see set_time_limit above
	 * you might be able to use php.ini or htaccess file
	 * but again check with your hosting provider for info
	 * use echo ini_get('upload_max_filesize') to see your default upload size limit
	 * but some hosts disable ini_get
	 * Probably its 2M, multiply by 1012000 to convert to B
	 * 1024B=1KB 1024000B=1000KB=1M 
	 */
$ezfilemanager = array();
$ezfilemanager['maxsize']['image'] = 2048000; // Image file maximum size 61440=60KB
$ezfilemanager['maxsize']['media'] = 2048000; // Media file maximum size 2048000=2M
$ezfilemanager['maxsize']['file']  = 2048000; // Other file maximum size 1228800=1.2M
	/** 
	 * Add File upload paths/url to ezfilemanager array
	 * no need to modify 
	 */
$ezfilemanager['path']['image'] = IMG_PATH;
$ezfilemanager['url']['image'] = IMG_URL; 
$ezfilemanager['path']['media'] = MEDIA_PATH; 
$ezfilemanager['url']['media'] = MEDIA_URL; 
$ezfilemanager['path']['file']  = FILE_PATH;
$ezfilemanager['url']['file']  = FILE_URL;
	/** 
	 * Add Permitted file extensions  to ezfilemanager array
	 * for image, media and docs/other types
	 */
$ezfilemanager['filetype']['image'] = array('image/pjpeg', 'image/jpeg', 'image/gif', 'image/x-png', 'image/png');
$ezfilemanager['filetype']['media'] =  array('swf','mp3','mov','avi','mpg','qt','mp4');
$ezfilemanager['filetype']['file']  = array('html','pdf','ppt','txt','doc','xml','zip','rar'); 
	/**
	 * set the working path and absolute URL parameters
	 * Do not modify
	 */
define('CHANGE_URL',$ezfilemanager['url'][$_GET['type']]);
define('CHANGE_PATH',$ezfilemanager['path'][$_GET['type']]);
define('WORKING_PATH',$ezfilemanager['path'][$_GET['type']].$_GET['thedir']);
?>