<?
include('master.inc.php');
include('application.php');

if(!$_SESSION['user_logged_in'] || !isset($_SESSION['client'])){
	addError('Your session has timed out please login again');
	redirect(PAGE_LOGIN);
}
$trail = new breadcrumb();//start breadcrumb class
$trail->add("Dashboard", PAGE_DEFAULT);
$trail->add("Publish", "");


//save the differet types of content here =) 

if(isset($_POST['action'])) {
switch($_POST['action']){
	case 'podcasts':
	
					$publish = explode('/', $_POST['pod_casts_publish_date']);
					$publishDateStart = mktime(0,0,0, $publish[0], $publish[1], $publish[2]);
					if($_POST['feature'] == '1') {
						//delete existing and make this one featured.
						dbQuery('UPDATE pod_casts SET pod_casts_feature = 0');
						$row['pod_casts_feature'] = 1;	
					}
					$row['pod_casts_title'] = input($_POST['pod_casts_title']);
					$row['pod_casts_desc'] = $_POST['pod_casts_desc'];
					$row['pod_casts_publish_date'] = $publishDateStart;
					$row['pod_casts_url'] = $_POST['pod_casts_url'];
					$row['pod_casts_date_updated'] = time();
					$row['pod_casts_author'] = $_SERVER['user_id'];
					$row['pod_casts_status'] = input($_POST['pod_casts_status']);
					if($_FILES['file']['name'] != "" ) { //uploading flash file
						$filename = fixFilename($_FILES['file']['name']);
						uploadFile($_FILES['file'], $filename);
						$row['pod_casts_filename'] = $filename;
					}
					
					$row['pod_casts_date_added'] = time();
					
					dbPerform('pod_casts', $row, 'insert');
					addMessage("Added podcast successfully");
					redirect(PAGE_MANAGE."?section=podcasts");
	
	break;
	case 'testimonials':
			$row['testimonials_text'] = input($_POST['testimonials_text']);
			$row['testimonials_author'] = input($_POST['testimonials_author']);
			if($_FILES['testimonials_image']['name'] != '') {
					//uploading image!
					$filename = time()."_".$_FILES['testimonials_image']['name'];
					uploadFile($_FILES['testimonials_image'], $filename);
					makeThumbnail($filename, UPLOAD_DIR, 150, '', 'small');
					makeThumbnail($filename, UPLOAD_DIR, 50, '', 'xsmall');
					makeThumbnail($filename, UPLOAD_DIR, 250, '', 'large');
					$row['testimonials_image'] = $filename;
				}
			$row['testimonials_date_added'] = time();
			dbPerform('testimonials', $row,'insert');
			addMessage("Updated testimonial successfully");
			redirect(PAGE_MANAGE."?section=testimonials");
			
	break;
	case 'gallery':
	
		$row['gallery_desc'] = input($_POST['desc']);
		$row['gallery_title'] = input($_POST['title']);
		$row['gallery_sort'] = ($_POST['sort_order'] != '') ? $_POST['sort_order'] : 0;
		$row['gallery_date_added'] = strtotime($_POST['publish_date']);
		$row['gallery_custom_1'] = $_POST['gallery_custom_1'];
		$row['gallery_custom_2'] = $_POST['gallery_custom_2'];
		$row['gallery_custom_3'] = $_POST['gallery_custom_3'];
		$row['gallery_custom_4'] = $_POST['gallery_custom_4'];
		$row['gallery_custom_5'] = $_POST['gallery_custom_5'];
		$row['gallery_feature'] = ($_POST['gallery_feature'] != '') ? $_POST['gallery_feature'] : 0;
		
		if($_FILES['video_file']['name'] != "" ) { //uploading flash file
			$videoName = fixname($_FILES['video_file']['name']);
			upload_file($_FILES['video_file']['tmp_name'], UPLOAD_DIR_MEDIA.$videoName);
			$row['gallery_url_type'] = 'internal';
			$row['gallery_url'] = $videoName;
		} else {
			if($_POST['url'] != "") {
				//pointing to a youtube video
				$row['gallery_url'] = $_POST['url'];
				$row['gallery_url_type'] = 'external';
			}
		}
		
		$row['client_id'] = $_SESSION['client'];
		
		dbPerform('gallery', $row, 'insert');
		$galleryID = dbInsertID();
		
		addMessage("Added " . output($_POST['title']) . ' successfully.');
		
		if($_POST['sort_order'] != "") {
			$gal2['gallery_sort'] = $_POST['sort_order'];	
		}else{
			$gal2['gallery_sort'] = $galleryID;
		}
		
		dbPerform('gallery', $gal2, 'update', 'gallery_id='.$galleryID);
		//redirect(PAGE_MANAGE."?section=gallery&action=images&gallery=".$galleryID);
		redirect(PAGE_MANAGE."?section=gallery");
	break;
	
	case 'downloadables':
				$row['page_downloads_title'] = input($_POST['page_downloads_title']);
				$row['page_downloads_category'] = $_POST['page_downloads_category'];
				$row['page_downloads_date_added'] = time();
				if($_FILES['file']['name'] != "" ){ 
					$filename = fixFilename($_FILES['file']['name']);
					uploadDownloadable($_FILES['file'], $filename);
					$row['page_downloads_filename'] = $filename;
				}
				
				$row['client_id'] = $_SESSION['client'];
				
				dbPerform('page_downloads', $row, 'insert');
				addMessage("Added file successfully");
				redirect(PAGE_MANAGE."?section=downloadables");
				
	break;
	
	case 'webpage':
					$publish = explode('/', $_POST['publish_date']);
					$publishDate = mktime(0,0,0, $publish[0], $publish[1], $publish[2]);
					//k we want to make sure that we save everything and then redirect the user back to the manage area which displays all current pages
					$row['page_content_title'] 				= input($_POST['title']);
					$row['page_content_text'] 				= $_POST['content'];
					$row['client_id'] 						= $_POST['client'];
					$row['page_content_publish_date'] 		= $publishDate;
					$row['page_content_seo_title'] 			= $_POST['seo_title'];
					$row['page_content_seo_description'] 	= $_POST['seo_description'];
					$row['page_content_seo_keyword'] 		= $_POST['seo_keywords'];
					$row['page_content_sort_order'] 		= ($_POST['sort_order']) ? $_POST['sort_order'] : 0;
					$row['page_content_author'] 			= $_POST['author'];
					$row['page_content_added'] 				= time();
					$row['parent'] 							= ($_POST['parent']) ? $_POST['parent'] : 0;
					$row['page_content_status'] 			= $_POST['status'];
					$row['page_content_member']				= ($_POST['member']) ? $_POST['member'] : 0;
					$row['custom_url']						= $_POST['custom_url'];
					$row['page_content_form'] 				= $_POST['page_content_form'];
					$row['page_content_show_in_menu']		= $_POST['page_content_show_in_menu'];
					$row['client_id'] 						= $_SESSION['client'];
					
					$pageurl = create_page_url($_POST['title']);
					$row['page_content_url'] 				= $pageurl;

					
					if($_FILES['page_content_image']['name'] != "") {
						//upload da file =)
						$filename =time()."_".$_FILES['page_content_image']['name'];
						uploadFile($_FILES['page_content_image'], $filename);
						makeThumbnail($filename, UPLOAD_DIR, 620, '', 'main');
						$row['page_content_image'] = $filename;
					}

					
					$pageUrlCheck = dbQuery('SELECT page_content_id FROM page_content WHERE page_content_url = "'.$_POST['url'].'"');
					if(dbNumRows($pageUrlCheck)){
						//url already exists! we need to prepend something to it to make it unique!
						//get parents title and prepend!
						
						$row['page_content_url'] = time()."_".$row['page_content_url'];
					}
					
					
					dbPerform('page_content', $row, 'insert');
					$page_content_id = dbInsertID();
					
					if(isset($_POST['downloadables'])) {
						foreach($_POST['downloadables'] as $key => $val) {
							$dls['page_downloads_id'] = $val;
							$dls['page_content_id'] = $_POST['id'];
							$dls['page_content_downloads_sort'] = $_POST['page_downloads_sort'][$val];
							dbPerform('page_content_downloads', $dls, 'insert');
							
						}
					}
					
					addMessage("Added Page Successfully");
					redirect(PAGE_MANAGE."?section=webpage");
	
	
	break;
	case 'sponsors':
					$row['sponsor_name'] = $_POST['sponsor_name'];
					$row['sponsor_url'] = $_POST['sponsor_url'];
					$row['sponsor_desc'] = $_POST['sponsor_desc'];
					$row['sponsor_short_desc'] = $_POST['sponsor_short_desc'];
					$row['sponsor_date_added'] = time();
					
					if($_FILES['sponsor_logo']['name'] != '') {
						$filename = $_POST['sponsor_name']."_".$_FILES['sponsor_logo']['name'];
						uploadFile($_FILES['sponsor_logo'], $filename);
						makeThumbnail($filename, UPLOAD_DIR, 150, '', 'small');
						makeThumbnail($filename, UPLOAD_DIR, 50, '', 'xsmall');
						makeThumbnail($filename, UPLOAD_DIR, 250, '', 'large');
						
						$row['sponsor_logo'] = $filename;
					}
					
					$row['sponsor_level'] = $_POST['sponsor_level'];
					
					dbPerform('sponsors', $row, 'insert');
					addMessage("Added sponsor successfully");
					redirect('manage.php?section=sponsors');
					
					
	break;
	case 'article':
					$publish = explode('/', $_POST['publish_date']);
					$publishDate = mktime(0,0,0, $publish[1], $publish[0], $publish[2]);
					//k we want to make sure that we save everything and then redirect the user back to the manage area which displays all current pages
					$article['article_title'] = input($_POST['title']);
					$article['article_text'] = $_POST['content'];
					$article['client_id'] = $_POST['client'];
					$article['article_publish_date'] = $publishDate;
					$article['article_url'] = $_POST['url'];
					$article['article_seo_title'] = $_POST['seo_title'];
					$article['article_description'] = $_POST['seo_description'];
					$article['article_seo_keyword'] = $_POST['seo_keywords'];
					$article['article_sort_order'] = $_POST['sort_order'];
					$article['author'] = $_POST['author'];
					$article['article_added'] = time();
					
					if(isset($_POST['sticky'])){
						$article['article_sticky'] = 1;	
					}
					
					$row['client_id'] = $_SESSION['client'];
					
					dbPerform('articles', $article,'insert');
					addMessage("Added Article Successfully");
					redirect(PAGE_MANAGE."?section=article");
	break;
	
	case 'blog':
	
		  $publish = explode('/', $_POST['publish_date']);
		  $publishDateStart = mktime(0,0,0, $publish[0], $publish[1], $publish[2]);
		  
		  
		  
		  $blog['title'] = input($_POST['title']);
		  $blog['content'] = $_POST['content'];
		  $blog['publish_date'] = $publishDateStart;
		  
		//  $blog['news_tag'] = $_POST['seo_keywords'];
		  $blog['author'] = $_POST['author'];
		//  $blog['client_id'] = $_POST['client'];
		//   $blog['category'] = $_POST['category'];
		  $blog['snippet'] = $_POST['snippet'];
		  
		 if($_FILES['file']['name'] != "" ) { //uploading flash file
			  $filename = fixFilename($_FILES['file']['name']);
			  uploadFile($_FILES['file'], $filename);
			  makeThumbnail($filename, UPLOAD_DIR, 150, '', 'medium');
			  makeThumbnail($filename, UPLOAD_DIR, 50, '', 'thumb');
			  makeThumbnail($filename, UPLOAD_DIR, 400, '', 'large');
			  makeThumbnail($filename, UPLOAD_DIR, 600, '', 'xlarge');
			  makeThumbnail($filename, UPLOAD_DIR, 250, '', 'post');
			  $blog['filename'] = $filename;
		  }
		  
		  if($_FILES['file']['podcast'] != "" ) { //uploading flash file
				$filename = fixFilename($_FILES['file']['podcast']);
				uploadFile($_FILES['file'], $filename);
				$blog['podcast'] = $filename;
			}
		  
		  dbPerform('blog', $blog, 'insert');
		  
		  addMessage("Added post sucessfully");
		  redirect(PAGE_MANAGE."?section=blog");

	break;

	
	
	case 'event':
	
					include('functions/functions.events.php');
					//Save event like we used to.. but at the same time we are going to add it's events in the ehhhh calendar_event_items table...
					$event['calendar_events_url'] = $_POST['url'];
					$event['calendar_events_title'] = input($_POST['calendar_events_title']);
					$event['calendar_events_description'] = input($_POST['calendar_events_description']);
					$event['calendar_events_status'] = $_POST['calendar_events_status'];
					$event['calendar_events_description_short'] = input($_POST['calendar_events_description_short']);
					$event['author'] = $_SESSION['user_id'];
					$event['calendar_events_status'] = $_POST['calendar_events_status'];
					
					if($_POST['event_type_new'] != '') {
						$event['calendar_events_type'] = $_POST['event_type_new'];
					} else {
						$event['calendar_events_type'] = $_POST['calendar_events_type'];
					}
					
					
					//$event['calendar_events_date_added'] = time();
					$event['calendar_events_featured'] = $_POST['calendar_events_featured'];
					
					if($_POST['repeats'] == 'never') {
						$event['calendar_events_repeats'] = 0;
					} else {
						$event['calendar_events_repeats'] = 1;	
					}
					
					dbPerform('calendar_events', $event, 'insert');
					$_POST['id'] = dbInsertID();
					
					if($_FILES['event_image']['name'] != '') {
						//uploading image!
						$filename = time()."_".$_FILES['event_image']['name'];
						uploadFile($_FILES['event_image'], $filename);
						makeThumbnail($filename, UPLOAD_DIR, 150, '', 'small');
						makeThumbnail($filename, UPLOAD_DIR, 50, '', 'xsmall');
						makeThumbnail($filename, UPLOAD_DIR, 250, '', 'large');
						$event['calendar_events_image'] = $filename;
					}
					
					if($_FILES['event_main_image']['name'] != '') {
						//uploading image!
						$filename = time()."_".$_FILES['event_main_image']['name'];
						uploadFile($_FILES['event_main_image'], $filename);
						makeThumbnail($filename, UPLOAD_DIR, 660, '', 'main');
						$event['calendar_events_main_image'] = $filename;
					}
					
					
					dbQuery('DELETE FROM calendar_events_categories WHERE calendar_events_id = ' . $_POST['id']);
					if($_POST['keywords']) {
						foreach($_POST['keywords'] as $key=>$val) {
							if($val != "") {
								$c['calendar_events_id'] = $_POST['id'];
								$c['calendar_events_categories_title'] = $val;
								dbPerform('calendar_events_categories', $c, 'insert');
								
							}
						}
					}
					
					$event['calendar_events_start_date'] = mktime(12,0,0, date('m', strtotime($_POST['start_calendar'])),  date('d', strtotime($_POST['start_calendar'])),  date('Y', strtotime($_POST['start_calendar'])));
					$event['calendar_events_end_date'] = mktime(12,0,0, date('m', strtotime($_POST['end_calendar'])),  date('d', strtotime($_POST['end_calendar'])),  date('Y', strtotime($_POST['end_calendar'])));
					$event['calendar_events_start_time'] = strtotime($_POST['start_calendar'].' '.$_POST['time1']);
					$event['calendar_events_end_time'] = strtotime($_POST['start_calendar'].' '.$_POST['time2']);
					dbPerform('calendar_events', $event, 'update', 'calendar_events_id = ' . $_POST['id']);
					
					addEventItems($_POST);
					
					addMessage("Event updated successfully");
					redirect(PAGE_MANAGE."?section=event");
	
	break;
	case 'alerts':
					$alert['alerts_content'] = input($_POST['content']);
					$alert['alerts_date_added'] = time();
					$alert['alerts_title'] = input($_POST['title']);
					
					dbPerform('alerts', $alert, 'insert');
					addMessage("Added alert successfully");
					redirect(PAGE_MANAGE."?section=alerts");
					
	break;	
	case 'news':
	
					$publish = explode('/', $_POST['publish_date']);
					$publishDateStart = mktime(0,0,0, $publish[0], $publish[1], $publish[2]);
					
					if($_POST['publish_date_end'] != 0) {
						$publish2 = explode('/', $_POST['publish_date_end']);
						$publishDateEnd = mktime(0,0,0, $publish2[0], $publish2[1], $publish2[2]);
						$news['news_end'] = $publishDateEnd;
					} else {
						$news['news_end'] = 0;
					}
					$news['client_id'] = $_SESSION['client'];
					$news['news_title'] = input($_POST['title']);
					$news['news_text'] = $_POST['content'];
					$news['news_start'] = $publishDateStart;
					$news['news_url'] = $_POST['url'];
					if(isset($_POST['sticky'])){
						$news['news_sticky'] = 1;	
					}
					
					$news['news_tag'] = $_POST['seo_keywords'];
					$news['author'] = $_POST['author'];
					$news['client_id'] = $_POST['client'];
					$news['news_created'] = time();
					$news['news_type'] = $_POST['news_type'];
					
					dbPerform('news', $news, 'insert');
					
					addMessage("News added successfully");
					redirect(PAGE_MANAGE."?section=news");
	
	break;
	case 'class':
	
	break;
	case 'banners':
			
				$row['banner_title'] = input($_POST['banner_title']);
				$row['banner_url'] = $_POST['banner_url'];
				$row['banner_url_target'] = $_POST['banner_url_target'];
				$row['banner_publish_date'] = strtotime($_POST['publish_date']);
				$row['banner_date_added'] = time();
				$row['client_id'] = $_SESSION['client'];
				if($_FILES['banner']['name'] != "" ){ 
					$filename = fixFilename($_FILES['banner']['name']);
					uploadBanner($_FILES['banner'], $filename);
					$row['banner_filename'] = $filename;
				}
				
				dbPerform('banners', $row, 'insert');
				addMessage("Added banner successfully");
				redirect(PAGE_MANAGE."?section=banners");
				
				//upload main banner!
				
			
	break;
}
}

