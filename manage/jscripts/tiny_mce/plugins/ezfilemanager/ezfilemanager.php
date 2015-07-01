<?php
require_once("includes/config.inc.php");
require_once("includes/functions.inc.php"); 
require_once('langs/'.LANG.'.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'
	'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?echo LANG?>" lang="<?echo LANG?>"> 
<head>
<title><?echo TXT_BROWSER?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="js/ezfilemanager.js" type="text/javascript"></script>
<script src="js/mctabs.js" type="text/javascript"></script>
<?php
//make sure the tiny_mce_popup.js path is correct
if ($_GET['sa']!=1){//Not necessary but will stop javascript errors?>
<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
<?}?>
<link href="css/ezbrowser.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php if ($_GET['type']){
basic_hack_block();
do_delete();
get_files();
?>
<div class="tabs">
			<ul>
				<li id="dir_tab" class="current"><span><a href="javascript:mcTabs.displayTab('dir_tab','dir_panel');" onmousedown="return false;"><?echo TXT_BROWSE?></a></span></li>
				<li id="upload_tab"><span><a href="javascript:mcTabs.displayTab('upload_tab','upload_panel');" onmousedown="return false;"><?echo TXT_UPLOAD?></a></span></li>
				<li id="makedir_tab"><span><a href="javascript:mcTabs.displayTab('makedir_tab','makedir_panel');" onmousedown="return false;"><?echo TXT_NEW_DIR?></a></span></li>
			</ul>
		</div>
<div class="panel_wrapper">
			<div id="dir_panel"  class="panel current">

<?php require_once("includes/directory_list.inc.php"); ?>
</div>

			<div id="upload_panel" class="panel">
		<?php require_once("includes/upload.inc.php"); ?>
			</div>
		

			<div id="makedir_panel" class="panel">
		<?php require_once("includes/makedir.inc.php"); ?>
			</div>
			</div>



<script type='text/javascript'>
function do_refresh(){
window.location="http://<?echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>";
}
</script>
<?php
}else{//When no type is defined
require_once("includes/selectdir.inc.php"); 
}?>
</body>
</html>