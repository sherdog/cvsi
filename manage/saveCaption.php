<?php
//used in conjuction with the gallery area
//display form to add caption and set sort order number.

include('application.php');


if($_POST){
	$row['gallery_image_caption'] = ($_POST['desc'] != '') ? $_POST['desc'] : '';
	$row['gallery_image_sort_order'] = ($_POST['sort_order'] != '') ? $_POST['sort_order'] : 0;
	$row['gallery_image_featured'] = (isset($_POST['featured'])) ? 1 : 0; 

	dbPerform('gallery_images', $row, 'update', 'gallery_image_id  = ' . $_POST['image_id']);
} 

?>