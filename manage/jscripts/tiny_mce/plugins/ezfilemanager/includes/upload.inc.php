<fieldset>
<legend><?php echo TXT_UPLOAD_TO?>: <?php echo $xdir?></legend>


<form action="do_upload.php" method="post" enctype="multipart/form-data"  target="upload_iframe" onSubmit="javascript:do_upload();">
<?php echo TXT_SELECT_FILE." (".bytestostring($ezfilemanager['maxsize'][$_GET['type']]).")";?>
<br />
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $ezfilemanager['maxsize'][$_GET['type']]?>">
<input type="file" name="filename" id="filename"   class='fieldtext' style='width:250px' />&nbsp;
<input type='hidden' name='type' id='type' value='<?php echo $_GET['type']?>' />
<br />
<input type='hidden' name='upload_path' id='upload_path' value='<?php echo WORKING_PATH?>' />
<input type="submit" value="<?php echo TXT_UPLOAD?>"  id='submit1' name='submit1' />
</form>

<iframe  name="upload_iframe" id="upload_iframe" hspace="0" vspace="0" frameborder="0" style="width: 100%; height: 30px;padding:0px;margin:0px;visibility:hidden;" src=""></iframe>
</fieldset><div style='background:#f0f0f0'>
<?php 
echo TXT_YOU_CAN." ";
foreach ($ezfilemanager['filetype'][$_GET['type']] as $value) {
    echo ",".$value." ";
}
echo TXT_FILES;
?>
</div>
<script type="text/javascript">
function do_upload(){
document.getElementById('upload_iframe').style.visibility = 'visible';
}
function do_clear_msg(){
var theiframe=document.getElementById('upload_iframe');
window["upload_iframe"].document.body.innerHTML= '';
document.getElementById('upload_iframe').style.visibility = 'hidden';
}
</script>