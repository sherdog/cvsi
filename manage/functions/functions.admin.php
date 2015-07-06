<?php

function get_modules() {

	$modules[] = array('name'=>'Webapges', 'value'=>'pages');
	$modules[] = array('name'=>'News', 'value'=>'news');
	$modules[] = array('name'=>'Events', 'value'=>'events');
	$modules[] = array('name'=>'Articles', 'value'=>'articles');
	$modules[] = array('name'=>'Store Front', 'value'=>'store');
	
	$modules[] = array('name'=>'Communication', 'value'=>'communication');
	$modules[] = array('name'=>'Banners', 'value'=>'banners');
	$modules[] = array('name'=>'Photo Gallery', 'value'=>'users');
	$modules[] = array('name'=>'Settings', 'value'=>'settings');
	
	
	return $modules;
	
}

?>