<?php
//used in conjuction with the gallery area
//display form to add caption and set sort order number.

include('master.inc.php');
include('application.php');

if(!isset($_GET['gallery'])) { echo "Gallery is not set!"; }

$galleryResults = dbQuery('SELECT * FROM  gallery_images WHERE  gallery_image_id =  ' .$_GET['image_id']);
$gal = dbFetchArray($galleryResults);

?> 


<table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td align="right"><a href="#" class="jqmClose"><em>Close</em></a></td>
    </tr>
  </table>
<div class="success" style="display:none;">Saved image successully <a href="javascript:void(0);" onClick="location.reload(true);">Close and refresh window</a></div>
<div class="error" style="display:none;">Whoops, there was an error saving your information<br /><a href="#" id="jqmClose">Close Window</a></div>
<div id="imageForm">
<form id="imageCaptionForm" method="post" action="saveCaption.php">
<input type="hidden" name="action" value="save" />
<input type="hidden" name="gallery_id" id="gallery_id" value="<?=$_GET['gallery']?>" />
<input type="hidden" name="image_id" id="image_id" value="<?=$_GET['image_id']?>" />
 
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
   
    <tr>
      <td width="100" rowspan="5" valign="top"><img src="<?=UPLOAD_DIR_URL.getThumbnailFilename($gal['gallery_image_filename'], 'small')?>" /></td>
      <td class="pageTitleSub">Image Caption</td>
    </tr>
    <tr>
      <td><textarea name="desc" cols="35" rows="5"  id="desc"><?=output($gal['gallery_image_caption'])?></textarea></td>
    </tr>
    <tr>
      <td class="pageTitleSub">Sort Order</td>
    </tr>
    <tr>
      <td><input name="sort_order" type="text" class="fieldLabel" id="sort_order" value="<?=$gal['gallery_image_sort_order']?>" /></td>
    </tr>
     <tr>
      <td class="pageTitleSub">Featured Image? <label><input name="featured" type="checkbox" id="featured" value="1" <? if($gal['gallery_image_featured']) echo " checked"; ?>>Yes</label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="button" id="button" class="submit" value="Save Image" /> <a style="margin-left:20px; text-decoration:none; background-color:#FF0006; padding:2px; color:white;" href="<?=PAGE_MANAGE?>?section=images&gallery=<?=$_GET['gallery']?>&image_id=<?=$gal['gallery_image_id']?>&action=delete" onclick="return confirm('Are you sure you want to delete this image?');">Delete Image</a></td>
    </tr>
  </table>
</form>
<script type="text/javascript" src="jscripts/jquery.form.js"></script> 
<script language="javascript">
$(function() {
	$('#imageCaptionForm').ajaxForm(function() { 
		$('.success').fadeIn(200).show();
	}); 
});
</script>



</div>
</body>
</html>