<?php
//Image Functions!

function uploadFile($formfile,$filename="") {

	if ($filename=="")
		$filename = $formfile['name'];
			
	move_uploaded_file($formfile['tmp_name'], UPLOAD_DIR.$filename);

}

function uploadStoreImage($name, $tmpname, $filename="")
{
	if ($filename=="")
		$filename = $name;
	//exit('Move uploaded file: ' . $name . ' : ' . $tmpname . ' : ' . $filename . ' to: ' . UPLOAD_DIR_STORE);

	move_uploaded_file($tmpname, UPLOAD_DIR_STORE.$filename);
}

function uploadBanner($formfile,$filename="") {

	if ($filename=="")
		$filename = $formfile['name'];
			
	move_uploaded_file($formfile['tmp_name'], UPLOAD_DIR_BANNER.$filename);



}

function uploadDownloadable($formfile,$filename="") {

	if ($filename=="")
		$filename = $formfile['name'];
			
	move_uploaded_file($formfile['tmp_name'], UPLOAD_DIR_DOWNLOADABLE.$filename);

}



function fixFilename($name) {
	$file = explode('.', $name);
	$filename =preg_replace("/[^A-Za-z0-9\s\s+]/","",$file[0]);
	$filename = str_replace(" ", "_",$file[0]);
	$filename = time().'_'.$filename.'.'.$file[1];
	
	return $filename;
}
		
function getFiletype($name) {
	return substr($name, strrpos($name,".")+1);
}

function makeThumbnail($filename, $location, $width="", $height="", $postfix="") {

  $filetype = substr($filename, strrpos($filename, ".")+1); 
  
  switch($filetype) {
	  case "jpg":
	  case "jpeg":
		  $image = imagecreatefromjpeg($location.$filename);
		  break;
	  case "gif":
		  $image = imagecreatefromgif($location.$filename);
		  break;
	  case "png":
		  $image = imagecreatefrompng($location.$filename);
		  break;
	  default:
		  return FALSE;
  }
  
  list($imwidth, $imheight) = getimagesize($location.$filename);
  
  if ($width=="" && $height=="") {
	  $newwidth=$imwidth;
	  $newheight=$imheight;
  }
  else if ($width!="" && $height=="") {
	  $newwidth=$width;
	  $newheight=$imheight*($width/$imwidth);
  }
  else if ($width=="" && $height!="") {
	  $newwidth=$imwidth*($height/$imheight);
	  $newheight=$height;
  }
  else {
	  $newwidth=$width;
	  $newheight=$height;
  }
  
  $thumbimage = imagecreatetruecolor($newwidth, $newheight);
  imagecopyresampled($thumbimage, $image, 0, 0, 0, 0, $newwidth, $newheight, $imwidth, $imheight);
  
  imagejpeg($thumbimage, $location.getThumbnailFilename($filename, $postfix));
  
  return TRUE;
}

function thumb($filename, $postfix="") {

	return get_thumbnail_filename($filename, $postfix);

}

function getThumbnailFilename($filename, $postfix="") {

	return substr($filename, 0, strrpos($filename, ".")).".thumb$postfix.jpg";

}

?>