switch($_GET['section']){
case 'webpage':
default;
$trail->add("Webpage");
break;
case 'article':
$trail->add("Article");
break;
case 'event':
$trail->add("Event");
break;
case 'gallery':
$trail->add("Gallery");
break;
}

//check to see what module displays! we have to check for permission to be on that particular page:)
if(!$_GET['section']) {
	if(user_has_permission('alerts')){
		$default = 'alerts';	
	}
	if(user_has_permission('banners')){
		$default = 'banners';	
	}
	if(user_has_permission('news')){
		$default = 'news';	
	}
	if(user_has_permission('events')){
		$default = 'event';	
	}
	if(user_has_permission('admin')){
		$default = 'webpage';	
	}
	$_GET['section'] = $default;
}

$page = $_GET['section'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=COMPANY_NAME?> :: Central Intelligence Center</title>
<script language="javascript" src="nav.js"></script>
<script type="text/javascript" src="jscripts/cicfunctions.js"></script>
<script language="javascript" src="jscripts/jquery.js"></script>
<script type="text/javascript" src="jscripts/ui.datepicker.js"></script>
<script type="text/javascript" src="jscripts/jquery.timePicker.js"></script>
<script language="javascript" src="jscripts/jquery.maskedinput.js"></script>
<link rel="stylesheet" href="jscripts/ui.datepicker.css" />
<script language="javascript">
$(document).ready(function(){
  $('.cpNavOff').mouseover( function(){ 
		$(this).removeClass().addClass("navHover");
  }).mouseout( function() {
		$(this).removeClass().addClass("cpNavOff");
  });
  
  $("div.subMenuTab").mouseover(function(){ 
		$(this).removeClass().addClass("subMenuTabHover");								   
  }).mouseout(function() {
	  	$(this).removeClass().addClass("subMenuTab");
  });
  
  $('#toggleDate').click(function(){ 
		$('#publishDate').slideToggle();
		if($(this).html() == "close")
			$(this).html("change");
		else
			$(this).html("close");
  });
  
  $("#calendarContainer").datepicker(
	{
	altField: '#publish_date',
	altFormat: 'mm/dd/yy'
	
	}
  );
  $("#calendarContainerEnd").datepicker(
	{
	altField: '#publish_date_end',
	altFormat: 'mm/dd/yy'
	
	}
  );
 $('#calendar_events_type').click(function() {
		if( $('#calendar_events_type').find(':selected').val() == "new") {
			$('#new_event_type').show();
		} else {
			$('#event_type_new').val("");
			$('#new_event_type').hide();
		}
	});
	
  
	$('#parent_toggle').click(function() {
		$('#page_parent').slideToggle();										  
	});
	$('#author_toggle').click(function() {
		$('#page_author').slideToggle();										  
	});
	$('#order_toggle').click(function() {
		$('#page_order').slideToggle();										  
	});
	$('#seo_options_toggle').click(function() {
		$('#page_seo').slideToggle();										  
	});
	$('#tools_toggle').click(function() {
		$('#page_tools').slideToggle();										  
	});
	$('#downloadables_toggle').click(function() {
		$('#page_downloadables').slideToggle();										  
	});
	<?php 
	if($_GET['section'] == "podcasts") { ?>
	$('#pod_casts_title').keyup(function() {
  		//convert spaces to _ and lowercase everything..
  		var url = cleanURLField( $('#pod_casts_title').val() );
		$('#pod_casts_url_d').text(url);
		$('#pod_casts_url').val(url);
   	});
	<? } ?>
	<?php 
	if($_GET['section'] != "gallery") {
	?>
		$('#title').keyup(function() {
		//convert spaces to _ and lowercase everything..
		var url = cleanURLField( $('#title').val() );
		$('#url').val(url+'.html');
   });
	<?
	}
	?>
});
</script>
<script type="text/javascript">
$(document).ready(function() {
  $(".select_dropdown dt a").click(function() {
	  $(".select_dropdown dd ul").toggle();
  });
  
  $(document).bind('click', function(e) {
	  var $clicked = $(e.target);
	  if (! $clicked.parents().hasClass("select_dropdown"))
		  $(".select_dropdown dd ul").hide();
  });
			  
  $(".select_dropdown dd ul li a").click(function() {
	  var text = $(this).html();
	  $(".select_dropdown dt a").html(text);
	  $(".select_dropdown dd ul").hide();
	  
	  var source = $("#page_content_mirror");
	  source.val($(this).find("span.value").html())
  });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
  $(".select_dropdown-parent dt a").click(function() {
	  $(".select_dropdown-parent dd ul").toggle();
  });
  
  $(document).bind('click', function(e) {
	  var $clicked = $(e.target);
	  if (! $clicked.parents().hasClass("select_dropdown-parent"))
		  $(".select_dropdown-parent dd ul").hide();
  });
			  
  $(".select_dropdown-parent dd ul li a").click(function() {
	  var text = $(this).html();
	  $(".select_dropdown-parent dt a").html(text);
	  $(".select_dropdown-parent dd ul").hide();
	  
	  var source = $("#parent");
	  source.val($(this).find("span.value").html())
  });
});
</script>


<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript">
  tinyMCE.init({
	mode : "specific_textareas",
	editor_selector : "mceEditor",
	theme : "advanced",
	// Theme options
	//plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
	plugins : "media,example,table,inlinepopups,imagemanager,advlink,contextmenu,preview,fullscreen",
	theme_advanced_buttons1 : "mylistbox,removeformat, bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,link,unlink,|,classSelector,template,insertimage,media,preview,fullscreen,code",
	//theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor, image, cleanup, code",
	//theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|sub,sup",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_buttons4 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	inline_styles : true,
	theme_advanced_resizing : true,
	forced_root_block : '',
	keep_styles : false,
	inline : "yes",
	template_templates: 
	[
	<?
	/* Output all template here. */
	$templateResults = dbQuery('SELECT * FROM email_templates');
	$templateCount = dbNumRows($templateResults);
	$rowCount = 1;
	while($template = dbFetchArray($templateResults)) {
		//output the template stuff!
		echo "{\n";
		echo "title : '" .output($template['email_templates_name'])."',\n";
		echo "src : 'communication_template_loader.php?id=".$template['email_templates_id']."',\n";
		echo "description: '" . output($template['email_templates_desc'])."'\n";
		if($rowCount == $templateCount) echo "}\n"; else echo "},\n"; //determines if we are at the last record (we don't want to teh comma on the last record.
		
	$rowCount++;
	}
	?>
	],
	formats : {
		heading_text : { inline : 'span', styles : { color : '#6c522c', fontSize : '18px', fontWeight : 'normal'} },
		subheading_text : { inline : 'span', styles : { color : '#333333', fontSize : '14px', fontWeight : 'bold'} },
		reg_text : { inline : 'span', styles : { color : '#1a1a1a', fontSize : '12px', fontWeight : 'normal'} }

	},
	
	// Example content CSS (should be your site CSS)
	content_css : "css/cms.css",
	width: "650",
	remove_script_host : false,
	relative_urls : false,
	height: "500"
	
});

tinymce.create('tinymce.plugins.ExamplePlugin', {
    createControl: function(n, cm) {
        switch (n) {
            case 'mylistbox':
                var mlb = cm.createListBox('mylistbox', {
                     title : 'Format',
					 max_width : 300,
                     onselect : function(v) {
						 //check to see if val = val if it is, then we remove formatting else we add it!
						if(mlb===v) {
							tinymce.activeEditor.formatter.remove(v)
						} else {
							tinyMCE.activeEditor.formatter.apply(v);
						}
                     }
                });
				 mlb.onRenderMenu.add(function(c, m) {
                    m.settings['max_width'] = 300;
                });

                // Add some values to the list box
                mlb.add('Heading Text', 'heading_text');
                mlb.add('Subheading Text', 'subheading_text');
                mlb.add('Paragraph Text', 'reg_text');

                // Return the new listbox instance
                return mlb;
        }

        return null;
    }
});

// Register plugin with a short name
tinymce.PluginManager.add('example', tinymce.plugins.ExamplePlugin);


//add block section!
function addBlock(id) {
	var _id = id;//Grabs the corect html
	$.get("__getContentBlock.php", { id: _id },
	   function(data){
		tinyMCE.execCommand('mceInsertContent',false, data)
	  });

	
}

</script>

<?php
if($_GET['section'] == 'downloadables') {
printGalleryHeadJS();	
}
?>
<link rel="stylesheet" href="css/styles.css" />
</head>
<div id="topHeader"><? include('header.php'); ?></div>
<div id="header"></div>

<? include('navigation.php'); ?>
<div class="breadcrumbTrail">you are here: <?=$trail->getPath(" > ")?></div>
<?
printMessage();
printError();
?>

<div class="contentStart">
<? include('publish_sub_menu.php'); ?>
<?
switch($_GET['section']) {
case 'banners':
					?>
                     <form action="<?=PAGE_PUBLISH?>" method="post" enctype="multipart/form-data" name="banners" id="banners">
                    <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                    <input type="hidden" name="action" value="<?=$_GET['section']?>" />
                    <table border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="750" valign="top" class="formBody">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="pageTitleSub">Title</td>
                            </tr>
                            <tr>
                              <td><input type="text" name="banner_title" id="banner_title" class="textField-title" style="width:600px" /></td>
                            </tr>
                            <tr>
                              <td class="pageTitleSub">URL</td>
                            </tr>
                            <tr>
                              <td><input type="text" name="banner_url" id="banner_url" class="textField-title" style="width:500px" /></td>
                            </tr>
                            <tr>
                              <td class="pageTitleSub">Open link in</td>
                            </tr>
                            <tr>
                              <td><select name="banner_url_target" id="banner_url_target">
                                <option value="_self">Same Window</option>
                                <option value="_blank">New Window</option>
                              </select></td>
                            </tr>
                            <tr>
                              <td class="pageTitleSub">Banner Image<br />
                            <span class="smallText">The image file should be set to the size: <?=BANNER_IMAGE_SIZE?></span>                           </td>
                            </tr>
                            <tr>
                              <td><input type="file" name="banner" id="banner" /></td>
                            </tr>
                          </table>
                          <div class="mb20"></div>
                          <div class="expandable">
                          <div class="pageTitleSub">SEO Options</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel">Tag Show (comma seperated):</td>
                            </tr>
                            <tr>
                              <td class="fieldLabel"><input type="text" name="seo_keywords" id="seo_keywords" class="textField-title" style="width:600px" /></td>
                            </tr>
                          </table>
                          </div>
                          
                          <div class="mb20"></div>
                          <div class="expandable">
                          <div class="pageTitleSub">Author</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel"><select name="author" class="textField-title" id="author">
                                <?
							  $authorResults =dbQuery("SELECT ui.*, u.* FROM user AS u, user_information AS ui WHERE ui.user_id = u.user_id");
							  while($aInfo = dbFetchArray($authorResults)) {
									echo "<option value=\"".$aInfo['user_id']."\"";
										if($_SESSION['user_id'] == $aInfo['user_id'])
											echo " selected";
									echo ">".$aInfo['user_first_name']." ".$aInfo['user_last_name']."</option>\n";
							  }
							  ?>
                              </select></td>
                            </tr>
                          </table>
                          </div>
                          
                        <div class="mb20"></div></td>
                        <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                          <tr>
                            <td>
                            <div style="margin:0px 10px 10px 10px;">
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                              <tr>
                                <td class="headerCell"><h2>Publish Banner</h2></td>
                              </tr>
                              <tr>
                                <td><strong>Banner Category</strong></td>
                              </tr>
                              <tr>
                                <td><select name="banner_category" id="banner_category">
                                  <option value="sidebar">Homepage 1920x200</option>
                                </select></td>
                              </tr>
                              
                              <tr>
                                <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /> </td>
                              </tr>
                            </table>
                            </div>
                            
                            </td>
                          </tr>
                        </table></td>
                      </tr>
  </table></form>
                    <?
break;
case 'sponsors':
?>
							<form action="<?=PAGE_PUBLISH?>" method="post" enctype="multipart/form-data" name="sponsors" id="sponsors">
                            <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                             <input type="hidden" name="action" value="<?=$_GET['section']?>" />
                            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                            <table  border="0" cellspacing="0" cellpadding="0" >
                              <tr>
                                <td width="650" valign="top" class="formBody">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td class="pageTitleSub">Name</td>
                                    </tr>
                                    <tr>
                                      <td><input type="text" name="sponsor_name" id="sponsor_name" class="textField-title" style="width:650px"  /></td>
                                    </tr>
                                    <tr>
                                      <td class="pageTitleSub">Website URL</td>
                                    </tr>
                                    <tr>
                                      <td>
                                      <input type="text" name="sponsor_url" id="sponsor_url" class="textField-title" style="width:500px" /></td>
                                    </tr>
                                    <tr>
                                      <td class="pageTitleSub">Long Description</td>
                                    </tr>
                                    <tr>
                                      <td><textarea name="sponsor_desc" id="sponsor_desc" cols="65" rows="5"></textarea></td>
                                    </tr>
                                    <tr>
                                      <td class="pageTitleSub">Short  Description</td>
                                    </tr>
                                    <tr>
                                      <td><textarea name="sponsor_short_desc" id="sponsor_short_desc" cols="65" rows="5"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                      <td class="pageTitleSub">Banner Image</td>
                                    </tr>
                                    
                                    <tr>
                                      <td><input type="file" name="sponsor_logo" id="sponsor_logo" /></td>
                                    </tr>
                                  </table>
                                <div class="mb20"></div></td>
                                <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                                  <tr>
                                    <td>
                                    <div style="margin:0px 10px 10px 10px;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                      <tr>
                                        <td class="headerCell"><h2>Save Sponsor</h2></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Sponsor Level</strong>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>
                                          <select name="sponsor_level" id="sponsor_level">
                                            <option value="Level 1">Level 1</option>
                                            <option value="Level 2">Level 2</option>
                                          	<option value="Level 3">Level 3</option>
                                            <option value="Level 4">Level 4</option>
                                          </select>
                                                                            
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /> </td>
                                      </tr>
                                    </table>
                                    </div>
                                    </td>
                                  </tr>
                                </table></td>
                              </tr>
                            </table></form>
                            
						 <script language="javascript">
						  $(document).ready(function(){
							  $("#calendarContainer").datepicker(
								  {
									  altField: '#publish_date',
									  altFormat: 'mm/dd/yy',
									  defaultDate: new Date('<?=date('m/d/Y', $bInfo['banner_publish_date'])?>')
								  }
							  );
						});
						 </script>
<?

break;
case 'gallery':
					?>
                    <form id="event" name="gallery" method="post" action="<?=PAGE_PUBLISH?>">
                    <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                    <input type="hidden" name="action" value="<?=$_GET['section']?>" />
                    <table  border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="750" valign="top" class="formBody"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td colspan="2" class="pageTitleSub">Title</td>
                          </tr>
                          <tr>
                            <td colspan="2"><input type="text" name="title" id="title" class="textField-title" style="width:600px" /></td>
                          </tr>
                          <tr>
                            <td colspan="2" class="pageTitleSub">Project Description</td>
                          </tr>
                          <tr>
                            <td colspan="2"> <? echo printEditorCB("desc", "desc",''); ?></td>
                          </tr>
                          <tr>
                            <td width="17%" class="pageTitleSub">Sort Order </td>
                            <td width="83%" class="pageTitleSub"><input type="text" name="sort_order" id="sort_order" class="textField-title" style="width:50px" value="" /></td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                        </table>

                        <div class="mb20"></div>
                     <div id="seo_options_toggle">
                            <table width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                           	 <td width="29"><img id="" width="29" height="28" src="images/sectionTitleArrow.jpg"/></td>
                            	<td class="sectionTitle">SEO Options <span class="smallText">(optional)</span></td>
                           	 </tr>
                         
                            </table>
                            </div>
                          <div class="expandable" id="page_seo">
                          <div class="pageTitleSub">SEO Options</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class=""><strong>Tag Post (comma seperated):</strong></td>
                            </tr>
                            <tr>
                              <td class="fieldLabel"><input type="text" name="seo_keywords" id="seo_keywords" class="textField-title" style="width:600px"  /></td>
                            </tr>
                          </table>
                          </div>
                          
                          
                          <div class="mb20"></div>
                            <div id="author_toggle">
                            <table width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                           	 <td width="29"><img id="" width="29" height="28" src="images/sectionTitleArrow.jpg"/></td>
                            	<td class="sectionTitle">Author<span class="smallText">(optional)</span></td>
                           	 </tr>
                         
                            </table>
                            </div>
                          <div class="expandable" id="page_author">
                          <div class="pageTitleSub">Author</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel">
                             
                              <select name="author" class="textField-title" id="author">
                                <?
							  $authorResults =dbQuery("SELECT ui.*, u.* FROM user AS u, user_information AS ui WHERE ui.user_id = u.user_id");
							  while($aInfo = dbFetchArray($authorResults)) {
									echo "<option value=\"".$aInfo['user_id']."\"";
										
									echo ">".$aInfo['user_first_name']." ".$aInfo['user_last_name']."</option>\n";
							  }
							  ?>
                              </select>
                               
                              </td>
                            </tr>
                          </table>
                          </div>



                          <div class="mb20"></div>
                          <div class="expandable">
                          <div class="pageTitleSub">SEO Options</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel">Tag Show (comma seperated):</td>
                            </tr>
                            <tr>
                              <td class="fieldLabel"><input type="text" name="seo_keywords" id="seo_keywords" class="textField-title" style="width:600px" /></td>
                            </tr>
                          </table>
                          </div>


                          
                        <div class="mb20"></div></td>
                        <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                          <tr>
                            <td>
                            <div style="margin:0px 10px 10px 10px;">
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                              <tr>
                                <td class="headerCell"><h2>Publish Project</h2></td>
                              </tr>
                              <tr>
                                <td><strong>Project Date</strong><br />
                                  <div id="calendarContainer" style="padding:5px 0px;" align="center"></div>
                                  <input type="text" name="publish_date" id="publish_date" value=""  />
                                  <div class="mt10"></div>
                                  <strong>Feature?</strong>
                                  <input type="checkbox" name="gallery_feature" id="gallery_feature" value="1" <? if($gInfo['gallery_feature']==1) echo " checked"; ?> />
                                  <br />
                                  <span class="smallText">(Checking this will set this project as the first one))</span>
                                  <div class="mt10"></div></td>
                              </tr>
                              <tr>
                                <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /></td>
                              </tr>
                              </table>
                            
                            </div>
                            
                            </td>
                          </tr>
                        </table>      <p>&nbsp;</p></td>
                      </tr>
  </table></form>
  				<script language="javascript">
					$(document).ready(function(){
						$("#calendarContainer").datepicker(
						{
							altField: '#publish_date',
							altFormat: 'mm/dd/yy',
							defaultDate: new Date('<?=date('m/d/Y', time())?>')
						});
				});
				</script>
<?
break;
case 'news':
?>
                    <form id="event" name="webpage" method="post" action="<?=PAGE_PUBLISH?>">
                    <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                    <input type="hidden" name="action" value="news" />
                    <table border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="750" valign="top" class="formBody">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="pageTitleSub">Title</td>
                            </tr>
                            <tr>
                              <td><input type="text" name="title" id="title" class="textField-title" style="width:600px" /></td>
                            </tr>
                            <tr>
                              <td class="pageTitleSub">Page URL</td>
                            </tr>
                            <tr>
                              <td>http://yoursite.com/news/                                
                              <input type="text" name="url" id="url" class="textField-title" style="width:500px" readonly="readonly" /></td>
                            </tr>
                            <tr>
                              <td class="pageTitleSub">Content</td>
                            </tr>
                            <tr>
                              <td> <? echo printEditorCB("content", "content",''); ?></td>
                            </tr>
                          </table>
                          <div class="mb20"></div>
                          <div class="expandable">
                          <div class="pageTitleSub">SEO Options</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel">Tag Event (comma seperated):</td>
                            </tr>
                            <tr>
                              <td class="fieldLabel"><input type="text" name="seo_keywords" id="seo_keywords" class="textField-title" style="width:600px" /></td>
                            </tr>
                          </table>
                          </div>
                          
                          <div class="mb20"></div>
                          <div class="expandable">
                          <div class="pageTitleSub">Author</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel"><select name="author" class="textField-title" id="author">
                                <?
							  $authorResults =dbQuery("SELECT ui.*, u.* FROM user AS u, user_information AS ui WHERE ui.user_id = u.user_id");
							  while($aInfo = dbFetchArray($authorResults)) {
									echo "<option value=\"".$aInfo['user_id']."\"";
										if($_SESSION['user_id'] == $aInfo['user_id'])
											echo " selected";
									echo ">".$aInfo['user_first_name']." ".$aInfo['user_last_name']."</option>\n";
							  }
							  ?>
                              </select></td>
                            </tr>
                          </table>
                          </div>
                          
                        <div class="mb20"></div></td>
                        <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                          <tr>
                            <td>
                            <div style="margin:0px 10px 10px 10px;">
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                              <tr>
                                <td class="headerCell"><h2>Publish News</h2></td>
                              </tr>
                              <tr>
                                <td><strong>Status</strong></td>
                              </tr>
                              <tr>
                                <td><select name="status" id="status">
                                  <option value="published" selected="selected">Published</option>
                                  <option value="pending">Pending Review</option>
                                  <option value="private">Unpublished</option>
                                </select></td>
                              </tr>
                             
                              <tr>
                                <td><strong>Publish Date</strong>
                                  <div id="calendarContainer" style="padding:5px 0px;" align="center"></div>
                                <input type="text" name="publish_date" id="publish_date" value="<?=date('m/d/Y', time())?>" readonly="readonly" />
                                <div class="mt10"></div>
                                <div class="mt10"></div>
                                <strong>Sticky?</strong>
                                <input type="checkbox" name="sticky" id="sticky" value="1" /><br />
                                <span class="smallText">(stickies always remain first on the list and are only removed if you remove the sticky)</span>
                                
                              
                                
                                </td>
                              </tr>
                              <tr>
                                <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /> </td>
                              </tr>
                            </table>
                            
                            </div>
                            
                            </td>
                          </tr>
                        </table>      <p>&nbsp;</p></td>
                      </tr>
  					</table></form>
<?
break;
case 'testimonials':?>
					<form action="<?=PAGE_PUBLISH?>" method="post" enctype="multipart/form-data" name="banners" id="banners">
                            <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                            <input type="hidden" name="action" value="<?=$_GET['section']?>" />
                            <table  border="0" cellspacing="0" cellpadding="0" >
                              <tr>
                                <td width="650" valign="top" class="formBody">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    
                                    <tr>
                                      <td class="pageTitleSub">Testimonial</td>
                                    </tr>
                                    <tr>
                                      <td><textarea name="testimonials_text" id="testimonials_text" cols="65" rows="5"><?=output($bInfo['testimonials_text'])?></textarea></td>
                                    </tr>
                                    <tr>
                                      <td class="pageTitleSub">Name</td>
                                    </tr>
                                    <tr>
                                      <td><input type="text" name="testimonials_author" id="testimonials_author" class="textField-title" style="width:650px" value="<?=output($bInfo['testimonials_author'])?>" /></td>
                                    </tr>
                                    
                                    <tr>
                                      <td class="pageTitleSub">Photo</td>
                                    </tr>
                                    <tr>
                                    	<td>Current Image:<br />
                                        <img src="<?=UPLOAD_DIR_URL.$bInfo['testimonials_image']?>" width="100" height="50" />
                                        </td>
                                    </tr>
                                    <tr>
                                      <td><input type="file" name="testimonials_image" id="testimonials_image" /></td>
                                    </tr>
                                  </table>
                                  <div class="mb20"></div>
                                 
                                  
                                  
                                <div class="mb20"></div></td>
                                <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                                  <tr>
                                    <td>
                                    <div style="margin:0px 10px 10px 10px;">
                                    
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                      <tr>
                                        <td class="headerCell"><h2>Add Testimonial</h2></td>
                                      </tr>
                                      <tr>
                                        <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Add" /> </td>
                                      </tr>
                                    </table>
                                    </div>
                                    </td>
                                  </tr>
                                </table></td>
                              </tr>
                            </table></form><?php
break;
case 'podcasts':?>
                <form id="event" name="webpage" method="post" action="<?=PAGE_PUBLISH?>" enctype="multipart/form-data">
				<input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                <input type="hidden" name="action" value="<?=$_GET['section']?>" />
                <table border="0" cellspacing="0" cellpadding="0" >
				  <tr>
					<td width="785" valign="top" class="formBody">
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
						<tr>
						  <td class="pageTitleSub">Title</td>
						</tr>
						<tr>
						  <td><input type="text" name="pod_casts_title" id="pod_casts_title" class="textField-title" style="width:650px" value="<?=output($n['pod_casts_title'])?>" /></td>
						</tr>
                        <tr>
						  <td class="pageTitleSub">File</td>
						</tr>
						<tr>
						  <td><input type="file" name="file" id="file" /> <span class="smallText">(.mp3 format)</span><br />
                          <?php
						  if($n['pod_casts_filename'] != '' && $_GET['id'] != '' ) {
								echo "<span class=\"smallText\">".$n['pod_casts_filename']." <a href=\"".PAGE_MANAGE."?action=deletepodcast&id=".$_GET['id']."&section=".$_GET['section']."\">(delete)<a/></span>";  
						  }
						  ?>
                          </td>
						</tr>
						<tr>
						  <td class="pageTitleSub">Video URL</td>
					    </tr>
						<tr>
						  <td>
						    <input type="text" name="pod_casts_url2" id="pod_casts_url2" class="textField-title" style="width:650px" value="<?=output($n['pod_casts_url'])?>" />
						    <br />
						    <span class="smallText">**This must be the URL to the video on youtube (ex. http://www.youtube.com/watch?v=B7TICfqeck)</span><br />
						   </td>
					    </tr>
                        
						<tr>
						  <td class="pageTitleSub">Short desc</td>
						</tr>
                        
						<tr>
						  <td>
                          <? // echo printEditorCB("pod_casts_desc", "pod_casts_desc", output($n['pod_casts_desc']);?>
                          <textarea style="width:600px; height:80px;" id="pod_casts_desc" name="pod_casts_desc" class="textField-title"><?=output($n['pod_casts_desc']);?></textarea>
                         </td>
						</tr>
                         <tr>
						  <td class="pageTitleSub">Direct link URL</td>
						</tr>
                        <tr>
						  <td><?=SITE_URL?>podcasts/view/<span id="pod_casts_url_d"><?=output($n['pod_casts_url'])?></span>
                          <input type="hidden" name="pod_casts_url" id="pod_casts_url" value="<?=$n['pod_casts_url']?>" />
                          </td>
						</tr>
					  </table>
					 
					<div class="mb20"></div></td>
					<td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
					  <tr>
						<td>
						<div style="margin:0px 10px 10px 10px;">
						<table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
						  <tr>
							<td class="headerCell"><h2>Publish Media</h2></td>
						  </tr>
						  <tr>
							<td><strong>Status</strong></td>
						  </tr>
						  <tr>
							<td><select name="pod_casts_status" id="pod_casts_status">
							  <option value="published" <? if($n['pod_casts_status'] == 'active') echo " selected"; ?>>Published</option>
							  <option value="pending" <? if($n['pod_casts_status'] == 'pending') echo " selected"; ?>>Pending Review</option>
							  <option value="private" <? if($n['pod_casts_status'] == 'private') echo " selected"; ?>>Unpublished</option>
							</select></td>
						  </tr>
						 
						  <tr>
							<td>
							<strong>Publish Date</strong><br />
							<div id="calendarContainer" style="padding:5px 0px;" align="center"></div>
							<input type="hidden" name="pod_casts_publish_date" id="pod_casts_publish_date" value="<?=date('m/d/Y')?>" readonly="readonly" />
							<div class="mt10"></div>
							<div class="mt10"></div>
							
							
							</td>
						  </tr>
                          <tr>
						  <td><input type="checkbox" name="feature" value="1" <?php if($n['pod_casts_feature']) echo " checked"; ?> /> 
						    Featured?<br />
						    <span class="smallText">By checking this, this podcast will appear on the homepage<br />
						    <strong>**Please note, that this must be an mp3 file and not video</strong> </span></td>
						  </tr>
						  <tr>
							<td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /> </td>
						  </tr>
						</table>
						
						</div>
						
						</td>
					  </tr>
					</table></td>
				  </tr>
				</table></form>
                <script language="javascript">
						$(document).ready(function(){
						
							$("#calendarContainer").datepicker(
							{
								altField: '#pod_casts_publish_date',
								altFormat: 'mm/dd/yy',
								defaultDate: new Date('<?=date('m/d/Y')?>')
							}
							);
						 
							
						});
						</script>
				<?
break;
case 'alerts':
?>
                    <form id="event" name="webpage" method="post" action="<?=PAGE_PUBLISH?>">
                    <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                    <input type="hidden" name="action" value="alerts" />
                    <table  border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="750" valign="top" class="formBody">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="pageTitleSub">Title</td>
                            </tr>
                            <tr>
                              <td><input type="text" name="title" id="title" class="textField-title" style="width:600px" value="" /></td>
                            </tr>
                            <tr>
                              <td class="pageTitleSub">Content</td>
                            </tr>
                            <tr>
                              <td>
                               <? echo printEditorCB("content", "content",''); ?></td>
                            </tr>
                          </table>
                         </td>
                        <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                          <tr>
                            <td>
                            <div style="margin:0px 10px 10px 10px;">
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                              <tr>
                                <td class="headerCell"><h2>Publish Alert</h2></td>
                              </tr>
                             
                              <tr>
                                <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /> </td>
                              </tr>
                            </table>
                            
                            </div>
                            
                            </td>
                          </tr>
                        </table>      <p>&nbsp;</p></td>
                      </tr>
  					</table></form>
<?
break;
case 'event':
?>
                 <form id="event" name="webpage" method="post" action="<?=PAGE_PUBLISH?>" enctype="multipart/form-data">
				<input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
				<input type="hidden" name="action" value="event" />
                <input type="hidden" name="section" value="<?=$_GET['section']?>" />

				<table border="0" cellspacing="0" cellpadding="0" >
				  <tr>
					<td width="785" valign="top" class="formBody">
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
						<tr>
						  <td class="pageTitleSub">Title					        </td>
						  <td class="pageTitleSub"><input type="text" name="calendar_events_title" id="calendar_events_title" class="textField-title" style="width:650px" value="<?=output($e['calendar_events_title'])?>" /></td>
						</tr>
						<tr>
						  <td colspan="2" class="pageTitleSub">Short description<br />
					      <span class="smallText" style="font-weight:normal;">This will appear on the homepage, it should be a brief explanation of the event.</span></td>
						</tr>
                        <tr>
						  <td colspan="2"><span class="pageTitleSub">
                          <textarea name="calendar_events_description_short" id="short_description" class="textField-title" style="width:700px; height:60px"><?=output($e['calendar_events_description_short'])?></textarea>
						    
						  </span></td>
					    </tr>
                        <tr>
                          <td colspan="2"><span class="pageTitleSub">Description</span></td>
                        </tr>
                       
                        <tr>
                          <td colspan="2"><? echo printEditorCB("calendar_events_description", "calendar_events_description", $e['calendar_events_description']); ?></td>
                        </tr>
                        
                       
                        
                        <tr>
                          <td colspan="2">
                          <!-- Since we are editing the event.. we want to give the person the ability to not change any recurring events.. as they would have to always reconfigure the recurring event. -->
                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                    <td  class="pageTitleSub" colspan="2">Date/Time</td>
                                </tr>
                              <tr>
                                <td width="10%"><strong> Date:</strong></td>
                                <td width="90%">
                                <input name="start_calendar" type="text" class="textField-title" id="start_calendar" value="<?=$startDate?>" />
                                Event spans to date
                                <input name="end_calendar" type="text" class="textField-title" id="end_calendar" value="<?=$endDate?>" /></td>
                              </tr>
                              <tr>
                                <td><strong>Time:</strong></td>
                                <td>
                                  
                                  From:
                                  <input type="text" name="time1" id="time1" class="textField-title" value="<?=$startTime?>" />
                                  To:
                                  <input type="text" name="time2" id="time2"  class="textField-title" value="<?=$endTime?>" /></td>
                              </tr>
                              <tr>
                                    <td  class="pageTitleSub" colspan="2">Recurring Event?</td>
                                </tr>
                            </table>
                              <div style="width:750px;">
                              <label><input type="radio" id="event_repeat_never" name="repeats" class="repeats" value="never" checked="checked" />Never</label>
                              <label><input type="radio" id="event_repeat_daily" name="repeats" class="repeats" value="daily"  />Daily</label>
                              <label><input type="radio" id="event_repeat_weekly" name="repeats" class="repeats" value="weekly" />Weekly</label> 
                              <label><input type="radio" id="event_repeat_monthly" name="repeats" class="repeats" value="monthly" />Monthly</label> 
                              <div class="spacer"></div>
                              <!-- Start Daily Repeat -->
                              <div class="events_tasks" id="dailyForm" style="display:none;">
                                  <div class="fieldLabel">Ends</div>
                                  <div class="fieldValue">
                                  <select name="daily_repeat_ends_on" class="textField-title" id="daily_repeat_ends_on">
                                    <option value="never">Never</option>
                                    <option value="on">On</option>
                                    <option value="after">After</option>
                                  </select>
                                  </div>  
                                  <div id="daily_repeat_on" style="display:none;">
                                     <div class="fieldLabel">Date</div>
                                     <div class="fieldValue"><input name="repeat_daily_on_day" type="text" class="textField-title" id="repeat_daily_on_day" value="" /></div>
                                  </div>
                                  <div id="daily_repeat_after" style="display:none;">
                                    <div class="fieldLabel">After</div>
                                    <div class="fieldValue"><input name="repeat_daily_after" type="text" class="textField-title" id="repeat_daily_after" style="width:50px;" />Events</div>
                                  </div>
                                  <div class="spacer"></div>
                              </div>
                              <!-- End Daily Repeat -->
                              
                               <!-- Start Weekly Repeat -->
                              <div class="events_tasks" id="weeklyForm" style="display:none;">
                                  <div class="fieldLabel">Ends</div>
                                  <div class="fieldValue">
                                   <select name="weekly_repeat_ends_on" class="textField-title" id="weekly_repeat_ends_on">
                                    <option value="on">On</option>
                                    <option value="after">After</option>
                                   </select>
                                  </div>
                              
                                 <div id="weekly_repeat_on" style="display:;">
                                   <div class="fieldLabel">Date</div>
                                   <div class="fieldValue"><input name="repeat_weekly_on_day" type="text" class="textField-title" id="repeat_weekly_on_day" value="" /></div>
                                 </div>
                                 <div id="weekly_repeat_after" style="display:none;">
                                  <div class="fieldLabel">After</div>
                                  <div class="fieldValue"><input name="repeat_weekly_after" type="text" class="textField-title" id="repeat_weekly_after" style="width:50px;" />Events</div>
                                 </div>
                                 <div class="spacer"></div>
                             </div>
                              <!-- End Weekly Repeat -->
                              
                              
                              <!-- Start Monthly Repeat -->
                              <div style="display:none" class="events_tasks" id="monthlyForm">
                              <div class="fieldLabel"><input name="event_monthly_repeat" type="radio" id="event_repeat_each" value="event_repeat_each" checked="checked" /><label for="event_repeat_each">Each</label></div>
                              <div class="fieldValue">
                                <input type="hidden" name="event_monthly_each_cal_date" id="event_monthly_each_cal_date" value=""  />
                                <div id="calendar_day_container">
                                <?
                                echo "<table>\n";
                                echo "<tr>\n";
                                $count=1;
                                for($i=1;$i<=31;$i++) {
                                    echo "<td><a href=\"javascript:void(0);\" class=\"monthly_day\">".$i."</a></td>\n";
                                
                                    if($count % 7 == 0) {
                                        echo "</tr>\n";
                                        echo "<tr>\n";
                                    }
                                    $count++;
                                }
                                echo "</tr>\n";
                                echo "</table>\n";
                                ?>
                                </div>
                              </div>
                              
                              <div class="fieldLabel">
                                 <input type="radio" name="event_monthly_repeat" id="event_repeat_each" value="event_repeat_onthe" class="textField-title" /><label for="radio">On the</label>
                              </div>
                              <div class="fieldValue">
                                  <select name="event_monthly_on_the_when" id="event_monthly_on_the_when" class="textField-title">
                                    <option value="first">First</option>
                                    <option value="second">Second</option>
                                    <option value="third">Third</option>
                                    <option value="fourth">Fourth</option>
                                    <option value="last">Last</option>
                                  </select>
                                  <select name="event_monthly_on_the_days" id="event_monthly_on_the_days" class="textField-title">
                                      <option value="sunday">Sunday</option>
                                      <option value="Monday">Monday</option>
                                      <option value="tuesday">Tuesday</option>
                                      <option value="wednesday">Wednesday</option>
                                      <option value="thursday">Thursday</option>
                                      <option value="friday">Friday</option>
                                      <option value="saturday">Saturday</option>
                                  </select>
                              </div>
                              <div class="fieldLabel">Ends</div>
                              <div class="fieldValue">
                               <select name="monthly_repeat_on" class="textField-title" id="monthly_repeat_on">
                                    <option value="on">On</option>
                                    <option value="after">After</option>
                                  </select>
                              </div>
                                <div id="monthly_repeat_ends_on">
                                    <div class="fieldLabel">Date</div>
                                    <div class="fieldValue"><input name="repeat_monthly_on_day" type="text" class="textField-title" id="repeat_monthly_on_day" value="" /></div>
                                </div>
                                <div id="monthly_repeat_after" style="display:none;">
                                    <div class="fieldLabel">After</div>
                                    <div class="fieldValue"><input name="repeat_monthly_after" type="text" class="textField-title" id="repeat_monthly_after" style="width:50px;" /> Events</div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            </div>
                            <!-- End Monthly Repeat -->
                          </td>
                        </tr>
					  </table>
					  <div class="mb20"></div>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
						<tr>
						  <td class=""><strong>Keyword Tags:</strong><br /><span class="smallText">Check off any that event falls under, these will appear on the homepage.</span></td>
						</tr>
						<tr>
						  <td>
                          <label style="font-weight:normal; font-size:10px;"><input type="checkbox" name="keywords[]" id="keyword_0" value="kickoff"  />Kickoff</label> 
                          <label style="font-weight:normal; font-size:10px;"><input type="checkbox" name="keywords[]" id="keyword_1" value="thursday"  />Thursday Events</label> 
                          <label style="font-weight:normal; font-size:10px;"><input type="checkbox" name="keywords[]" id="keyword_2" value="friday"  />Friday Events</label> 
                          <label style="font-weight:normal; font-size:10px;"><input type="checkbox" name="keywords[]" id="keyword_3" value="saturday"  />Saturday Event</label> 
                          <label style="font-weight:normal; font-size:10px;"><input type="checkbox" name="keywords[]" id="keyword_4" value="kids"  />Just for Kids</label> 
                          <label style="font-weight:normal; font-size:10px;"><input type="checkbox" name="keywords[]" id="keyword_5" value="adults" />Just for Adults</label> 
                          <label style="font-weight:normal; font-size:10px;"><input type="checkbox" name="keywords[]" id="keyword_6" value="santa" />Just Santa</label> 
                          
                          </td>
						</tr>
					  </table>
					   <div class="mb20"></div>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
					    <tr>
					      <td class="pageTitleSub"><span class="fieldLabel">Event Small Image</span> <span class="smallText">250px x 200px</span><input type="file" name="event_image" id="event_image" /></td>
				        </tr>
						
                        <?
						if($e['calendar_events_image'] != '') {
							echo "<tr>\n";
							echo "<td>\n";
							echo "<img src=\"".getThumbnailFilename(UPLOAD_DIR_URL.$e['calendar_events_image'], 'small')."\">\n";
							echo "</td>\n";
							echo "</tr>\n";
						}
						?>
                       
						 <tr>
					      <td class="pageTitleSub"><span class="fieldLabel">Event Main Image</span><span class="smallText">760px x 80px</span> <input type="file" name="event_main_image" id="event_main_image" /></td>
				        </tr>
                         <?
						if($e['calendar_events_main_image'] != '') {
							echo "<tr>\n";
							echo "<td>\n";
							echo "<img src=\"".getThumbnailFilename(UPLOAD_DIR_URL.$e['calendar_events_main_image'], 'small')."\">\n";
							echo "</td>\n";
							echo "</tr>\n";
						}
						?>
					  </table>
					  
					 
					  
					<div class="mb20"></div></td>
					<td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
					  <tr>
						<td>
						<div style="margin:0px 10px 10px 10px;">
						
						<table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
						  <tr>
							<td class="headerCell"><h2>Publish Event</h2></td>
						  </tr>
                          <tr>
						    <td><label><input type="checkbox" name="calendar_events_featured" id="calendar_events_featured" value="1" <? if($e['calendar_events_featured']) echo " checked"; ?> />Feature Event?</label></td>
					      </tr>
						  <tr>
						    <td><strong>Event Type</strong></td>
					      </tr>
						  <tr>
						    <td>
                            <select name="calendar_events_type" id="calendar_events_type">
						    	<option value="">Select one</option>
                                  <?
								$eventTypeResults = dbQuery('SELECT DISTINCT(calendar_events_type) FROM calendar_events');
								while($eInfo = dbFetchArray($eventTypeResults)){
									if($eInfo['calendar_events_type'] != '') { 
										echo "<option value=\"".$eInfo['calendar_events_type']."\"";
											if($e['calendar_events_type'] == $eInfo['calendar_events_type']) echo " selected";
										echo ">".$eInfo['calendar_events_type']."</option>\n";
									}
								}
								?>
                                <option value="new">New Type</option>
						    </select>
                            
                            <div id="new_event_type" style="display:none;">
                            <label>Enter in new type</label><br />
                            <input type="text" name="event_type_new" id="event_type_new" />
                            </div>
                            </td>
					      </tr>
						  <tr>
							<td><strong>Status</strong></td>
						  </tr>
						  <tr>
							<td><select name="calendar_events_status" id="calendar_events_status">
							  <option value="published" >Published</option>
							  <option value="pending" >Pending Review</option>
							  <option value="private" >Unpublished</option>
							</select></td>
						  </tr>
						 
						  <tr>
							<td><br />
                                <br />
                                <div id="recurringEventContainer"></div>
							
                            </td>
						  </tr>
						  <tr>
							<td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /> </td>
						  </tr>
						</table>
						
						</div>
						
						</td>
					  </tr>
					</table>      <p>&nbsp;</p></td>
				  </tr>
				</table></form>
                <script language="javascript" type="text/javascript">
                                $(document).ready(function() {
									var dateArray = new Array();
									function printLI() {
										$('#eventDates').html('');
										dateArray.sort();
										for(var i=0; i<=(dateArray.length-1); i++) {
											$('#eventDates').append('<li id="'+dateArray[i]+'">'+dateArray[i]+'</li>');
										}
									}
									
									$('.modRecur').bind('click', function() {
											if($(this).val() == 1) {
												$('#modifyRecurringEvent').slideDown();
											} else {
												$('#modifyRecurringEvent').slideUp();
											}
									});
									
									$('#event_monthly_on_the_when').change( function() {
										//we should set the radio button to the on the
										$('input[name=event_monthly_repeat]').attr('checked', 'checked');
									});
									$('#event_monthly_on_the_days').change( function() {
										$('input[name=event_monthly_repeat]').attr('checked', 'checked');
									});
									$('#calendar_day_container a').bind('click', function() {
										$('.monthly_day').removeClass('calActive');
										$(this).addClass('calActive');
									});
									$('#event_repeat_never').bind('click', function() {
										$('#dailyForm').hide();
										$('#weeklyForm').hide();
										$('#monthlyForm').hide();
									});
									
									$('#event_repeat_daily').bind('click', function() {
										//Display daily_repeat_on div
										$('.events_tasks').hide();
										$('#dailyForm').show();
									});
									$('#daily_repeat_ends_on').bind('change', function() {
										//If endsd soething.
										if($(this).val() == 'never') {
											//display calendar box.
											$('#daily_repeat_on').hide();
											$('#daily_repeat_after').hide();
										}
										if($(this).val() == 'on') {
											//display calendar box.
											$('#daily_repeat_on').show();
											$('#daily_repeat_after').hide();
										}
										if($(this).val() == 'after') {
											//display textbox 
											$('#daily_repeat_on').hide();
											$('#daily_repeat_after').show();
										}
									});
									
									$('#weekly_repeat_ends_on').bind('change', function() {
										//If endsd soething.
										if($(this).val() == 'on') {
											//display calendar box.
											$('#weekly_repeat_on').show();
											$('#weekly_repeat_after').hide();
										}
										if($(this).val() == 'after') {
											//display textbox 
											$('#weekly_repeat_on').hide();
											$('#weekly_repeat_after').show();
										}
									});
									
									$('#event_repeat_weekly').bind('click', function() {
										$('.events_tasks').hide();
										$('#weeklyForm').show();
										$('#weekly_repeat_on').bind('change', function() {
											if($(this).val() == 'on') {
												$('#weekly_repeat_ends_on').show();
												$('#weekly_repeat_after').hide();
											} 
											if($(this).val() == 'after') {
												$('#weekly_repeat_ends_on').hide();
												$('#weekly_repeat_after').show();
											}
										});
									});
									
									$('#event_monthly_each_cal').datepicker({ altField: 'event_monthly_each_cal_date' });
									
									$('#event_repeat_monthly').bind('click', function() {
										$('.events_tasks').hide();
										$('#monthlyForm').show();
										$('#monthly_repeat_on').bind('change', function() {
											if($(this).val() == 'on') {
												$('#monthly_repeat_ends_on').show();
												$('#monthly_repeat_after').hide();
											} 
											if($(this).val() == 'after') {
												$('#monthly_repeat_ends_on').hide();
												$('#monthly_repeat_after').show();
											}
										});
									});
									
									$('.monthly_day').bind('click', function() {
										$('#event_monthly_each_cal_date').val($(this).text());
									});
									
									
									$("#time2").timePicker({
									  startTime: "06.00", // Using string. Can take string or Date object.
									  endTime: "23.00", // Using Date object here.
									  show24Hours: false,
									  separator: '.',
									  step: 15
									 });
									$("#time1").timePicker({
									  startTime: "06.00", // Using string. Can take string or Date object.
									  endTime: "23.00", // Using Date object here.
									  show24Hours: false,
									  separator: '.',
									  step: 15
									 });
									  
									$('#start_calendar').datepicker({
										showOn: 'button',
										buttonImage: 'images/icons/calendar2.gif',
										buttonImageOnly: true,
										onSelect: function(dateText) {
											var eleID = str_replace(dateText, '/', '_');
											var rawNum = str_replace(dateText,'/','');
											
											//alert($.inArray(rawNum, dateArray));
											
											var selected = $.datepicker.parseDate('mm/dd/yy', dateText);
											//alert($('#eventDates li').length);
											if( dateArray.length != 0) {
												var keyToRemove = $.inArray(rawNum, dateArray);
												console.log(keyToRemove);
												if(keyToRemove != -1) { 
													//alert('found in array');
													dateArray.splice(keyToRemove, 1);
													printLI();
												} else {
													dateArray.push(rawNum);
													printLI();
												}
												//$('#'+eleID).remove();
											} else {
												//$('#eventDates').append('<li id="'+eleID+'">'+dateText+'</li>');
												dateArray.push(rawNum);
												printLI();
											}
											
										},
										beforeShowDay: function(date) {
											var selected;
											
											if(selected != null && date.getTime() > selected.getTime() && (date.getTime() - selected.getTime()) < 12*24*3600*1000) {
												return [true, 'ui-state-active'];
											}
											return [true, ''];
										}
									});
									$('#end_calendar').datepicker({ showOn: 'button', buttonImage: 'images/icons/calendar2.gif', buttonImageOnly: true });
									$('#repeat_daily_on_day').datepicker({ showOn: 'button', buttonImage: 'images/icons/calendar2.gif', buttonImageOnly: true });
									$('#repeat_weekly_on_day').datepicker({ showOn: 'button', buttonImage: 'images/icons/calendar2.gif', buttonImageOnly: true });
									$('#repeat_monthly_on_day').datepicker({ showOn: 'button', buttonImage: 'images/icons/calendar2.gif', buttonImageOnly: true });
									
									$('#repeat_daily_on_day').mask('99/99/9999');
									$('#repeat_weekly_on_day').mask('99/99/9999');
									$('#repeat_monthly_on_day').mask('99/99/9999');
									
									$('#publish_date').mask('99/99/9999');
									$('#publish_date_end').mask('99/99/9999');
									
								});
                             </script>
<?
break;
case 'blog':
	default:
						?>
				<form id="event" name="webpage" method="post" action="<?=PAGE_PUBLISH?>" enctype="multipart/form-data">
				<input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
				
                <input type="hidden" name="action" value="<?=$_GET['section']?>" />
                <table border="0" cellspacing="0" cellpadding="0" >
				  <tr>
					<td width="785" valign="top" class="formBody">
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
						<tr>
						  <td class="pageTitleSub">Title</td>
						</tr>
						<tr>
						  <td><input type="text" name="title" id="title" class="textField-title" style="width:650px" value="" /></td>
						</tr>
                        <tr>
						  <td class="pageTitleSub">Snippet<br /><span class="smallText">This copy is placed on a blog listing, it's more of  breakdown of the blog post. You can just paste int the first paragraph of the main post</span></td>
						</tr>
						<tr>
						  <td><textarea name="snippet" id="snippet" style="width:650px; height:50px;"></textarea></td>
						</tr>
                        <tr>
						  <td class="pageTitleSub">Blog Post Image <span class="smallText" style="font-weight:normal;">(400x400 pixels recommended)</span></td>
						</tr>
						<tr>
						  <td><input type="file" name="file" id="file" class="textField-title" style="width:650px" /></td>
						</tr>
                        <tr>
						  <td class="pageTitleSub">Podcast File <span class="smallText" style="font-weight:normal;">(.mp3 format required.)</span></td>
						</tr>
						<tr>
						  <td><input type="file" name="podcast" id="podcast" class="textField-title" style="width:650px" /></td>
						</tr>
						<tr>
						  <td class="pageTitleSub">Content</td>
						</tr>
						<tr>
						  <td>
                          <? echo printEditorCB("content", "content", ""); ?>
                         </td>
						</tr>
					  </table>
					 <div class="mb20"></div>
                     <div id="seo_options_toggle">
                            <table width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                           	 <td width="29"><img id="" width="29" height="28" src="images/sectionTitleArrow.jpg"/></td>
                            	<td class="sectionTitle">SEO Options <span class="smallText">(optional)</span></td>
                           	 </tr>
                         
                            </table>
                            </div>
                          <div class="expandable" id="page_seo">
                          <div class="pageTitleSub">SEO Options</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class=""><strong>Tag Post (comma seperated):</strong></td>
                            </tr>
                            <tr>
                              <td class="fieldLabel"><input type="text" name="seo_keywords" id="seo_keywords" class="textField-title" style="width:600px"  /></td>
                            </tr>
                          </table>
                          </div>
                          
                          
                          <div class="mb20"></div>
                            <div id="author_toggle">
                            <table width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                           	 <td width="29"><img id="" width="29" height="28" src="images/sectionTitleArrow.jpg"/></td>
                            	<td class="sectionTitle">Author<span class="smallText">(optional)</span></td>
                           	 </tr>
                         
                            </table>
                            </div>
                          <div class="expandable" id="page_author">
                          <div class="pageTitleSub">Author</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel">
                             
                              <select name="author" class="textField-title" id="author">
                                <?
							  $authorResults =dbQuery("SELECT ui.*, u.* FROM user AS u, user_information AS ui WHERE ui.user_id = u.user_id");
							  while($aInfo = dbFetchArray($authorResults)) {
									echo "<option value=\"".$aInfo['user_id']."\"";
										
									echo ">".$aInfo['user_first_name']." ".$aInfo['user_last_name']."</option>\n";
							  }
							  ?>
                              </select>
                               
                              </td>
                            </tr>
                          </table>
                          </div>
					<div class="mb20"></div></td>
					<td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
					  <tr>
						<td>
						<div style="margin:0px 10px 10px 10px;">
						<table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
						  <tr>
							<td class="headerCell"><h2>Publish Blog Post</h2></td>
						  </tr>
						  <tr>
							<td><strong>Status</strong></td>
						  </tr>
						  <tr>
							<td><select name="status" id="status">
							  <option value="published" >Published</option>
							  <option value="pending" >Pending Review</option>
							  <option value="private" >Unpublished</option>
							</select></td>
						  </tr>
						 
						  <tr>
							<td>
							<strong>Publish Date</strong><br />
							<div id="calendarContainer" style="padding:5px 0px;" align="center"></div>
							<input type="text" name="publish_date" id="publish_date" value="<?=date('m/d/Y')?>" readonly="readonly" />
							<div class="mt10"></div>
							
							</td>
						  </tr>
						  <tr>
							<td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /> </td>
						  </tr>
						</table>
						
						</div>
						
						</td>
					  </tr>
					</table></td>
				  </tr>
				</table></form>
                <script language="javascript">
						$(document).ready(function(){
						
							$("#calendarContainer").datepicker(
							{
								altField: '#publish_date',
								altFormat: 'mm/dd/yy',
								defaultDate: new Date('<?=date('m/d/Y')?>')
							}
							);
						  $("#calendarContainerEnd").datepicker(
							{
								altField: '#publish_date',
								altFormat: 'mm/dd/yy',
								defaultDate: new Date('<?=date('m/d/Y')?>')
							}
							);
							
						});
						</script>
				<?
break;
case 'class':
	
break;
case 'article':

					?>
    				<form id="article" name="article" method="post" action="<?=PAGE_PUBLISH?>">
                    <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                    <input type="hidden" name="action" value="article" />
                    <table width="900" border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="650" valign="top" class="formBody">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="pageTitleSub">Title</td>
                            </tr>
                            <tr>
                              <td><input type="text" name="title" id="title" class="textField-title" style="width:600px" /></td>
                            </tr>
                            <tr>
                              <td class="pageTitleSub">Article URL</td>
                            </tr>
                            <tr>
                              <td>http://yoursite.com/article/ 
                                <input type="text" name="url" id="url" class="textField-title" style="width:500px" readonly="readonly" /></td>
                            </tr>
                            <tr>
                              <td class="pageTitleSub">Content</td>
                            </tr>
                            <tr>
                              <td>
                               <? echo printEditorCB("content", "content",''); ?></td>
                            </tr>
                          </table>
                          <div class="mb20"></div>
                          <div class="expandable">
                          <div class="pageTitleSub">SEO Options</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel">Title</td>
                            </tr>
                            <tr>
                              <td><input type="text" name="seo_title" id="seo_title" class="textField-title" style="width:600px" /></td>
                            </tr>
                            <tr>
                              <td class="fieldLabel">Description (max characters 160)</td>
                            </tr>
                            <tr>
                              <td><input type="text" name="seo_description" id="seo_description" class="textField-title" style="width:600px" />
                                <br />
                                <strong>Character Count: 
                                <input name="textfield5" type="text" class="textField-title" id="textfield5" style="width:30px;" />
                                </strong></td>
                            </tr>
                            <tr>
                              <td class="fieldLabel">Keywords (comma seperated):</td>
                            </tr>
                            <tr>
                              <td class="fieldLabel"><input type="text" name="seo_keywords" id="seo_keywords" class="textField-title" style="width:600px" /></td>
                            </tr>
                          </table>
                          </div>
                          
                           <div class="mb20"></div>
                          <div class="expandable">
                          <div class="pageTitleSub">Page Order</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel"><strong>
                                <input name="sort_order" type="text" class="textField-title" id="sort_order" style="width:30px;" />
                              </strong></td>
                            </tr>
                          </table>
                          </div>
                          
                         <div class="mb20"></div>
                          <div class="expandable">
                          <div class="pageTitleSub">Page Author</div>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel"> <select name="author" class="textField-title" id="author">
                               <?
							  $authorResults =dbQuery("SELECT ui.*, u.* FROM user AS u, user_information AS ui WHERE ui.user_id = u.user_id AND u.client_id = " . $_SESSION['client']);
							  while($aInfo = dbFetchArray($authorResults)) {
									echo "<option value=\"".$aInfo['user_id']."\"";
										if($_SESSION['user_id'] == $aInfo['user_id'])
											echo " selected";
									echo ">".$aInfo['user_first_name']." ".$aInfo['user_last_name']."</option>\n";
							  }
							  ?>
                              </select></td>
                            </tr>
                          </table>
                          </div>
                          
                        <div class="mb20"></div></td>
                        <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                          <tr>
                            <td>
                            <div style="margin:0px 10px 10px 10px;">
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                              <tr>
                                <td class="headerCell"><h2>Publish Article</h2></td>
                              </tr>
                              <tr>
                                <td><strong>Status</strong></td>
                              </tr>
                              <tr>
                                <td><select name="status" id="status">
                                  <option value="published" selected="selected">Published</option>
                                  <option value="pending">Pending Review</option>
                                  <option value="private">Unpublished</option>
                                </select></td>
                              </tr>
                             
                              <tr>
                                <td><strong>Sticky Article?</strong>
                                  <input type="checkbox" name="sticky" id="sticky" value="1" />
                                  <br />
                                  <span class="smallText">(sticky articles always remain first on the list and are only removed if you remove the sticky)</span>
                                  <div class="mt10"></div>
                                <strong>Publish on:</strong>
                                  <?=date("F j, Y, g:i a", time())?> <a href="javascript:void(0);" id="toggleDate" style="font-size:10px; font-weight:normal; color:#CC0000;">(change)</a>
								  <div id="publishDate" style="display:none;">
                                  <div id="calendarContainer"></div>
                                  <div class="mt10"></div>
                                  <input type="text" name="publish_date" id="publish_date" value="<?=date('d/m/Y', time())?>" readonly="readonly" />
                                  </div>
                                  
                                
                                </td>
                              </tr>
                              <tr>
                                <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /></td>
                              </tr>
                            </table>
                              
                            </div>
                            
                            </td>
                          </tr>
                        </table></td>
                      </tr>
  					</table></form>
    				<?
break;
case 'webpage':
?>
                    <form id="webpage" name="webpage" method="post" action="<?=PAGE_PUBLISH?>">
                    <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                    <input type="hidden" name="action" value="webpage" />
                    <table  border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="650" valign="top" class="formBody">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                   
                            <td class="pageTitleSub">Title</td>
                            </tr>
                            <tr>
                              <td><input type="text" name="title" id="title" class="textField-title" style="width:600px" value="<?=output($p['page_content_title'])?>" /></td>
                            </tr>
                           
                            <tr>
								  <td class="pageTitleSub">Page Image <input name="page_content_image" type="file" class="textField-title" id="page_content_image" /><Span class="smallText"> Size: 620px x 200px</Span>
                                    <?php
							if($p['page_content_image'] != '') {
								echo "<br /><span class=\"smallText\">Current: " . $p['page_content_image']." <a href=\"".PAGE_MANAGE."&action=deletepageimage&id=".$_GET['id']."\">(delete)</a></span>";	
							}
							?>
                                  
                                  </td>
							</tr>
                            
                          
                            <tr>
                              <td class="pageTitleSub">Content</td>
                            </tr>
                            <tr>
                              <td>
                               <? echo printEditorCB("content", "content",''); ?>
                              </td>
                            </tr>
                          </table>
                          <div class="mb20"></div>
						   
                          <div id="seo_options_toggle">
                            <table width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                           	 <td width="29"><img id="" width="29" height="28" src="images/sectionTitleArrow.jpg"/></td>
                            	<td class="sectionTitle">Advanced Options</td>
                           	 </tr>
                         
                            </table>
                            </div>
                          <div class="expandable" id="page_seo">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel">Map to a custom URL</td>
                            </tr>
                            <tr>
                              <td><span class="fieldLabel">
                                <input type="text" name="custom_url" id="custom_url" class="textField-title" style="width:600px"  />
                                </span></td>
                            </tr>
                            <tr>
                              <td><label>Show in main navigation?<input type="checkbox" name="page_content_show_in_menu" id="page_content_show_in_menu" checked="checked" value="1" /></label></td>
                            </tr>
                            <tr>
                              <td class="fieldLabel">Title</td>
                            </tr>
                            <tr>
                              <td><input type="text" name="seo_title" id="seo_title" class="textField-title" style="width:600px" /></td>
                            </tr>
                            <tr>
                              <td class="fieldLabel">Description (max characters 160)</td>
                            </tr>
                            <tr>
                              <td><input type="text" name="seo_description" id="seo_description" class="textField-title" style="width:600px" />
                                <br />
                                <strong>Character Count: 
                                <input name="textfield5" type="text" class="textField-title" id="textfield5" style="width:30px;" />
                                </strong></td>
                            </tr>
                            <tr>
                              <td class="fieldLabel">Keywords (comma seperated):</td>
                            </tr>
                            <tr>
                              <td class="fieldLabel"><input type="text" name="seo_keywords" id="seo_keywords" class="textField-title" style="width:600px" /></td>
                            </tr>
                          </table>
                          </div>
                           <div class="mb20"></div>
                            <div id="order_toggle">
                            <table width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                           	 <td width="29"><img id="" width="29" height="28" src="images/sectionTitleArrow.jpg"/></td>
                            	<td class="sectionTitle">Page Order</td>
                           	 </tr>
                         
                            </table>
                            </div>
                          <div class="expandable" id="page_order">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel"><strong>
                                <input name="sort_order" type="text" class="textField-title" id="sort_order" style="width:30px;" />
                              </strong></td>
                            </tr>
                          </table>
                          </div>
                         <div class="mb20"></div>
                          <div id="author_toggle">
                            <table width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                           	 <td width="29"><img id="" width="29" height="28" src="images/sectionTitleArrow.jpg"/></td>
                            	<td class="sectionTitle">Author</td>
                           	 </tr>
                         
                            </table>
                            </div>
                          <div class="expandable" id="page_author">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel"> <select name="author" class="textField-title" id="author">
                               <?
							  $authorResults =dbQuery("SELECT ui.*, u.* FROM user AS u, user_information AS ui WHERE ui.user_id = u.user_id");
							  while($aInfo = dbFetchArray($authorResults)) {
									echo "<option value=\"".$aInfo['user_id']."\"";
										if($_SESSION['user_id'] == $aInfo['user_id'])
											echo " selected";
									echo ">".$aInfo['user_first_name']." ".$aInfo['user_last_name']."</option>\n";
							  }
							  ?>
                              </select></td>
                            </tr>
                          </table>
                          </div>
                          
                           <div class="mb20"></div>
                            <div id="parent_toggle">
                            <table width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                           	 <td width="29"><img id="" width="29" height="28" src="images/sectionTitleArrow.jpg"/></td>
                            	<td class="sectionTitle">Parent</td>
                           	 </tr>
                         
                            </table>
                            </div>
                          <div class="expandable" id="page_parent">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="fieldLabel">
                             
                              
                              
                               <input type="hidden" name="parent" id="parent" value="<?=$_GET['parent']?>" />
                                    <dl id="parent_page" class="select_dropdown">
										<?php
                                        recurse_navigation_li_top($_GET['parent']);
                                        recurse_navigation_li($_GET['parent']);
                                        recurse_navigation_li_botttom();
                                        ?>
                                    </dl>
                           
                              </td>
                            </tr>
                          </table>
                          </div>
                        </td>
                        <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                          <tr>
                            <td>
                            <div style="margin:0px 10px 10px 10px;">
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                              <tr>
                                <td class="headerCell"><h2>Publish Page</h2></td>
                              </tr>
                              <!--
                              <tr>
                                <td><label><strong>Requires Membership?</strong> 
                                    <input name="member" type="checkbox" id="member" value="1" />
                                </label></td>
                              </tr>
                              -->
                              
                              <tr>
                                <td><strong>Status</strong></td>
                              </tr>
                              <tr>
                                <td><select name="status" id="status">
                                  <option value="published" selected="selected">Publish</option>
                                  <option value="pending">Pending Review</option>
                                  <option value="private">Unpublished</option>
                                </select></td>
                              </tr>
                             
                              <tr>
                                <td><strong>Publish on:</strong><br />
                                  <?=date("F j, Y, g:i a", time())?> <a href="javascript:void(0);" id="toggleDate" style="font-size:10px; font-weight:normal; color:#CC0000;">(change)</a>
								  <div id="publishDate" style="display:none;">
                                  <div id="calendarContainer" style="padding:5px 0px;"></div>
                                  <div class="mt10"></div>
                                  <input type="text" name="publish_date" id="publish_date" value="<?=date('d/m/Y', time())?>" readonly="readonly" />
                                  </div>
                                  
                                
                                </td>
                              </tr>
                              <tr>
                                <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /></td>
                              </tr>
                            </table>
                              <br /><br />
                            </div>
                            
                            </td>
                          </tr>
                        </table>      <p>&nbsp;</p></td>
                      </tr>
  					</table></form>
<?
	break;
	
	case 'downloadables':
						
							?>
                            <form action="<?=PAGE_PUBLISH?>" method="post" enctype="multipart/form-data" name="banners" id="banners">
                            <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                            <input type="hidden" name="action" value="downloadables" />
                            <table  border="0" cellspacing="0" cellpadding="0" >
                              <tr>
                                <td width="650" valign="top" class="formBody">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td class="pageTitleSub">File Name</td>
                                    </tr>
                                    <tr>
                                      <td><input type="text" name="page_downloads_title" id="page_downloads_title" class="textField-title" style="width:600px" value="<?=output($dInfo['page_downloads_title'])?>" /></td>
                                    </tr>
                                   
                                    <tr>
                                      <td class="pageTitleSub">File</td>
                                    </tr>
                                    <tr>
                                      <td><input type="file" name="file" id="file" /></td>
                                    </tr>
                                  </table>
                                  <div class="mb20"></div>
                                 </td>
                                <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                                  <tr>
                                    <td>
                                    <div style="margin:0px 10px 10px 10px;">
                                    
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                      <tr>
                                        <td class="headerCell"><h2>Save File</h2></td>
                                      </tr>
                                      <tr>
                                        <td><strong>File Category</strong>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>
                                          <select name="page_downloads_category" id="page_downloads_category">
                                            <?
											$cResults = dbQuery('SELECT * FROM page_downloads_category AND client_id = ' . $_SESSION['client'] . ' ORDER BY page_downloads_category_title');
											while($c=dbFetchArray($cResults))
											{
											echo "<option value=\"".$c['page_downloads_category_id']."\"";
												
												echo ">".output($c['page_downloads_category_title'])."</option>\n";	
											}
											?>
                                          </select>
                                                                            
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /> </td>
                                      </tr>
                                    </table>
                                    
                                    </div>
                                    
                                    </td>
                                  </tr>
                                </table></td>
                              </tr>
                            </table></form>
               <?
	break;
}
?>


</div>
</body>
</html>
