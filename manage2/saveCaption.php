<?php
//used in conjuction with the gallery area
//display form to add caption and set sort order number.

include('master.inc.php');
include('application.php');


if($_POST){
	$row['gallery_image_caption'] = ($_POST['desc'] != '') ? $_POST['desc'] : '';
	$row['gallery_image_sort_order'] = ($_POST['sort_order'] != '') ? $_POST['sort_order'] : 0;
	
	dbPerform('gallery_images', $row, 'update', 'gallery_image_id  = ' . $_GET['image_id']);
} 

?>