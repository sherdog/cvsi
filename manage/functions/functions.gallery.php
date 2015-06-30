<?php


function printGalleryHeadJS() {
	
	echo "<link href=\"swfupload/default.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
	echo "<script type=\"text/javascript\" src=\"swfupload/swfupload.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"swfupload/handlers.js\"></script>\n";
	echo "<script type=\"text/javascript\">\n";
	echo "		var swfu;\n";
	echo "		window.onload = function () {\n";
	echo "			swfu = new SWFUpload({\n";
	echo "				// Backend Settings\n";
	echo "				upload_url: \"upload.php\",\n";
	echo "				post_params: {\"gallery_id\": \"".$_GET['gallery']."\"},\n";
	
	echo "				// File Upload Settings\n";
	echo "				file_size_limit : \"6 MB\",	// 6MB\n";
	echo "				file_types : \"*.jpg\",\n";
	echo "				file_types_description : \"Images\",\n";
	echo "				file_upload_limit : \"0\",\n";
	
	echo "				// Event Handler Settings - these functions as defined in Handlers.js\n";
	echo "				//  The handlers are not part of SWFUpload but are part of my website and control how\n";
	echo "				//  my website reacts to the SWFUpload events.\n";
	
	echo "				file_queue_error_handler : fileQueueError,\n";
	echo "				file_dialog_complete_handler : fileDialogComplete,\n";
	echo "				upload_progress_handler : uploadProgress,\n";
	echo "				upload_error_handler : uploadError,\n";
	echo "				upload_success_handler : uploadSuccess,\n";
	echo "				upload_complete_handler : uploadComplete,\n";
	
	echo "				// Button Settings\n";
	echo "				button_image_url : \"swfupload/SmallSpyGlassWithTransperancy_17x18.png\",\n";
	echo "				button_placeholder_id : \"spanButtonPlaceholder\",\n";
	echo "				button_width: 180,\n";
	echo "				button_height: 18,\n";
	echo "				button_text : '<span class=\"button\">Select Images <span class=\"buttonSmall\">(2 MB Max)</span></span>',\n";
	echo "				button_text_style : \".button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }\",\n";
	echo "				button_text_top_padding: 0,\n";
	echo "				button_text_left_padding: 18,\n";
	echo "				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,\n";
	echo "				button_cursor: SWFUpload.CURSOR.HAND,\n";
	
	echo "				// Flash Settings\n";
	echo "				flash_url : \"swfupload/swfupload.swf\",\n";
	
	echo "				custom_settings : {\n";
	echo "					upload_target : \"divFileProgressContainer\"\n";
	echo "				},\n";
					
	echo "				// Debug Settings\n";
	echo "				debug: false\n";
	echo "			});\n";
	echo "		};\n";
	echo "	</script>\n";
	
}

function printGalleryModal() {
	echo "<script language=\"javascript\" src=\"jscripts/jquery.modal.js\"></script>\n";
	echo "<link rel=\"stylesheet\" href=\"css/jquery.jmodal.css\" />\n";
	echo "<script language=\"javascript\">\n";
	echo "$(document).ready( function() {\n";
	echo "	$('#jqModal').jqm({ajax: '@href', trigger: 'a.modal'});\n";
	echo "})\n"; 	
	echo "</script>\n";
}


function printFilesHeadJS() {
	
	echo "<link href=\"swfupload/default-files.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
	echo "<script type=\"text/javascript\" src=\"swfupload/swfupload.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"swfupload/swfupload.swfobject.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"swfupload/swfupload.queue.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"swfupload/fileprogress.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"swfupload/handlers-files.js\"></script>\n";
	echo "<style type=\"text/css\">\n";
	echo ".swfupload {\n";
	echo "	position: absolute;\n";
	echo "	z-index: 1;\n";
	
	echo "}\n";
	echo "div.flash {\n";
	echo "	-moz-border-radius-bottomleft:5px;\n";
	echo "	-moz-border-radius-bottomright:5px;\n";
	echo "	-moz-border-radius-topleft:5px;\n";
	echo "	-moz-border-radius-topright:5px;\n";
	echo "	border-color:#666;\n";
	echo "	margin:10px 5px;\n";
	echo "	width:375px;\n";
	echo "	}\n";
	echo "	div.fieldset {\n";
	echo "	border:1px solid #666;\n";
	echo "	margin:10px 0;\n";
	echo "	padding:20px 10px;\n";
	echo "	}\n";
	echo " span.legend { color:#333; background-color:#E4EAF1; } \n";
	echo "	</style>\n";
	echo "<script type=\"text/javascript\">\n";
	echo "var swfu;\n";
	
	
	echo "	SWFUpload.onload = function () {\n";
	echo "	var settings = {\n";
	echo "		flash_url : \"swfupload/swfupload.swf\",\n";
	echo "		upload_url: \"upload-files.php\",\n";
	echo "		post_params: {\"cid\": \"".$_GET['cid']."\"},\n";
	echo "		file_size_limit : \"100 MB\",\n";
	echo "		file_types : \"*.*\",\n";
	echo "		file_types_description : \"All Files\",\n";
	echo "		file_upload_limit : 100,\n";
	echo "		file_queue_limit : 0,\n";
	echo "		custom_settings : {\n";
	echo "			progressTarget : \"fsUploadProgress\",\n";
	echo "			cancelButtonId : \"btnCancel\"\n";
	echo "		},\n";
	echo "		debug: false,\n";
	
	echo "		// Button Settings\n";
	echo "		button_placeholder_id : \"spanButtonPlaceholder\",\n";
	echo "		button_width: 61,\n";
	echo "		button_height: 22,\n";
	echo "		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,\n";
	echo "		button_cursor: SWFUpload.CURSOR.HAND,\n";
	
	echo "		// The event handler functions are defined in handlers.js\n";
	echo "		swfupload_loaded_handler : swfUploadLoaded,\n";
	echo "		file_queued_handler : fileQueued,\n";
	echo "		file_queue_error_handler : fileQueueError,\n";
	echo "		file_dialog_complete_handler : fileDialogComplete,\n";
	echo "		upload_start_handler : uploadStart,\n";
	echo "		upload_progress_handler : uploadProgress,\n";
	echo "		upload_error_handler : uploadError,\n";
	echo "		upload_success_handler : uploadSuccess,\n";
	echo "		upload_complete_handler : uploadComplete,\n";
	echo "		queue_complete_handler : queueComplete,	// Queue plugin event\n";
			
	echo "		// SWFObject settings\n";
	echo "		minimum_flash_version : \"9.0.28\",\n";
	echo "		swfupload_pre_load_handler : swfUploadPreLoad,\n";
	echo "		swfupload_load_failed_handler : swfUploadLoadFailed\n";
	echo "	};\n";
	echo "swfu = new SWFUpload(settings);";
	echo "};\n";
	echo "</script>\n";

	
}

