<?php
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php"); 
require_once('langs/'.LANG.'.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'
	'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?echo LANG?>" lang="<?echo LANG?>"> 
<head>
<title><?echo TXT_NEW_DIR?></title>
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

<div id='makingdir' style='margin:0px;padding:4px'>
<?php basic_upload_hack_block();?>
</div>
<script type="text/javascript">
contentContainer=document.getElementById('makingdir');
contentContainer.innerHTML = '<?echo TXT_CREATING?>';
</script>

<?php
$new_directory=trim($_POST['newdir']);
$new_directory = strtolower($new_directory);
$new_directory = str_replace(" ", "_",$new_directory);
$validFileType = false;
		$ExistDir = false;
		$validFileChar = true;
		$makedir =true;
if (!empty($new_directory)){
//Check if Dir exists
if(is_file($_POST['upload_path'] . $new_directory)){
		$ExistDir = true;
		}
		
if (eregi(VALID_DIR_CHAR, $new_directory)){
	$validFileChar = false;
		$makedir =false;
	}

if($ExistFile == true)
		{
			// Dir exist
			$statusText = TXT_DIR_EXIST;
			$makedir =false;
		}
		elseif($validFileChar == false)
		{
		$makedir =false;// Invalid chars
		$statusText = TXT_FILE_CHRACTERS;
		}

}else{
$makedir =false;
$statusText =TXT_DIR_NO;
}	
if ($makedir==1){
$statusText = $newFileName . " ".TXT_DIR_CREATED;
mkdir($_POST['upload_path'] . $new_directory, 0755);//remove ,0755 if creates problem
echo "<script type='text/javascript'>contentContainer.innerHTML = '".$statusText."'</script>";
echo "<script type='text/javascript'>setTimeout(\"parent.do_refresh()\", 1000);</script>";
}else{
echo "<script type='text/javascript'>contentContainer.innerHTML = '".$statusText."'</script>";
echo "<script type='text/javascript'>setTimeout(\"parent.do_clear_dir_msg()\", 2000);</script>";
}	
?>
</body>
</html>