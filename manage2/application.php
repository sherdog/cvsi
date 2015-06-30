<?php
define('this_php', basename($_SERVER['PHP_SELF']));
$alpha = array('a','b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'all');

//setup application constants
	
	include(SITE_PATH . 'misc/filenames.php');
	include(SITE_PATH . 'misc/breadcrumb.php');
	include(SITE_PATH . 'functions/database.inc.php');
	include(SITE_PATH . 'functions/functions.general.php');
	include(SITE_PATH . 'functions/functions.user.php');
	include(SITE_PATH . 'functions/functions.email.php');
	include(SITE_PATH . 'functions/functions.image.php');
	include(SITE_PATH . 'functions/functions.store.php');
	include(SITE_PATH . 'functions/functions.gallery.php');
	include(SITE_PATH . 'functions/functions.tools.php');
	include(SITE_PATH . 'functions/functions.admin.php');
	include(SITE_PATH . 'classes/class.phpmailer.php');	

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