function printFilesModal() {
	echo "<script language=\"javascript\" src=\"jscripts/jquery.modal.js\"></script>\n";
	echo "<link rel=\"stylesheet\" href=\"css/jquery.jmodal.css\" />\n";
	echo "<script language=\"javascript\">\n";
	echo "$(document).ready( function() {\n";
	echo "	$('#jqModal').jqm({ajax: '@href', trigger: 'a.modal'});\n";
	echo "})\n"; 	
	echo "</script>\n";
}



function get_gallery_image_count($galleryID) {
	if($galleryID=="") { echo "0"; }
	//return count!<br>
	$linkstart = "<a href=\"".PAGE_MANAGE."?section=gallery&action=images&gallery=".$galleryID."\"><img src=\"images/images.png\" border=\"0\">";
	$linkend = "</a>";
	$results = dbQuery('SELECT gallery_image_id FROM gallery_images WHERE gallery_id = ' . $galleryID);
	echo $linkstart . " (".dbNumRows($results).")".$linkend;
}

function print_gallery_title($galleryID) {
	$results = dbQuery('SELECT * FROM gallery WHERE gallery_id = ' . $galleryID);
	$row = dbFetchArray($results);
	echo output($row['gallery_title']);
}

function print_gallery_date_added($galleryID) {
	$results = dbQuery('SELECT gallery_date_added FROM gallery WHERE gallery_id = ' . $galleryID);
	$row = dbFetchArray($results);
	return date('m/d/Y', $row['gallery_date_added']);
}

function print_gallery_image_thumbs($galleryID, $display="*", $type="div") {
	$sql = 'SELECT * FROM gallery_images WHERE gallery_id = ' . $galleryID . ' AND gallery_image_featured = 0 ORDER BY gallery_image_sort_order';
	$imgResults = dbQuery($sql );
	
	if(dbNumRows($imgResults)) {
		echo "<h1>Current Images</h1>\n";
		
		if($type=="ul") {
			echo "<ul>\n";
			while($img = dbFetchArray($imgResults)) {
				if($img['gallery_image_featured'] == 1) $featured = " featured"; else $featured = ""; 
				echo "<li class=\"sortableitem\"><a href=\"editimage.php?gallery=".$galleryID."&image_id=".$img['gallery_image_id']."\" class=\"galleryThumb modal ".$featured."\" ><img border=\"0\" src=\"".UPLOAD_DIR_URL.getThumbnailFilename($img['gallery_image_filename'], 'thumb')."\" class=\"gallery_img\" /></a></li>\n";
			}
			echo "</ul>\n";
		} 
		if($type == "div") {
			echo "<div id=\"gallery_current_images\" align=\"center\">";
			
			$featuredResults = dbQuery('SELECT * FROM gallery_images WHERE gallery_id = ' . $galleryID . ' AND gallery_image_featured = 1 ORDER BY gallery_image_sort_order');
			while($feature = dbFetchArray($featuredResults)) {
				if($feature['gallery_image_featured'] == 1) $featured = " featured"; else $featured = ""; 
				echo "<div id=\"img_".$img['gallery_image_id']."\" class=\"galeryImg featuredImg\">";
				echo "<a href=\"editimage.php?gallery=".$galleryID."&image_id=".$feature['gallery_image_id']."\" class=\"galleryThumbFeatured modal ".$featured."\" title=\"".$feature['gallery_image_caption']."\" ><img border=\"0\" src=\"".UPLOAD_DIR_URL.getThumbnailFilename($feature['gallery_image_filename'], 'small')."\" class=\"gallery_img_featured\" /><br><strong>Sort#:</strong>".$feature['gallery_image_sort_order']."</a>";
				echo "</div>\n";
			} 
			echo "<div class=\"clear\"></div>\n";
			while($img = dbFetchArray($imgResults)) {
				if($img['gallery_image_featured'] == 1) $featured = " featured"; else $featured = ""; 
				echo "<div id=\"img_".$img['gallery_image_id']."\" class=\"galeryImg\">";
				echo "<a href=\"editimage.php?gallery=".$galleryID."&image_id=".$img['gallery_image_id']."\" class=\"galleryThumb modal ".$featured."\" title=\"".$img['gallery_image_caption']."\" ><img border=\"0\" src=\"".UPLOAD_DIR_URL.getThumbnailFilename($img['gallery_image_filename'], 'thumb')."\" class=\"gallery_img\" /><br><strong>Sort#:</strong>".$img['gallery_image_sort_order']."</a>";
				echo "</div>\n";
			}
			echo "<div class=\"clear\"></div>\n";
			echo "</div>";
		}
	} 
}


?>