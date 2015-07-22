<?php
define('this_php', basename($_SERVER['PHP_SELF']));
$alpha = array('a','b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'all');

$local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== FALSE) ? true : false;

define('LOCAL', $local);


include('master.inc.php');

error_reporting('E_NONE');

//setup application constants
	include(MANAGE_PATH . 'misc/filenames.php');
	include(MANAGE_PATH . 'misc/breadcrumb.php');
	include(MANAGE_PATH . 'functions/database.inc.php');
	include(MANAGE_PATH . 'functions/functions.general.php');
	include(MANAGE_PATH . 'functions/functions.user.php');
	include(MANAGE_PATH . 'functions/functions.email.php');
	include(MANAGE_PATH . 'functions/functions.image.php');
	include(MANAGE_PATH . 'functions/functions.store.php');
	include(MANAGE_PATH . 'functions/functions.gallery.php');
	include(MANAGE_PATH . 'functions/functions.tools.php');
	include(MANAGE_PATH . 'functions/functions.admin.php');
	include(MANAGE_PATH . 'classes/class.phpmailer.php');	

	//Gallery Image Sizes (thumbnails when uploaded.)

	/*
	makeThumbnail($filename, UPLOAD_DIR, 520, '', 'large');
	makeThumbnail($filename, UPLOAD_DIR, 480, '', 'medium');
	makeThumbnail($filename, UPLOAD_DIR, 240, '', 'small');
	makeThumbnail($filename, UPLOAD_DIR, 100, '', 'thumb');
	*/

	$galleryImageSizes = array(
		'thumb' => 100,
		'small' => 240,
		'medium' => 480,
		'large' => 800
	);

	
?>