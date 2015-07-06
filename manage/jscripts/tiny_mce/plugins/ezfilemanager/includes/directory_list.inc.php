<div style='text-align:right;background:#F0F0F0''>&nbsp;<a href="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];?>"><img src="img/refresh.gif" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo $_SERVER['PHP_SELF'].'?type='.$_GET['type'].'&amp;sa='.$_GET['sa'];?>"><img src="img/folder_root.gif" width="18" height="16" alt="" border="0" /></a>&nbsp;
<?php if ($pos = strrpos(substr($_GET['thedir'], 0, -1), "/")){?>
<a href="<?php echo $_SERVER['PHP_SELF'].'?type='.$_GET['type'].'&amp;thedir='.substr($_GET['thedir'], 0, $pos).'/&amp;sa='.$_GET['sa'];?>"><img src="img/folder_up.gif" width="18" height="16" alt="" /></a>
<?php
}
?>
</div>
<fieldset>
<legend>Browsing: <?php echo $xdir =substr($_GET['thedir'], 0, -1);?></legend>
<div class='dirlist'>
<form action='?<?php echo $_SERVER['QUERY_STRING']?>' method='post' id='dodelete' name='dodelete' onsubmit='return confirm_del(this)'>
<table cellspacing="0" cellpadding="0" align="center" class="browse"><tr><th><?php if ($pos = strrpos(substr($_GET['thedir'], 0, -1), "/")){?>
<a href="<?php echo $_SERVER['PHP_SELF'].'?type='.$_GET['type'].'&amp;thedir='.substr($_GET['thedir'], 0, $pos).'/&amp;sa='.$_GET['sa'];?>"><img src="img/folder_up.gif" width="18" height="16" alt="" /></a>
<?php }else{?>
<a href="<?php echo $_SERVER['PHP_SELF'].'?type='.$_GET['type'].'&amp;sa='.$_GET['sa'];?>"><img src="img/folder_up.gif" width="18" height="16" alt="" /></a>

<?php }?></th><th><?php echo TXT_NAME?></th><th><?php echo TXT_SIZE?></th><th><?php echo TXT_TYPE?></th><th><?php echo TXT_DATE?></th><th><input type="image" name="submit" src="img/delete.gif" style='border:0px' /></th></tr>
<?php
//First show the sub-directories
for($ii=0;$ii<count($dir_name);$ii++)
		{
		$row_color = (@$row_color == "r1") ? "r2" : "r1";	
		echo '<tr class="'.$row_color.'">
			  <td class="icon"><a href="'.$_SERVER['PHP_SELF'].'?type='.$_GET['type'].'&amp;thedir='.$dir_name_path[$ii].'/&amp;sa='.$_GET['sa'].'"><img src="img/folder.gif" width="16" height="16" alt="'.TXT_BROWSE.'" /></a></td>
			<td colspan="4"><a href="'.$_SERVER['PHP_SELF'].'?type='.$_GET['type'].'&amp;thedir='.$dir_name_path[$ii].'/&amp;sa='.$_GET['sa'].'" title="'.TXT_BROWSE.'">'.$dir_name[$ii].'</a></td>
			<td><input class="delcheckbox" type="checkbox" name="dir_to_del['.$dir_name_path[$ii].']" value="1" /></td></tr>';
		}
//Now show the directory files		
for($i=0;$i<count($file['name']);$i++)
		{
		$row_color = (@$row_color == "r1") ? "r2" : "r1";
		//IF stand alone or Plug-in, dont create the install links	
		if ($_GET['sa']==1){
		$make_link = '<span title="'.$file['name'][$i].' '.$file['width'][$i].' '.$file['height'][$i].'">' .truncate_filename($file['name'][$i]).'</span>';
		}else{
		$make_link = '<a href="#" onclick="selectURL(\''.CHANGE_URL.$_GET['thedir'].$file['name'][$i].'\');" title="'.TXT_SELECT_FILE.' '.$file['name'][$i].' '.$file['width'][$i].' '.$file['height'][$i].'">' .truncate_filename($file['name'][$i]).'</a>';
		}		
	echo'<tr class="'.$row_color.'">
		<td><img src="img/icons/'.return_mime_type_icon($file['name'][$i]).'" width="16" height="16" alt="'.$file['name'][$i].' '.$file['width'][$i].' '.$file['height'][$i].'" /></td>
		<td>'.$make_link.'</td>
		<td>'.bytestostring($file['size'][$i],1).'</td>
		<td>'.$file['type'][$i].'</td>
		<td>'.date(DATE_FORMAT,$file['modified'][$i]).'</td>
		<td><input class="delcheckbox" type="checkbox" name="file_to_del['.$file['name'][$i].']" value="1" /></td></tr>';
		}
	echo '</table>';
?></form></div>
</fieldset>


<script type="text/javascript">
function confirm_del(f){
var Frm = document.forms[0]; 
var e, i = 0, checked = false;
while (e = f.elements[i++]) {if (e.type == 'checkbox' && e.checked) checked = true};
if (!checked){
alert('<?php echo TXT_FILE_NO?>');
return false;
}else{


	var delMsg = confirm("<?php echo TXT_QUSTION;?>\n\n<?php echo TXT_DO_CLICK;?>\n\n");
	if (delMsg == true) {
return true;
}
return false;
}
}
</script>