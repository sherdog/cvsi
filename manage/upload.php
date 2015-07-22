<?php

/* This script is used with swfupload */
/* We are going to be upload and storing the gallery images /*
/* Create thumbnails and return a true statement! */

include('application.php');

if($_FILES['Filedata']['name'] != "" && isset($_POST['gallery_id'])){

	
		$filename = time().fixFilename($_FILES['Filedata']['name']);

		uploadFile($_FILES['Filedata'], $filename);

		foreach($galleryImageSizes as $name => $size)
		{
			makeThumbnail($filename, UPLOAD_DIR, $size, '', $name);
		}

		$row['gallery_id'] = $_POST['gallery_id'];
		$row['gallery_image_filename'] = $filename;
		$row['gallery_date_added'] = time();
		$row['gallery_image_caption'] = '';

		dbPerform('gallery_images', $row, 'insert');

		$imageID = dbInsertID();

		//get total images then add one for the sort order number!
		$imgResults = dbQuery('SELECT gallery_image_id FROM gallery_images WHERE gallery_id = ' . $_POST['gallery_id']);
		$count = dbNumRows($imgResults);
		$next = $count++;
		$row2['gallery_image_sort_order'] = $next;
		dbPerform('gallery_images', $row2, 'update', 'gallery_image_id = ' . $imageID);


		echo "FILEID:".UPLOAD_DIR_URL.getThumbnailFilename($filename, 'thumb');

													 

} else {
	return false;
	die();
}

?>