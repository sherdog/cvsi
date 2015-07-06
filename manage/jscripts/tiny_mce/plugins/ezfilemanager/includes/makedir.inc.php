<fieldset>
<legend><?php echo TXT_MAKE_DIR_IN?>: <?php echo $xdir?></legend>


<form action="do_makedir.php" method="post" enctype="multipart/form-data"  target="makedir_iframe" onSubmit="javascript:do_makedir();">
<?php echo TXT_NEW_DIR;?>
<br />
<input type='text' name='newdir' id='newdir' class='fieldtext' style='width:250px' />
<br />
<input type='hidden' name='upload_path' id='upload_path' value='<?php echo WORKING_PATH?>'>
<input type="submit" value="<?php echo TXT_MAKE?>"  id='submit1' name='submit1' />
</form>
<iframe  name="makedir_iframe" id="makedir_iframe" hspace="0" vspace="0" frameborder="0" style="width: 100%; height: 30px;padding:0px;margin:0px;visibility:hidden;" src=""></iframe>
</fieldset>
<script type="text/javascript">
function do_makedir(){
document.getElementById('makedir_iframe').style.visibility = 'visible';
}
function do_clear_dir_msg(){
window["makedir_iframe"].document.body.innerHTML= '';
document.getElementById('makedir_iframe').style.visibility = 'hidden';
}

</script>