<?php
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php"); 
require_once('langs/'.LANG.'.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'
	'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?echo LANG?>" lang="<?echo LANG?>"> 
<head>
<title><?echo TXT_UPLOADING?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<style>
BODY{
background:#BB0000;
color: #fff;
	margin: 0px;
	}
</style>

<body>

<div id='uploading' style='margin:0px;padding:4px'><?basic_upload_hack_block();?></div>
<script type='text/javascript'>
contentContainer=document.getElementById('uploading');
contentContainer.innerHTML = '<?php echo TXT_UPLOADING?>';
</script>

<?php
if($_FILES["filename"]["name"])
	{
		
		//Upload the image to the images directory
		
		$newFileName = $_FILES["filename"]["name"];
		if ($_POST['type']==image){
		$newFileType = $_FILES["filename"]["type"];
		}else{
		preg_match("|\.([a-z0-9]{2,4})$|i", $newFileName,$fileSuffix);
		$newFileType=$fileSuffix[1];
		}
		$newFileName = strtolower($newFileName);
		$newFileName = str_replace(" ", "_",$newFileName);
		$newFileLocation = $_FILES["filename"]["tmp_name"];
		
		$validFileType = false;
		$ExistFile = false;
		$validFileChar = true;
		$canupload =false;
		
		if(is_file($_POST['upload_path'] . $newFileName)){
		$ExistFile = true;
		}
			// Is the size valid
	if($HTTP_POST_FILES["filename"]["size"]<=$ezfilemanager['maxsize'][$_POST['type']]){
	$validFileZise = true;
	$canupload =true;
	}
	// Is the mime type valid
		if(in_array($newFileType, $ezfilemanager['filetype'][$_POST['type']])){
			$validFileType = true;
			$canupload =true;
			}
	// Is the name character valid		
	if (eregi(VALID_FILE_CHAR, $newFileName)){
	$validFileChar = false;
		$canupload =false;
	}
	// Is the name character valid	
	if($ExistFile == true)
		{
			// Invalid file type
			$statusText = TXT_FILE_EXIST;
				$canupload =false;
			}
		elseif($validFileType == false)
		{
			// Invalid file type
			$canupload =false;
			$statusText = TXT_FILE_INVALID;
			
		}
		elseif($validFileChar == false){
		$canupload =false;
		$statusText = TXT_FILE_CHRACTERS;
		}
		elseif($validFileZise == false){
$canupload =false;
$statusText = TXT_FILE_BIG;
		}
		else
		{
		if ($canupload==1){
$uploadSuccess = copy($newFileLocation, $_POST['upload_path']  . $newFileName);
$statusText = $newFileName . " ".TXT_FILE_UPLOADED;
chmod($_POST['upload_path']  . $newFileName, 0644);//remove ,0644 if creates problem   
}

}
//OTHER ERRORS
if ($_FILES['filename']['error']==2) $ExtraError="<br>".TXT_FILE_BIG;
	if ($_FILES['filename']['error']==4) $ExtraError="<br>".TXT_FILE_NO;
	if ($_FILES['filename']['error']==3) $ExtraError="<br>".TXT_FILE_PARTIAL;
	$message =$statusText.$ExtraError;
///
}else{
$message =TXT_FILE_NO;
}
echo "<script type='text/javascript'>contentContainer.innerHTML = '".$message."'</script>";
if ($canupload==1){
echo "<script type='text/javascript'>setTimeout(\"parent.do_refresh()\", 1000);</script>";
}else{
echo "<script type='text/javascript'>setTimeout(\"parent.do_clear_msg()\", 2000);</script>";
}
?>
</body>
</html>