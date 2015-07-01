<?
include('master.inc.php');
include('application.php');

if(!$_SESSION['user_logged_in'] || !isset($_SESSION['client'])){
	addError('Your session has timed out please login again');
	redirect(PAGE_LOGIN);
}
$trail = new breadcrumb();//start breadcrumb class
$trail->add("Dashboard", PAGE_DEFAULT);
$trail->add("Manage", "");
//save the differet types of content here =) 
switch($_POST['action']){
	case 'add':
	case 'downloadables_category':
		$row['page_downloads_category_title'] = input($_POST['title']);
		dbPerform('page_downloads_category', $row, 'insert');
		addMessage('Added category successfully');
		redirect(PAGE_MANAGE.'?section=downloadables');
	break;
	case 'update':
		switch($_POST['section']) {
		case 'downloadables_category':
			$row['page_downloads_category_title'] = input($_POST['title']);
			dbPerform('page_downloads_category', $row, 'update', 'page_downloads_category_id = ' . $_POST['id']);
			addMessage('Saved category successfully');
			redirect(PAGE_MANAGE.'?section=downloadables');
		break;
		
		case 'downloadables':
		
			$row['page_downloads_title'] = input($_POST['page_downloads_title']);
			$row['page_downloads_category'] = $_POST['page_downloads_category'];
			$row['page_downloads_id'] = time();
			if($_FILES['file']['name'] != "" ){ 
				$filename = fixFilename($_FILES['file']['name']);
				uploadDownloadable($_FILES['file'], $filename);
				$row['page_downloads_filename'] = $filename;
			}
			dbPerform('page_downloads', $row, 'update', 'page_downloads_id = '.$_POST['id']);
			addMessage("Saved file successfully");
			redirect(PAGE_MANAGE."?section=downloadables");
				
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
					
				dbPerform('testimonials', $row,'update','testimonials_id = ' . $_POST['id']);
				addMessage("Updated testimonial successfully");
				redirect(PAGE_MANAGE."?section=testimonials");
				
		break;
		case 'prayerrequests':
				$row['name'] = input($_POST['name']);
				$row['text'] = input($_POST['text']);
				$row['approved'] = $_POST['approved'];
				
				dbPerform('request_prayer', $row, 'update', 'id = ' . $_POST['id']);
				addMessage("Updated prayer request successfully");
				redirect(PAGE_MANAGE.'?section=prayerrequests');
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
					$row['page_content_added'] 				= time();
					$row['page_content_status'] 			= $_POST['status'];
					$row['parent'] 							= ($_POST['parent']) ? $_POST['parent'] : 0;
					$row['page_content_member']				= ($_POST['member']) ? $_POST['member'] : 0;
					$row['page_content_custom_1']			= $_POST['page_content_custom_1']; //ipipeline
					$row['page_content_last_modified_date'] = time();
					$row['custom_url']						= $_POST['custom_url'];
					$row['page_content_show_in_menu']		= $_POST['page_content_show_in_menu'];
					
					//create page url. :P
					$pageurl = create_page_url($_POST['title']);
					$row['page_content_url'] 				= $pageurl;
					
					
					$row['page_content_form'] = $_POST['page_content_form'];
					
					dbQuery('DELETE FROM page_content_downloads WHERE page_content_id = ' . $_POST['id']);
					
					if(isset($_POST['downloadables'])) {
						foreach($_POST['downloadables'] as $key => $val) {
							$dls['page_downloads_id'] = $val;
							$dls['page_content_id'] = $_POST['id'];
							$dls['page_content_downloads_sort'] = $_POST['page_downloads_sort'][$val];
							dbPerform('page_content_downloads', $dls, 'insert');
							
						}
					}
					
					
					if($_FILES['page_content_image']['name'] != "") {
						//upload da file =)
						$filename = time()."_".$_FILES['page_content_image']['name'];
						uploadFile($_FILES['page_content_image'], $filename);
						makeThumbnail($filename, UPLOAD_DIR, 620, '', 'main');
						$row['page_content_image'] = $filename;
					}
					
					
					//add to change log
					$change['page_content_id'] = $_POST['id'];
					$change['user_id'] = $_SESSION['user_id'];
					$change['page_content_log_timestamp'] = time();
					
					dbPerform('page_content_log', $change, 'insert');
					//end adding to log
					
					
					dbPerform('page_content', $row, 'update', 'page_content_id = ' . $_POST['id']);
					addMessage("Page updated successfully");
					
					redirect(PAGE_MANAGE."?section=webpage");
	
	
	break;
	case 'alerts':
					$row['alerts_content'] = input($_POST['content']);
					$row['alerts_title'] = input($_POST['title']);
					
					dbPerform('alerts', $row, 'update', 'alerts_id = ' . $_POST['id']);
					addMessage("Saved alert successfully");
					redirect(PAGE_MANAGE."?section=alerts");
					
	break;
	case 'banners':
					$row['banner_title'] = input($_POST['banner_title']);
					$row['banner_url'] = input($_POST['banner_url']);
					$row['banner_url_target'] = input($_POST['banner_url_target']); //either self or new window
					$row['banners_category'] = $_POST['banners_category'];
					$row['banner_publish_date'] = strtotime($_POST['publish_date']);
					
					if($_FILES['banner']['name'] != "" ){ 
						$filename = fixFilename($_FILES['banner']['name']);
						uploadBanner($_FILES['banner'], $filename);
						$row['banner_filename'] = $filename;
					}
					
					
					dbPerform('banners', $row, 'update', 'banner_id = ' . $_POST['id']);
					addMessage("Saved banner advertisement successfully");
					redirect(PAGE_MANAGE."?section=banners");
					
					
	break;
	case 'gallery':
					$row['gallery_desc'] = input($_POST['desc']);
					$row['gallery_title'] = input($_POST['title']);
					$row['gallery_sort'] = $_POST['sort_order'];
					$row['gallery_date_added'] = strtotime($_POST['publish_date']);
					$row['gallery_custom_1'] = $_POST['gallery_custom_1'];
					$row['gallery_custom_2'] = $_POST['gallery_custom_2'];
					$row['gallery_custom_3'] = $_POST['gallery_custom_3'];
					$row['gallery_custom_4'] = $_POST['gallery_custom_4'];
					$row['gallery_custom_5'] = $_POST['gallery_custom_5'];
					
					if($_FILES['video_file']['name'] != "" ) { //uploading flash file
						$videoName = fixFilename($_FILES['video_file']['name']);
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
					$row['gallery_feature'] = $_POST['gallery_feature'];
					
					dbPerform('gallery', $row, 'update', 'gallery_id = ' . $_POST['gallery_id']);
					addMessage("Updated ".output($_POST['title'])." successfully");
					redirect(PAGE_MANAGE."?section=gallery");
	
	break;
	case 'article':
					$publish = explode('/', $_POST['publish_date']);
					$publishDate = mktime(0,0,0, $publish[0], $publish[1], $publish[2]);
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
					
					dbPerform('articles', $article,'update', 'article_id = ' . $_POST['id']);
					addMessage("Article updated successfully");
					
					redirect(PAGE_MANAGE."?section=article");
	break;
	case 'sponsors':
	
					$row['sponsor_name'] = $_POST['sponsor_name'];
					$row['sponsor_url'] = $_POST['sponsor_url'];
					$row['sponsor_desc'] = $_POST['sponsor_desc'];
					$row['sponsor_short_desc'] = $_POST['sponsor_short_desc'];
					$row['sponsor_level'] = $_POST['sponsor_level'];
					
					if($_FILES['sponsor_logo']['name'] != '') {
						$filename = $_POST['sponsor_name']."_".$_FILES['sponsor_logo']['name'];
						uploadFile($_FILES['sponsor_logo'], $filename);
						makeThumbnail($filename, UPLOAD_DIR, 150, '', 'small');
						makeThumbnail($filename, UPLOAD_DIR, 50, '', 'xsmall');
						makeThumbnail($filename, UPLOAD_DIR, 250, '', 'large');
						$row['sponsor_logo'] = $filename;
					}
					
					dbPerform('sponsors', $row, 'update', 'sponsor_id = ' . $_POST['id']);
					addMessage("Added sponsor successfully");
					redirect('manage.php?section=sponsors');
					
					
	break;
	case 'event':
					include('functions/functions.events.php');
					//Save event like we used to.. but at the same time we are going to add it's events in the ehhhh calendar_event_items table...
					//$event['calendar_events_url'] = $_POST['url'];
					$event['calendar_events_title'] = input($_POST['calendar_events_title']);
					$event['calendar_events_description'] = input($_POST['calendar_events_description']);
					$event['calendar_events_status'] = $_POST['calendar_events_status'];
					$event['calendar_events_description_short'] = input($_POST['calendar_events_description_short']);
					if($_POST['event_type_new'] != '') {
						$event['calendar_events_type'] = $_POST['event_type_new'];
					} else {
						$event['calendar_events_type'] = $_POST['calendar_events_type'];
					}
					
					$event['author'] = $_SESSION['user_id'];
					$event['calendar_events_status'] = $_POST['calendar_events_status'];
					//$event['calendar_events_date_added'] = time();
					$event['calendar_events_featured'] = $_POST['calendar_events_featured'];
					if($_POST['modifyRecurring'] == 1) {
						if($_POST['repeats'] == 'never') {
							$event['calendar_events_repeats'] = 0;
						} else {
							$event['calendar_events_repeats'] = 1;	
						}
					}
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
					dbPerform('calendar_events', $event, 'update', 'calendar_events_id = ' . $_POST['id']);
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
					
					
					if($_POST['modifyRecurring'] == 1) {
						$event['calendar_events_start_date'] = mktime(12,0,0, date('m', strtotime($_POST['start_calendar'])),  date('d', strtotime($_POST['start_calendar'])),  date('Y', strtotime($_POST['start_calendar'])));
						$event['calendar_events_end_date'] = mktime(12,0,0, date('m', strtotime($_POST['end_calendar'])),  date('d', strtotime($_POST['end_calendar'])),  date('Y', strtotime($_POST['end_calendar'])));
						$event['calendar_events_start_time'] = strtotime($_POST['start_calendar'].' '.$_POST['time1']);
						$event['calendar_events_end_time'] = strtotime($_POST['start_calendar'].' '.$_POST['time2']);
						dbPerform('calendar_events', $event, 'update', 'calendar_events_id = ' . $_POST['id']);
						addEventItems($_POST);
					}
					
					addMessage("Event updated successfully");
					redirect(PAGE_MANAGE."?section=event");
					
	
	break;
	case 'news':
	
					$publish = explode('/', $_POST['publish_date']);
					$publishDateStart = mktime(0,0,0, $publish[0], $publish[1], $publish[2]);
					
					
					
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
					$news['news_type'] = $_POST['news_type'];
					
					dbPerform('news', $news, 'update', 'news_id = ' . $_POST['id']);
					
					addMessage("News updated successfully");
					redirect(PAGE_MANAGE."?section=news");
	
	break;
	
	case 'blog':
	
					$publish = explode('/', $_POST['publish_date']);
					$publishDateStart = mktime(0,0,0, $publish[0], $publish[1], $publish[2]);
					
					
					
					$blog['title'] = input($_POST['title']);
					$blog['content'] = $_POST['content'];
					$blog['publish_date'] = $publishDateStart;
					$blog['author'] = $_POST['author'];
					$blog['snippet'] = $_POST['snippet'];
					$blog['status'] = $_POST['status'];
					
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
					dbPerform('blog', $blog, 'update', 'id = ' . $_POST['id']);
					
					addMessage("Blog post updated successfully");
					redirect(PAGE_MANAGE."?section=blog");
	
	break;
	case 'podcasts':
					$publish = explode('/', $_POST['pod_casts_publish_date']);
					$publishDateStart = mktime(0,0,0, $publish[0], $publish[1], $publish[2]);
					
					
					$row['pod_casts_url'] = input($_POST['pod_casts_url']);
					$row['pod_casts_title'] = input($_POST['pod_casts_title']);
					$row['pod_casts_desc'] = $_POST['pod_casts_desc'];
					$row['pod_casts_publish_date'] = $publishDateStart;
					$row['pod_casts_url'] = $_POST['pod_casts_url'];
					$row['pod_casts_date_updated'] = time();
					$row['pod_casts_author'] = $_SERVER['user_id'];
					$row['pod_casts_status'] = input($_POST['pod_casts_status']);
					//$row['pod_casts_feature'] = $_POST['feature'];
					if($_POST['feature'] == '1') {
						//delete existing and make this one featured.
						dbQuery('UPDATE pod_casts SET pod_casts_feature = 0');
						$row['pod_casts_feature'] = 1;	
					}
          
					if($_FILES['file']['name'] != "" ) { //uploading flash file
						$filename = fixFilename($_FILES['file']['name']);
						uploadFile($_FILES['file'], $filename);
						$row['pod_casts_filename'] = $filename;
					}
					
					if($_POST['id'] != '') {
						dbPerform('pod_casts', $row, 'update', 'pod_casts_id = ' . $_POST['id']);
						addMessage("Updated podcast successfully");
					} else {
						$row['pod_casts_date_added'] = time();
						
						dbPerform('pod_casts', $row, 'insert');
						addMessage("Added podcast successfully");
					}
					
					
					
					redirect(PAGE_MANAGE."?section=podcasts");
	
	break;
		}
	break;
	}
if($_GET['action'] == 'deletepodcast') {
	if($_GET['id']) {
		$pResults = dbQuery('SELECT pod_casts_filename FROM pod_casts WHERE pod_casts_id = ' . $_GET['id']);
		$p = dbFetchArray($pResults);
		
		@unlink(UPLOAD_DIR.$p['pod_casts_filename']);
		
		dbQuery("DELETE FROM pod_casts WHERE pod_casts_id = " . $_GET['id']);
		addMessage("Deleted pod cast successfully");
		redirect('manage.php?action=edit&section=podcasts&id='.$_GET['id']);	
	}
}
if($_GET['action'] == 'delete') {
	switch($_GET['section']) {
		case 'prayerrequests':
			dbQuery('DELETE FROM request_prayer WHERE id = ' . $_GET['id']);
			addMessage("Deleted prayer request successfully");
			redirect(PAGE_MANAGE."?section=prayerrequests");
		break;
		case 'testimonials':
			dbQuery('DELETE FROM testimonials WHERE testimonials_id = ' . $_GET['id']);
			addMessage('Deleted tesimonials successfully');
			redirect('manage.php?section=testimonials');
		break;
		case 'downloadables_category':
			//get all files with this categiry
			$results = dbQuery('SELECT * FROM page_downloads WHERE page_downloads_category = ' . $_GET['cid']);
			if(dbNumRows($results)) {
				while($f=dbFetchArray($results)){
				@unlink(UPLOAD_URL_DOWNLOADABLE.$f['page_downloads_filename']);	
				}
			}
			dbQuery('DELETE FROM page_downloads_category WHERE page_downloads_category_id = ' . $_GET['cid']);
				//delete category and all files assocualted with that category
			addMessage("Delete downloadables category successfully");
			redirect(PAGE_MANAGE."?section=downloadables");
		break;
 
		case 'sponsors':
			dbQuery('DELETE FROM sponsors WHERE sponsor_id = ' . $_GET['id']);
			dbQuery('DELETE FROM event_sponsors WHERE sponsor_id = ' . $_GET['id']);
			
			addMessage("Deleted sponsor successfully");
			redirect('manage.php?section=sponsors');
			
		break;
    
		case 'webpage':
			$parentCheck = dbQuery('SELECT * FROM page_content WHERE parent = ' . $_GET['id']);
			if(dbNumRows($parentCheck)){
				addError("The page you are trying to delete has child pages, you must delete those first in order to delete this one");
				redirect(PAGE_MANAGE."?section=webpage");
			} else {
				dbQuery('DELETE FROM page_content WHERE page_content_id = ' . $_GET['id']);
				addMessage("Removed page successfully");
				redirect(PAGE_MANAGE."?section=webpage");
			}
		break;
		case 'downloadables':
			//delete the files!
			$results = dbQuery('SELECT page_downloads_filename FROM page_downloads WHERE page_downloads_id = ' .$_GET['id']);
			$row = dbFetchArray($results);
			@unlink(UPLOAD_DIR_DOWNLOADABLE.$row['page_downloads_filename']);
			dbQuery('DELETE FROM page_downloads WHERE page_downloads_id = ' . $_GET['id']);
			addMessage("Removed file successfully");
			redirect(PAGE_MANAGE."?section=downloadables");

		break;
		case 'pagebanners':
			$pageBanner = dbQuery('DELETE FROM page_banners WHERE page_banners_id = ' . $_GET['id']);
			addMessage("Removed banner from page");
			redirect(PAGE_MANAGE."?section=webpage&action=banners&id=".$_GET['page']);
		break;
		case 'article':
			$article = dbQuery('DELETE FROM articles WHERE article_id = ' .$_GET['id']);
			addMessage("Deleted article successfully");
			redirect(PAGE_MANAGE."?section=article");
		break;
		case 'news':
			$news = dbQuery('DELETE FROM news WHERE news_id = ' .$_GET['id']);
			addMessage("Deleted news item successfully");
			redirect(PAGE_MANAGE."?section=news");
		break;
		case 'event':
			$event = dbQuery('DELETE FROM calendar_events WHERE calendar_events_id = ' . $_GET['id']);
			$items = dbQuery('DELETE FROM calendar_events_items WHERE calendar_events_id = ' . $_GET['id']);
			$cats = dbQuery('DELETE FROM calendar_events_categories WHERE calendar_events_id = ' . $_GET['id']);
			addMessage("Deleted event successfully");
			redirect(PAGE_MANAGE."?section=event");
		break;
		case 'podcasts':
		if($_GET['id']) {
			$fr = dbQuery('SELECT pod_casts_filename FROM pod_casts WHERE pod_casts_id = ' . $_GET['id']);
			$f = dbFetchArray($fr);
			@unlink(UPLOAD_DIR.$f['pod_casts_filename']);	
		}
			dbQuery('DELETE FROM pod_casts WHERE pod_casts_id = ' . $_GET['id']);
			addMessage("Deleted podcast successfully");
			redirect(PAGE_MANAGE."?section=podcasts");
		break;
		case 'alerts':
			$alert = dbQuery('DELETE FROM alerts WHERE alerts_id = ' . $_GET['id']);
			addMessage("Deleted alert successfully");
			redirect(PAGE_MANAGE."?section=alerts");
		break;
		case 'banners':
		
			
			$banner = dbQuery('SELECT * FROM banners WHERE banner_id = ' . $_GET['id']);
			$b = dbFetchArray($banner);
			
			@unlink(UPLOAD_BANNER_URL.$b['banner_filename']);
			
			dbQuery('DELETE FROM banners WHERE banner_id = ' . $_GET['id']);
			dbQuery('DELETE FROM page_banners WHERE banners_id = ' . $_GET['id']);
			
			addMessage("Deleted banner successfully");
			redirect(PAGE_MANAGE."?section=banners");
			
		break;
		case 'gallery':
			//we are going to delete the gallery and all images in the gallery =)! poop on a steeiicckkk
			//we want to go through each image and delete it's 4 thumbs!
			$imgs = dbQuery('SELECT * FROM gallery_images WHERE gallery_id = ' .$_GET['id']);
			while($img=dbFetchArray($imgs)){
				@unlink(UPLOAD_DIR_URL.$img['gallery_image_filename']);
				@unlink(UPLOAD_DIR_URL.getThumbnailFilename($img['gallery_image_filename'], 'thumb'));
				@unlink(UPLOAD_DIR_URL.getThumbnailFilename($img['gallery_image_filename'], 'small'));
				@unlink(UPLOAD_DIR_URL.getThumbnailFilename($img['gallery_image_filename'], 'medium'));
				@unlink(UPLOAD_DIR_URL.getThumbnailFilename($img['gallery_image_filename'], 'large'));
			}
			//now delete the records from the db!
			dbQuery('DELETE FROM gallery WHERE gallery_id = ' . $_GET['id']);
			dbQuery('DELETE FROM gallery_images WHERE gallery_id = ' . $_GET['id']);
			
			addMessage("Deleted the gallery successfully");
			redirect(PAGE_MANAGE."?section=gallery");
			
			break;
			case 'images':
			//we are deleting a pic.
			$img = dbQuery('SELECT gallery_image_filename FROM gallery_images WHERE gallery_image_id = ' . $_GET['image_id']);
			$row = dbFetchArray($img);
			@unlink(UPLOAD_DIR_URL.$row['gallery_image_filename']);
			dbQuery('DELETE FROM gallery_images WHERE gallery_image_id = ' . $_GET['image_id']);
			
			addMessage("Deleted image successfully");
			redirect(PAGE_MANAGE."?section=gallery&action=images&gallery=".$_GET['gallery']);
			
		break;
	}
}

switch($_GET['section']){
case 'webpage':
default;
	$trail->add("Pages");
break;
case 'article':
	$trail->add("Articles");
break;
case 'event':
	$trail->add("Events");
break;
case 'gallery':
	$trail->add("Show Success");
break;
case 'banners':
	$trail->add("Banners");
break;
}


if(!$_GET['section']) {
	if(user_has_permission('contest')){
		$default = 'alerts';	
	}
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
	if(user_has_permission('annuity') || user_has_permission('banners')){
		$default = 'annuityrates';	
	}
	if(user_has_permission('content') || user_has_permission('banners')){
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
<script type="text/javascript" src="jscripts/simpletip.js"></script>
<script language="javascript" src="jscripts/jquery.simpletip-1.3.1.min.js"></script>

<link rel="stylesheet" href="jscripts/ui.datepicker.css" />
<script language="javascript">
$(document).ready(function(){
	$(".simpletip").simpletip({ 
		fixed: true, 
		position: 'right',
		showTime : 350,
		offset: [10, 4],
		content : false
	}); 	

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
	$('#media_toggle').click(function() {
		$('#media').slideToggle();										  
	});
	
	$(".stripeTable tr").mouseover(function(){$(this).addClass("highlight");}).mouseout(function(){$(this).removeClass("highlight");});
   	$(".stripeTable tr:even").addClass("row1");
	$(".stripeTable tr:odd").addClass("row0");
	
	$("#toggleCalcs").click(function(){ 
		$("#calcs:checkbox").each(function(){
			if($(this).attr('checked')){
				$(this).attr('checked','checked');
			} else {
				$(this).attr('checked','');
			}
		}) 
	});
	
	$('#calendar_events_type').click(function() {
		if( $('#calendar_events_type').find(':selected').val() == "new") {
			$('#new_event_type').show();
		} else {
			$('#event_type_new').val("");
			$('#new_event_type').hide();
		}
	});
	
	
	<?php 
	if($_GET['section'] != "gallery") { ?>
	$('#title').keyup(function() {
  		//convert spaces to _ and lowercase everything..
  		var url = cleanURLField( $('#title').val() );
		$('#url').val(url+'.html');
   	});
	<? } ?>
	<?php 
	if($_GET['section'] == "podcasts") { ?>
	$('#pod_casts_title').keyup(function() {
  		//convert spaces to _ and lowercase everything..
  		var url = cleanURLField( $('#pod_casts_title').val() );
		$('#pod_casts_url_d').text(url);
		$('#pod_casts_url').val(url);
   	});
	<? } ?>
	
	
});
</script>



<?
if($_GET['section'] == 'gallery' && $_GET['action'] == 'images') {
	printGalleryHeadJS();
}
if($_GET['section'] == 'gallery' && $_GET['action'] == 'images' || $_GET['section'] == 'webpage' && $_GET['action'] == 'banners') {
	printGalleryModal();	
}
if($_GET['section'] == 'downloadables' && $_GET['action'] == 'files') {
	printFilesHeadJS();
}
if($_GET['section'] == 'downloadables' && $_GET['action'] == 'files' ) {
	//printFilesModal();	
}

//only load tinymce is we are editing =0
if($_GET['action'] == 'edit') { ?>
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
			
			
			
			$(".select_dropdown_parent dt a").click(function() {
                $(".select_dropdown_parent dd ul").toggle();
            });

            $(document).bind('click', function(e) {
                var $clicked = $(e.target);
                if (! $clicked.parents().hasClass("select_dropdown_parent"))
                    $(".select_dropdown_parent dd ul").hide();
            });
                        
            $(".select_dropdown_parent dd ul li a").click(function() {
                var text = $(this).html();
                $(".select_dropdown_parent dt a").html(text);
                $(".select_dropdown_parent dd ul").hide();
                
                var source = $("#parent");
                source.val($(this).find("span.value").html())
            });
			
			
});
</script>
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<?
	include('misc/contentEditor.php');
}
?>
<script type="text/javascript" src="jscripts/tiny_mce/plugins/imagemanager/js/mcimagemanager.js"></script>
<script language="javascript">
$(document).ready(function(){
	<?
	if($_GET['section'] == 'medialibrary') {
		echo "mcImageManager.open();\n";
	}
	?>
});
</script>

<link rel="stylesheet" href="css/styles.css" />
</head>
<body>
<div class="jqmWindow" id="jqModal" style="display:none;">
Loading...
</div>
<div id="topHeader"><? include('header.php'); ?></div>
<div id="header"></div>
<? include('navigation.php'); ?>
<div class="breadcrumbTrail">you are here: <?=$trail->getPath(" > ")?></div>
<?
printMessage();
printError();
?>
<div class="contentStart">
<? include('manage_sub_menu.php'); ?>
<?
switch($_GET['section']) {
	case 'medialibrary':
	?>
<table width="900" border="0" cellspacing="0" cellpadding="0" >
  <tr>
    <td width="650" valign="top" class="formBody"><div style="padding:10px; text-align:center;">
      <p>If the media library did not open please make sure you have your pop up blocker off.</p>
      <p> <a href="javascript:void(0);" onclick="mcImageManager.open()">Click here to launch the media library</a></p>
    </div></td>
  </tr>
</table>
<?
	break;
	case 'banners':
		switch($_GET['action']) {
		case 'manage':
		default:
				?>
        		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
			                <tr>
			                  <td>&nbsp;</td>
			                  <td width="100"><a class="button" href="<?=PAGE_PUBLISH?>?section=banners"><span class="add">Add Banner</span></a></td>
		                    </tr>
		                  </table></td>
			            </tr>
			          </table>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr >
				          <td class="tableRowHeader"width="39%" align="left">Title</td>
				          <td class="tableRowHeader" width="20%" align="left">Date Added</td>
                           <td class="tableRowHeader" width="20%" align="left">Category</td>
                          <td class="tableRowHeader" width="20%" align="left">&nbsp;</td>
			            </tr>
                        <?
						$bannerResults = dbQuery('SELECT * FROM banners ORDER by banner_date_added DESC');
						$count=0;
						if(dbNumRows($bannerResults)) {

							while($bInfo = dbFetchArray($bannerResults)) {
								
								$row = $count % 2;
								?>
								<tr>
								  <td ><a href="<?=PAGE_MANAGE?>?action=edit&section=banners&id=<?=$bInfo['banner_id']?>"><?=output($bInfo['banner_title'])?></a></td>
								  <td >
								  <?
								  echo date('m/d/Y', $bInfo['banner_date_added']);
								  ?>
								  </td>
                                  <td >
								  <?
								  switch($bInfo['banners_category']) {
									case 'sidebar':
										echo "Side Bar";
									break;
									case 'mainpage':
										echo "Home Page Banner";
									break;
									case 'sidebar_homepage':
										echo "Home Page Sidebar";
									break;
								  }
								  ?>
								  </td>
                                  <td align="right" ><a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&section=banners&id=<?=$bInfo['banner_id']?>" onclick="return confirm('Are you sure you want to delete <?=output($bInfo['banner_title'])?>?');">Delete</a></td>
								</tr>
								<?
								$count++;
								
							} 
							
						} else { // no gallery!
							?>
                            <tr>
                            	<td colspan="4">There are no banners set... <a href="<?=PAGE_PUBLISH?>?section=banners">Add A Banner</a></td>
                            </tr>
                            <?
						}
						?>
		            </table>
                      </form></td>
			      </tr>
  			</table>
        <?
		break;
		case 'event':
		case 'edit':
						$bannerResult = dbQuery('SELECT * FROM banners WHERE banner_id = ' . $_GET['id']);
						$bInfo = dbFetchArray($bannerResult);
							?>
                            <form action="<?=PAGE_MANAGE?>" method="post" enctype="multipart/form-data" name="banners" id="banners">
                            <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                            <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                            <input type="hidden" name="action" value="update" />
                            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                            <table  border="0" cellspacing="0" cellpadding="0" >
                              <tr>
                                <td width="650" valign="top" class="formBody">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td class="pageTitleSub">Title</td>
                                    </tr>
                                    <tr>
                                      <td><input type="text" name="banner_title" id="banner_title" class="textField-title" style="width:650px" value="<?=output($bInfo['banner_title'])?>" /></td>
                                    </tr>
                                    <tr>
                                      <td class="pageTitleSub">URL</td>
                                    </tr>
                                    <tr>
                                      <td>
                                      <input type="text" name="banner_url" id="banner_url" class="textField-title" style="width:500px" value="<?=$bInfo['banner_url']?>" /></td>
                                    </tr>
                                    <tr>
                                      <td class="pageTitleSub">Open link in</td>
                                    </tr>
                                    <tr>
                                      <td><select name="banner_url_target" id="banner_url_target">
                                        <option value="_self" <? if($bInfo['banner_url_target'] == '_self') echo " selected"; ?>>Same Window</option>
                                        <option value="_blank" <? if($bInfo['banner_url_target'] == '_blank') echo " selected"; ?>>New Window</option>
                                      </select></td>
                                    </tr>
                                    <tr>
                                      <td class="pageTitleSub">Banner Image</td>
                                    </tr>
                                    <tr>
                                    	<td>Current Image:<br />
                                        <img src="<?=UPLOAD_BANNER_URL.$bInfo['banner_filename']?>" width="100" height="50" />
                                        </td>
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
                                        <td class="headerCell"><h2>Save Banner</h2></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Banner Category</strong>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>
                                          <select name="banners_category" id="banners_category">
                                            <option value="sidebar" <?  if($bInfo['banners_category'] == 'sidebar') echo " selected"; ?>>Side Bar (max 150px wide)</option>
                                            <option value="mainpage" <?  if($bInfo['banners_category'] == 'mainpage') echo " selected"; ?>>Home Page Banner (300 x 200px)</option>
                                          	<option value="sidebar_homepage" <?  if($bInfo['banners_category'] == 'sidebar_homepage') echo " selected"; ?>>Home Page Sidebar (max 150px wide)</option>
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
                                </table>      <p>&nbsp;</p></td>
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
		}
	break;
	
	case 'testimonials':
		switch($_GET['action']) {
		case 'manage':
		default:
				?>
        		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
			                <tr>
			                  <td>&nbsp;</td>
			                  <td width="100"><a class="button" href="<?=PAGE_PUBLISH?>?section=testimonials"><span class="add">Add a Testimonial</span></a></td>
		                    </tr>
		                  </table></td>
			            </tr>
			          </table>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr >
				          <td class="tableRowHeader"width="39%" align="left">Author</td>
				          <td class="tableRowHeader" width="20%" align="left">Date Added</td>
                          <td class="tableRowHeader" width="20%" align="left">&nbsp;</td>
			            </tr>
                        <?
						$testimonialResults = dbQuery('SELECT * FROM testimonials ORDER by testimonials_date_added DESC');
						$count=0;
						if(dbNumRows($testimonialResults)) {

							while($bInfo = dbFetchArray($testimonialResults)) {
								
								$row = $count % 2;
								?>
								<tr>
								  <td ><a href="<?=PAGE_MANAGE?>?action=edit&section=testimonials&id=<?=$bInfo['testimonials_id']?>"><?=output($bInfo['testimonials_author'])?></a></td>
								  <td >
								  <?
								  echo date('m/d/y', $bInfo['testimonials_date_added']);
								  ?>
								  </td>
                                  <td align="right" ><a class="table_edit_link" href="<?=PAGE_MANAGE?>?action=edit&section=testimonials&id=<?=$bInfo['testimonials_id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&section=testimonials&id=<?=$bInfo['testimonials_id']?>" onclick="return confirm('Are you sure you want to delete this testimonial?');">Delete</a></td>
								</tr>
								<?
								$count++;
								
							} 
							
						} else { // no gallery!
							?>
                            <tr>
                            	<td colspan="4">No results returned <a href="<?=PAGE_PUBLISH?>?section=testimonials">Add a Testimonial</a></td>
                            </tr>
                            <?
						}
						?>
		            </table>
                      </form></td>
			      </tr>
  			</table>
        <?
		break;
		case 'event':
		case 'edit':
						$bannerResult = dbQuery('SELECT * FROM testimonials WHERE testimonials_id = ' . $_GET['id']);
						$bInfo = dbFetchArray($bannerResult);
							?>
                            <form action="<?=PAGE_MANAGE?>" method="post" enctype="multipart/form-data" name="banners" id="banners">
                            <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                            <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                            <input type="hidden" name="action" value="update" />
                            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
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
                                        <td class="headerCell"><h2>Save Testimonial</h2></td>
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
	break;
	
	
	
	case 'downloadables_category':
		
					if(isset($_GET['cid'])) { //editing a category
						$category = dbQuery('SELECT * FROM page_downloads_category WHERE page_downloads_category_id = ' . $_GET['cid']);
						$cInfo = dbFetchArray($category);
					} else {
						$cInfo = array();
					}
							?>
                            <form action="<?=PAGE_MANAGE?>" method="post" enctype="multipart/form-data" name="banners" id="banners">
                            <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                            <input type="hidden" name="section" value="" />
                            <input type="hidden" name="action" value="<?=$_GET['section']?>" />
                            <input type="hidden" name="id" value="<?=$_GET['cid']?>" />
                            <table  border="0" cellspacing="0" cellpadding="0" >
                              <tr>
                                <td width="650" valign="top" class="formBody">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td class="pageTitleSub">Category Name</td>
                                    </tr>
                                    <tr>
                                      <td><input type="text" name="title" id="title" class="textField-title" style="width:650px" value="<?=output($cInfo['page_downloads_category_title'])?>" /></td>
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
                                        <td class="headerCell"><h2>Save Category</h2></td>
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
	case 'downloadables':
		switch($_GET['action']) {
		
		case 'manage':
		default:
				?>
        		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				    <form>
                      
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
			                <tr>
			                  <td>&nbsp;</td>
			                  <td width="100"><a class="button" href="<?=PAGE_MANAGE?>?section=downloadables_category&action=add"><span class="add">Create Category</span></a></td>
		                    </tr>
		                  </table></td>
			            </tr>
			          </table>
                      
                      <? //get downloadable categories 
					 $cResults = dbQuery('SELECT * FROM page_downloads_category ORDER BY page_downloads_category_title ASC');
					 $count='';
					 while($cInfo = dbFetchArray($cResults)) { ?>
                     
                     <h2><?=output($cInfo['page_downloads_category_title'])?> <a href="<?=PAGE_MANAGE?>?section=downloadables&action=files&cid=<?=$cInfo['page_downloads_category_id']?>" class="" style="font-size:11px; font-weight:normal;">[ Add Files ]</a> <a style="font-size:11px; font-weight:normal;" href="<?=PAGE_MANAGE?>?section=downloadables_category&action=edit&cid=<?=$cInfo['page_downloads_category_id']?>">[edit category]</a> <a style="font-size:11px; font-weight:normal;" href="<?=PAGE_MANAGE?>?section=downloadables_category&action=delete&cid=<?=$cInfo['page_downloads_category_id']?>" onclick="return confirm('Are you sure you want to delete this category? THIS WILL DELETE ALL FILES ASSOCIATED WITH THIS CATEGORY');">[delete category]</a></h2>
                      
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr >
				          <td class="tableRowHeader"width="39%" align="left">Title</td>
				          <td class="tableRowHeader" width="20%" align="left">Date Modified</td>
                          <td class="tableRowHeader" width="20%" align="left">&nbsp;</td>
			            </tr>
                        <?
						$results = dbQuery('SELECT * FROM page_downloads WHERE page_downloads_category = '.$cInfo['page_downloads_category_id'].' ORDER BY page_downloads_title');
						$count=0;
						if(dbNumRows($results))
						{

							while($dInfo = dbFetchArray($results))
							{
								
								?>
								<tr>
								  <td><a href="<?=PAGE_MANAGE?>?action=edit&section=downloadables&id=<?=$dInfo['page_downloads_id']?>"><?=output($dInfo['page_downloads_title'])?></a></td>
								  <td> <? echo date('m/d/Y', $dInfo['page_downloads_date_added']); ?></td>
                                 
                                  <td align="right" ><a class="table_edit_link" href="<?=PAGE_MANAGE?>?action=edit&section=downloadables&id=<?=$dInfo['page_downloads_id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&section=downloadables&id=<?=$dInfo['page_downloads_id']?>" onclick="return confirm('Are you sure you want to delete <?=output($dInfo['page_downloads_title'])?>?');">Delete</a></td>
								</tr>
								<?
							} 
						} 
						else
						{ // no gallery!
							?>
                            <tr>
                            	<td colspan="4">No results returned <a href="<?=PAGE_PUBLISH?>?section=downloadables">Upload a file</a></td>
                            </tr>
                            <?
						}
						?>
		            </table><br /> <?  
					$count++;
					}
                   ?>
                    
                   </form></td>
			      </tr>
  			</table>
        <?
		break;
		case 'files':
				?>
				<table w border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td width="785" valign="top" class="formBody">
			         
                     <form id="form1" action="index.php" method="post" enctype="multipart/form-data">
                        <p>&nbsp;</p>
                
                        <div id="divSWFUploadUI">
                            <div class="fieldset flash" id="fsUploadProgress">
                            <span class="legend">Upload Queue</span>
                
                            </div>
                            <p id="divStatus">0 Files Uploaded</p>
                            <p>
                                <span id="spanButtonPlaceholder"></span>
                                <input id="btnUpload" type="button" value="Select Files" style="width: 61px; height: 22px; font-size: 8pt;" />
                                <input id="btnCancel" type="button" value="Cancel All Uploads" disabled="disabled" style="margin-left: 2px; height: 22px; font-size: 8pt;" />
                            </p>
                            <br style="clear: both;" />
                
                        </div>
                        <noscript >
                            We're sorry.  SWFUpload could not load.  You must have JavaScript enabled to enjoy SWFUpload.
                        </noscript>
                        <div id="divLoadingContent" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
                            SWFUpload is loading. Please wait a moment...
                        </div>
                        <div id="divLongLoading" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
                            SWFUpload is taking a long time to load or the load has failed.  Please make sure that the Flash Plugin is enabled and that a working version of the Adobe Flash Player is installed.
                        </div>
                        <div id="divAlternateContent" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
                
                            We're sorry.  SWFUpload could not load.  You may need to install or upgrade Flash Player.
                            Visit the <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a> to get the Flash Player.
                        </div>
                    </form>

                    <div class="clear"></div>
                     <?
					//display current files!
					print_download_files($_GET['cid']);
					?>
                    
                     
				    <div class="mb20"></div>
				    </td>
				    <td width="250" valign="top">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="">
                          <tr>
                            <td>
                            <div style="margin:10px 10px 10px 10px;">
                              <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                <tr>
                                  <td class="headerCell"><h2>Save Downloads</h2></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /></td>
                                </tr>
                              </table>
				          </div>
                          </td>
			          	</tr>
				      </table>
                    </td>
			      </tr>
  </table>
<?
		break;
		case 'edit':
						$fileResults = dbQuery('SELECT * FROM page_downloads WHERE page_downloads_id = ' . $_GET['id']);
						$dInfo = dbFetchArray($fileResults);
							?>
                            <form action="<?=PAGE_MANAGE?>" method="post" enctype="multipart/form-data" name="banners" id="banners">
                            <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                            <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                            <input type="hidden" name="action" value="update" />
                            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                            <table  border="0" cellspacing="0" cellpadding="0" >
                              <tr>
                                <td width="650" valign="top" class="formBody">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td class="pageTitleSub">File Name</td>
                                    </tr>
                                    <tr>
                                      <td><input type="text" name="page_downloads_title" id="page_downloads_title" class="textField-title" style="width:650px" value="<?=output($dInfo['page_downloads_title'])?>" /></td>
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
											$cResults = dbQuery('SELECT * FROM page_downloads_category ORDER BY page_downloads_category_title');
											while($c=dbFetchArray($cResults))
											{
											echo "<option value=\"".$c['page_downloads_category_id']."\"";
												if($dInfo['page_downloads_category'] == $c['page_downloads_category_id'])
												{ 
													echo " selected"; 
												}
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
	break;
	case 'gallery':
		switch($_GET['action']) {
			case 'manage':
			default:
			
			?>
        		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
			                <tr>
			                  <td >&nbsp;</td>
			                  <td width="100"><a class="button" href="<?=PAGE_PUBLISH?>?section=gallery"><span class="add">Add Item</span></a></td>
		                    </tr>
		                  </table></td>
			            </tr>
			          </table>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr >
				          <td class="tableRowHeader"width="39%" align="left">Title</td>
				          <td class="tableRowHeader" width="20%" align="left">Date</td>
				          <td class="tableRowHeader" width="20%" align="left"># images</td>
                          <td class="tableRowHeader" width="20%" align="left">&nbsp;</td>
			            </tr>
                        <?
						$galleryResults = dbQuery('SELECT * FROM gallery ORDER by gallery_date_added DESC');
						$count=0;
						if(dbNumRows($galleryResults)) {

							while($gInfo = dbFetchArray($galleryResults)) {
								
								$row = $count % 2;
								?>
								<tr>
								  <td><?=output($gInfo['gallery_title'])?></td>
								  <td>
								  <?
								  echo date('m/d/Y', $gInfo['gallery_date_added']);
								  ?>
								  </td>
								  <td><?=get_gallery_image_count($gInfo['gallery_id'])?></td>
                                  <td align="right" ><a class="table_edit_link" href="<?=PAGE_MANAGE?>?action=edit&section=gallery&id=<?=$gInfo['gallery_id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&section=gallery&id=<?=$gInfo['gallery_id']?>" onclick="return confirm('Are you sure you want to delete <?=output($gInfo['gallery_title'])?>?');">Delete</a></td>
								</tr>
								<?
								$count++;
								
							} 
							
						} else { // no gallery!
							?>
                            <tr>
                            	<td colspan="4">There are no shows set... <a href="<?=PAGE_PUBLISH?>?section=gallery">Add Item</a></td>
                            </tr>
                            <?
						}
						?>
		            </table>
                      </form></td>
			      </tr>
  			</table>
        <?
				
		break;
		case 'edit':
				$galleryResults = dbQuery('SELECT * FROM gallery WHERE gallery_id = ' . $_GET['id']);
				$gInfo = dbFetchArray($galleryResults);
				?>
                	<form action="<?=PAGE_MANAGE?>" method="post" enctype="multipart/form-data" name="gallery" id="event">
                    <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="gallery_id" value="<?=$_GET['id']?>" />
                    <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                    <table border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="785" valign="top" class="formBody">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td colspan="2" class="pageTitleSub">Title</td>
                            </tr>
                            <tr>
                              <td colspan="2"><input type="text" name="title" id="title" class="textField-title" style="width:650px" value="<?=output($gInfo['gallery_title'])?>" /></td>
                            </tr>
                            <tr>
                              <td colspan="2" class="pageTitleSub">Project Description</td>
                            </tr>
                            <tr>
                              <td colspan="2">
                               <? echo printEditorCB("desc", "desc", $gInfo['gallery_desc']); ?>
                              </td>
                            </tr>
                            <tr>
                              <td width="17%" class="pageTitleSub">Sort Order </td>
                              <td width="83%" class="pageTitleSub"><input type="text" name="sort_order" id="sort_order" class="textField-title" style="width:50px" value="<?=output($gInfo['gallery_sort'])?>" /></td>
                            </tr>
                            <tr>
                              <td colspan="2">&nbsp;</td>
                            </tr>
                          </table>
                          
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
                                  <span class="smallText">(Checking this will set this project as the first one))</span>                                  <div class="mt10"></div>
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
                    <script language="javascript">
						$(document).ready(function(){
						
							$("#calendarContainer").datepicker(
							{
								altField: '#publish_date',
								altFormat: 'mm/dd/yy',
								defaultDate: new Date('<?=date('m/d/Y', $gInfo['gallery_date_added'])?>')
							}
							);
						
							
						});
						</script>
				
				<?
		break;
		case 'images':
				?>
				<table w border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td width="785" valign="top" class="formBody"><br />
                    <h1>Images for <? print_gallery_title($_GET['gallery']); ?></h1>
			         
                     <div class="galleryAdd">
                     	<form>
                        <div style="display: inline; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
                            <span id="spanButtonPlaceholder"></span>
                        </div>
                    	</form>
                    	<div id="divFileProgressContainer" style="height: 45px;"></div>
                    </div>
                    <div id="thumbnails"></div>
                    <div class="clear"></div>
                     <?
					//display current images!
					print_gallery_image_thumbs($_GET['gallery']);
					?>
                    
                     
				    <div class="mb20"></div>
				    </td>
				    <td width="250" valign="top">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="">
                          <tr>
                            <td>
                            <div style="margin:10px 10px 10px 10px;">
                              <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                <tr>
                                  <td class="headerCell"><h2>Publish Gallery</h2></td>
                                </tr>
                                <tr>
                                  <td><strong>Gallery added on:</strong><br /><?=print_gallery_date_added($_GET['gallery'])?>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /></td>
                                </tr>
                              </table>
				          </div>
                          </td>
			          	</tr>
				      </table>
                    </td>
			      </tr>
  				</table>
				<?
		break;

		}
	break;
	case 'prayerrequests':
		switch($_GET['action']) {
			case 'manage':
			default:
				?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <br />
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
			                <tr>
				                <td>&nbsp;</td>
			                  <td width="100">&nbsp;</td>
		                    </tr>
		                  </table></td>
			            </tr>
			          </table>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr >
				          <td class="tableRowHeader" align="left">From</td>
				          <td class="tableRowHeader" align="left">Date Added</td>
				          <td class="tableRowHeader" align="left">Status</td>
				          <td class="tableRowHeader" align="left">&nbsp;</td>
			            </tr>
                        <?
						$requestResults = dbQuery('SELECT * FROM request_prayer ORDER BY approved, date_added ASC');
						$count=0;
						while($rInfo = dbFetchArray($requestResults)) {
						$row = $count % 2;
						?>
				        <tr>
				          <td ><a href="<?=PAGE_MANAGE?>?action=edit&section=prayerrequests&id=<?=$rInfo['id']?>"><?=output($rInfo['name'])?></a></td>
				          <td >
                          <?
						  echo date('m/d/Y', $rInfo['date_added']);
						  
						  ?>
                          </td>
				          
				          <td >
                          	<?
                            	if( $rInfo['approved'] == '0') {
									$class = "textInactive";	
									$text = "awaiting approval";
								}else{
									$class = "textActive";
									$text = "approved";
								}
								echo "<span class=\"".$class."\">".$text."</span>\n";
							?> </td>
				          <td align="right"><a class="table_edit_link" href="<?=PAGE_MANAGE?>?action=edit&section=prayerrequests&id=<?=$rInfo['id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&amp;section=prayerrequests&amp;id=<?=$rInfo['id']?>" onclick="return confirm('Are you sure you want to delete this prayer request? this is NOT UNDOABLE');">Delete</a></td>
			            </tr>
                        <?
						$count++;
						}
						?>
		            </table>
                      </form></td>
			      </tr>
  				</table><?
			
								
			
			break;
			case 'edit':
					if($_GET['id']) {
						$prayerResults = dbQuery("SELECT * FROM request_prayer WHERE id = " . $_GET['id']);
						$r = dbFetchArray($prayerResults);
					}
					
					?>
						<form id="requestprayer" name="requestprayer" method="post" action="<?=PAGE_MANAGE?>" enctype="multipart/form-data">
						<input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="section" value="<?=$_GET['section']?>" />
						<input type="hidden" name="id" value="<?=$_GET['id']?>" />
						<table border="0" cellspacing="0" cellpadding="0" >
						  <tr>
							<td width="785" valign="top" class="formBody">
							  <table width="100%" border="0" cellspacing="0" cellpadding="5">
								<tr>
								  <td class="pageTitleSub">Name</td>
								</tr>
								<tr>
								  <td><input type="text" name="name" id="name" class="textField-title" style="width:650px" value="<?=output($r['name'])?>" /></td>
								</tr>
								
								
								<tr>
								  <td class="pageTitleSub">Prayer Request</td>
								</tr>
								
								<tr>
								  <td>
								 
								  <textarea style="width:600px; height:80px;" id="text" name="text" class="textField-title"><?=output($r['text']);?></textarea>
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
									<td class="headerCell"><h2>Publish Prayer</h2></td>
								  </tr>
								  <tr>
									<td><strong>Status</strong></td>
								  </tr>
								  <tr>
									<td><select name="approved" id="approved">
									  <option value="1" <? if($r['approved'] == '1') echo " selected"; ?>>Published</option>
									  <option value="0" <? if($r['approved'] == 'pending') echo " 0"; ?>>Unpublished</option>
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
				
		}
	break;
	
	case 'blog':
	default:
		switch($_GET['action']) {
			case 'edit':
			
			//get info for the post!
			$newsResult = dbQuery("SELECT * FROM blog WHERE id = " . $_GET['id']);
			$n = dbFetchArray($newsResult);
				?>
				<form id="event" name="blog" method="post" action="<?=PAGE_MANAGE?>" enctype="multipart/form-data">
				<input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
				<input type="hidden" name="action" value="update" />
                <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                <table border="0" cellspacing="0" cellpadding="0" >
				  <tr>
					<td width="785" valign="top" class="formBody">
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
						<tr>
						  <td class="pageTitleSub">Title</td>
						</tr>
						<tr>
						  <td><input type="text" name="title" id="title" class="textField-title" style="width:650px" value="<?=output($n['title'])?>" /></td>
						</tr>
                        <tr>
						  <td class="pageTitleSub">Snippet<br /><span class="smallText">This copy is placed on a blog listing, it's more of  breakdown of the blog post. You can just paste int the first paragraph of the main post</span></td>
						</tr>
						<tr>
						  <td><textarea name="snippet" id="snippet" style="width:650px; height:50px;"><?=output($n['snippet'])?></textarea></td>
						</tr>
                         <tr>
						  <td class="pageTitleSub">Podcast File <span class="smallText" style="font-weight:normal;">(.mp3 format required.)</span></td>
						</tr>
						<tr>
						  <td><input type="file" name="podcast" id="podcast" class="textField-title" style="width:650px" /></td>
						</tr>
                        <?php
						if($n['podcast'] !='') {
						?>
                        <tr>
                        	<td>Current File: <?=$n['podcast']?> <a href="manage.php?action=delpodcast&section=blog&id=<?=$n['id']?>">(delete)</a></td>
                        </tr>
                        <?	
						}
						?>
                        <tr>
						  <td class="pageTitleSub">Blog Post Image <span class="smallText" style="font-weight:normal;">(400x400 pixels recommended)</span></td>
						</tr>
						<tr>
						  <td><input type="file" name="file" id="file" class="textField-title" style="width:650px" /></td>
						</tr>
                        <?php
						if($n['filename'] !='') {
						?>
                        <tr>
                        	<td>Current File: <?=$n['filename']?> <a href="manage.php?action=delfile&section=blog&id=<?=$n['id']?>">(delete)</a></td>
                        </tr>
                        <?	
						}
						?>
						<tr>
						  <td class="pageTitleSub">Content</td>
						</tr>
						<tr>
						  <td>
                          <? echo printEditorCB("content", "content", $n['content']); ?>
                         </td>
						</tr>
					  </table>
					 <div class="mb20"></div>
                     
                          
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
										if($_SESSION['user_id'] == $aInfo['user_id'])
											echo " selected";
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
							  <option value="published" <? if($n['status'] == 'published') echo " selected"; ?>>Published</option>
							  <option value="pending" <? if($n['status'] == 'pending') echo " selected"; ?>>Pending Review</option>
							  <option value="private" <? if($n['status'] == 'inactive') echo " selected"; ?>>Unpublished</option>
							</select></td>
						  </tr>
						 
						  <tr>
							<td>
							<strong>Publish Date</strong><br />
							<div id="calendarContainer" style="padding:5px 0px;" align="center"></div>
							<input type="text" name="publish_date" id="publish_date" value="<?=date('m/d/Y', $n['publish_date'])?>" readonly="readonly" />
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
								defaultDate: new Date('<?=date('m/d/Y', $n['publish_date'])?>')
							}
							);
						
							
						});
						</script>
				<?
				break;
				case 'manage':
				default:
					//display events 
				//$sql = "SELECT * FROM blog b LEFT JOIN catgories c ON c.id = b.category ORDER BY b.publish_date DESC";
				$sql = "SELECT * FROM blog ORDER BY publish_date DESC";
				$newsResults = dbQuery($sql);
				
				
					
				
					
				?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <br />
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
			                <tr>
				                <td>&nbsp;</td>
			                  <td width="100"><a class="button" href="<?=PAGE_PUBLISH?>?section=blog"><span class="add">Add Blog</span></a></td>
		                    </tr>
		                  </table></td>
			            </tr>
			          </table>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr >
				          <td class="tableRowHeader" >&nbsp;</td>
				          <td class="tableRowHeader" align="left">Title</td>
				          <td class="tableRowHeader" align="left">Date</td>
				          <td class="tableRowHeader" align="left">Comments</td>
                          <td class="tableRowHeader" align="left">Author</td>
				          <td class="tableRowHeader" align="left">Status</td>
				          <td class="tableRowHeader" align="left">&nbsp;</td>
			            </tr>
                        <?
						if(dbNumRows($newsResults)) {
						$count=0;
						while($nInfo = dbFetchArray($newsResults)) {
						$commentResults = dbQuery('SELECT * FROM blog_comments WHERE blog_id = ' . $nInfo['id']);
						$commentCount = dbNumRows($commentResults);	
						
						$row = $count % 2;
						?>
				        <tr>
				          <td nowrap="nowrap" align="right"><? echo "<span class=\"smallGreenCaps\">".$nInfo['categoryName']."</span>"; ?></td>
				          <td ><a href="<?=PAGE_MANAGE?>?action=edit&section=blog&id=<?=$nInfo['id']?>"><?=output($nInfo['title'])?></a></td>
				          <td >
                          <?
						  echo date('m/d/Y', $nInfo['publish_date']);
						  ?>
                          </td>
                          <td><a href="<?=PAGE_MANAGE?>?action=edit&section=comments&id=<?=$nInfo['id']?>" class="table_comment_link"><?=$commentCount?> Comment(s)</a></td>
				          <td ><?=getAuthor($nInfo['author'])?></td>
				          <td >
                          	<?
                            	if( $nInfo['status'] == 'pending') {
									$class = "textPending";	
								}else if($nInfo['status'] == 'inactive') {
									$class = "textInactive";
								}else{
									$class = "textActive";
								}
								echo "<span class=\"".$class."\">".$nInfo['status']."</span>\n";
							?> </td>
				          <td align="right"><a class="table_edit_link" href="<?=PAGE_MANAGE?>?action=edit&section=blog&id=<?=$nInfo['id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&amp;section=blog&amp;id=<?=$nInfo['id']?>" onclick="return confirm('Are you sure you want to delete this blog post? this is NOT UNDOABLE');">Delete</a></td>
			            </tr>
                        <?
						$count++;
						}
						?>
                        <?php
						} else { // no results returned 
							echo "<tr><td colspan='7'>No results returned</td></tr>";	
						} ?>
		            </table>
                      </form></td>
			      </tr>
  </table>
    <? }
	break;
	case 'comments':
	?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
      <tr>
        <td valign="top" class="formBody">
          <br />
          <form>
         	<?php
			$commentResults = dbQuery('SELECT * FROM blog_comments WHERE blog_id = ' . $_GET['id']);
			$count = 1;
			while($cInfo = dbFetchArray($commentResults)) {
				$class = ($count % 2) ? "even" : "odd";
				echo "<div class=\"comment-".$class."\">\n";
				echo "<div class=\"deleteComment\">";
				echo "<a class=\"table_delete_link\" onclick=\"return confirm('Are you sure you want to delete this comment? this is NOT undoable');\" href=\"".PAGE_MANAGE."?action=delete&section=comment&id=".$cInfo['id']."&nid=".$_GET['id']."\">Delete</a>";
				echo "</div>\n";
				echo "<div class=\"commentBy-".$class."\">Submited: " . date('F d, Y g:i a', $cInfo['date_added']) . " By: " . $cInfo['name']."</div>";
				echo "<div class=\"commentBody-".$class."\"><strong>Comment:</strong> " . output($cInfo['text'])."</div>";
				echo "</div>";
				$count++;
			}	
			?>
         </form>
        </td>
      </tr>
    </table>             
    <?php
	break;
	
	case 'podcasts':
		switch($_GET['action']) {
			case 'edit':
			
			//get info for the post!]
			if($_GET['id']) {
				$newsResult = dbQuery("SELECT * FROM pod_casts WHERE pod_casts_id = " . $_GET['id']);
				$n = dbFetchArray($newsResult);
			} else {
				$n = array();	
				$n['pod_casts_publish_date'] = time();
				
			}
			
			?>
				<form id="event" name="webpage" method="post" action="manage.php" enctype="multipart/form-data">
                <input type="hidden" name="section" value="podcasts" />
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="id" value="<?=$_GET['id']?>" />
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
						    <input type="text" name="pod_casts_url" id="pod_casts_url" class="textField-title" style="width:650px" value="<?=output($n['pod_casts_url'])?>" />
						    <span class="smallText">(.This must be the URL to the video on youtube (ex. http://www.youtube.com/watch?v=B7TICfqeck)</span><br />
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
                        <!--
                         <tr>
						  <td class="pageTitleSub">Direct link URL</td>
						</tr>
                        <tr>
						  <td><?=SITE_URL?>podcasts/view/<span id="pod_casts_url_d"><?=output($n['pod_casts_id'])?></span>
                          <input type="hidden" name="pod_casts_url" id="pod_casts_url" value="<?=$n['pod_casts_url']?>" />
                          </td>
						</tr>
                        -->
					  </table>
					 
					<div class="mb20"></div></td>
					<td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
					  <tr>
						<td>
						<div style="margin:0px 10px 10px 10px;">
						<table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
						  <tr>
							<td class="headerCell"><h2>Save Media</h2></td>
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
							<input type="hidden" name="pod_casts_publish_date" id="pod_casts_publish_date" value="<?=date('m/d/Y', $n['pod_casts_publish_date'])?>" readonly="readonly" />
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
						    <td>&nbsp;</td>
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
								defaultDate: new Date('<?=date('m/d/Y', $n['pod_casts_publish_date'])?>')
							}
							);
						 
							
						});
						</script>
				<?
				break;
				case 'manage':
				default:
					//display events 
				$sql = "SELECT * FROM pod_casts ORDER BY pod_casts_date_added DESC";
				$newsResults = dbQuery($sql);
					
					
				?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <br />
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
			                <tr>
				                <td>&nbsp;</td>
			                  <td width="100"><a class="button" href="<?=PAGE_PUBLISH?>?section=podcasts&action=edit"><span class="add">Add Podcast</span></a></td>
		                    </tr>
		                  </table></td>
			            </tr>
			          </table>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr >
				          <td class="tableRowHeader" align="left">Title</td>
				          <td class="tableRowHeader" align="left">Publish Date</td>
				          <td class="tableRowHeader" align="left">Status</td>
				          <td class="tableRowHeader" align="left">&nbsp;</td>
			            </tr>
                        <?
						$count=0;
						while($nInfo = dbFetchArray($newsResults)) {
						$row = $count % 2;
						?>
				        <tr>
				          <td ><a href="<?=PAGE_MANAGE?>?action=edit&section=podcasts&id=<?=$nInfo['pod_casts_id']?>"><?=output($nInfo['pod_casts_title'])?></a></td>
				          <td >
                          <?
						  echo date('m/d/Y', $nInfo['pod_casts_publish_date']);
						  
						  ?>
                          </td>
				          
				          <td >
                          	<?
                            	if( $nInfo['pod_casts_status'] == 'pending') {
									$class = "textPending";	
								}else if($nInfo['pod_casts_status'] == 'inactive') {
									$class = "textInactive";
								}else{
									$class = "textActive";
								}
								echo "<span class=\"".$class."\">".$nInfo['pod_casts_status']."</span>\n";
							?> </td>
				          <td align="right"><a class="table_edit_link" href="<?=PAGE_MANAGE?>?action=edit&section=podcasts&id=<?=$nInfo['pod_casts_id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&amp;section=podcasts&amp;id=<?=$nInfo['pod_casts_id']?>" onclick="return confirm('Are you sure you want to delete this podcast? this is NOT UNDOABLE');">Delete</a></td>
			            </tr>
                        <?
						$count++;
						}
						?>
		            </table>
                      </form></td>
			      </tr>
  </table>
                                 <? }
	break;
	
	
	case 'news':
		switch($_GET['action']) {
			case 'edit':
			
			//get info for the post!
			$newsResult = dbQuery("SELECT * FROM news WHERE news_id = " . $_GET['id']);
			$n = dbFetchArray($newsResult);
				?>
				<form id="event" name="webpage" method="post" action="<?=PAGE_MANAGE?>">
				<input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
				<input type="hidden" name="action" value="update" />
                <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                <table border="0" cellspacing="0" cellpadding="0" >
				  <tr>
					<td width="785" valign="top" class="formBody">
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
						<tr>
						  <td class="pageTitleSub">Title</td>
						</tr>
						<tr>
						  <td><input type="text" name="pod_casts_title" id="pod_casts_title" class="textField-title" style="width:650px" value="<?=output($n['news_title'])?>" /></td>
						</tr>
						<tr>
						  <td class="pageTitleSub">Page URL</td>
						</tr>
						<tr>
						  <td>http://yoursite.com/news/<input name="url" type="text" class="textField-title" id="url" style="width:400px" value="<?=output($n['news_url'])?>" /></td>
						</tr>
						<tr>
						  <td class="pageTitleSub">Content</td>
						</tr>
						<tr>
						  <td>
                          <? echo printEditorCB("content", "content", $n['news_text']); ?>
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
							<td class="headerCell"><h2>Publish News</h2></td>
						  </tr>
						  <tr>
							<td><strong>Status</strong></td>
						  </tr>
						  <tr>
							<td><select name="status" id="status">
							  <option value="published" <? if($n['news_status'] == 'active') echo " selected"; ?>>Published</option>
							  <option value="pending" <? if($n['news_status'] == 'pending') echo " selected"; ?>>Pending Review</option>
							  <option value="private" <? if($n['news_status'] == 'inactive') echo " selected"; ?>>Unpublished</option>
							</select></td>
						  </tr>
						 
						  <tr>
							<td>
							<strong>Publish Date</strong><br />
							<div id="calendarContainer" style="padding:5px 0px;" align="center"></div>
							<input type="text" name="publish_date" id="publish_date" value="<?=date('m/d/Y', $n['news_start'])?>" readonly="readonly" />
							<div class="mt10"></div>
							<div class="mt10"></div>
							<strong>Sticky?</strong><input type="checkbox" name="sticky" id="sticky" value="1" <? if($n['news_sticky']==1) echo " checked"; ?> /><br />
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
					</table></td>
				  </tr>
				</table></form>
                <script language="javascript">
						$(document).ready(function(){
						
							$("#calendarContainer").datepicker(
							{
								altField: '#publish_date',
								altFormat: 'mm/dd/yy',
								defaultDate: new Date('<?=date('m/d/Y', $n['news_start'])?>')
							}
							);
						  $("#calendarContainerEnd").datepicker(
							{
								altField: '#publish_date',
								altFormat: 'mm/dd/yy',
								defaultDate: new Date('<?=date('m/d/Y', $n['news_end'])?>')
							}
							);
							
						});
						</script>
				<?
				break;
				case 'manage':
				default:
					//display events 
				$sql = "SELECT * FROM news ORDER BY news_created DESC";
				$newsResults = dbQuery($sql);
					
					
				?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <br />
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
			                <tr>
				                <td>&nbsp;</td>
			                  <td width="100"><a class="button" href="<?=PAGE_PUBLISH?>?section=news"><span class="add">Add News</span></a></td>
		                    </tr>
		                  </table></td>
			            </tr>
			          </table>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr >
				          <td class="tableRowHeader" >&nbsp;</td>
				          <td class="tableRowHeader" align="left">Title</td>
				          <td class="tableRowHeader" align="left">Date</td>
				          <td class="tableRowHeader" align="left">Author</td>
				          <td class="tableRowHeader" align="left">Status</td>
				          <td class="tableRowHeader" align="left">&nbsp;</td>
			            </tr>
                        <?
						$count=0;
						while($nInfo = dbFetchArray($newsResults)) {
						$row = $count % 2;
						?>
				        <tr>
				          <td nowrap="nowrap" align="right"><? echo "<span class=\"smallGreenCaps\">".$nInfo['news_type']."</span>"; ?></td>
				          <td ><? if($nInfo['news_sticky']) echo "<img src=\"images/star.png\" alt=\"Sticky\">"; ?> <a href="<?=PAGE_MANAGE?>?action=edit&section=news&id=<?=$nInfo['news_id']?>"><?=output($nInfo['news_title'])?></a></td>
				          <td >
                          <?
						  echo date('m/d/Y', $nInfo['news_start']);
						  
						  ?>
                          </td>
				          <td ><?=getAuthor($nInfo['author'])?></td>
				          <td >
                          	<?
                            	if( $nInfo['news_status'] == 'pending') {
									$class = "textPending";	
								}else if($nInfo['news_status'] == 'inactive') {
									$class = "textInactive";
								}else{
									$class = "textActive";
								}
								echo "<span class=\"".$class."\">".$nInfo['news_status']."</span>\n";
							?> </td>
				          <td align="right"><a class="table_edit_link" href="<?=PAGE_MANAGE?>?action=edit&section=news&id=<?=$nInfo['news_id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&amp;section=news&amp;id=<?=$nInfo['news_id']?>" onclick="return confirm('Are you sure you want to delete this news item? this is NOT UNDOABLE');">Delete</a></td>
			            </tr>
                        <?
						$count++;
						}
						?>
		            </table>
                      </form></td>
			      </tr>
  </table>
                                 <? }
	break;
	case 'contest':
		switch($_GET['action']){
		case 'manage':
		default:
		?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td valign="top" class="formBody">
                          <br />
                          <form>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                    <td>&nbsp;</td>
                                  <td width="100"><a class="button" href="<?=PAGE_MANAGE?>?section=contest&action=upload"><span class="add">Upload Points</span></a></td>
                                </tr>
                              </table></td>
                            </tr>
                          </table>
                          <?php
						  //dispaly count
						  $countResults = dbQuery('SELECT points FROM user_points');
						  $pointCount = dbNumRows($countResults);
						  ?>
                         <div style="margin:10px;"><strong>Displaying the top 25 of 
                         <?=$pointCount?> results</strong></div>
                          
                          <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
                            <tr>
                              <td class="tableRowHeader"width="39%" align="left">SSN</td>
                              <td class="tableRowHeader" width="20%" align="left">Points</td>
                            </tr>
                            <?
							$pointResults = dbQuery('SELECT * FROM user_points ORDER BY points DESC LIMIT 25');
                            $count=0;
                            while($nInfo = dbFetchArray($pointResults)) {
                            $row = $count % 2;
                            ?>
                            <tr>
                              <td nowrap="nowrap"><?=$nInfo['user_ssn']?></td>
                              <td><?=number_format($nInfo['points'], 2, '.', ',')?></td>
                            </tr>
                            <?
                            $count++;
                            }
                            ?>
                        </table>
                      </form></td>
                      </tr>
  </table>
        <?
		break;
		case 'upload':
					?>
                    <form action="<?=PAGE_MANAGE?>" method="post" enctype="multipart/form-data" name="webpage" id="event">
                    <input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                    <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                    <table border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="785" valign="top" class="formBody">
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td class="pageTitleSub">Upload the point standings<br /><span class="smallText">The file <strong>MUST</strong> be in .csv format</span></td>
                            </tr>
                            <tr>
                              <td><input name="points_upload" type="file" class="textField-title" id="points_upload" /></td>
                            </tr>
                          </table>
                        </td>
                        <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                          <tr>
                            <td>
                            <div style="margin:0px 10px 10px 10px;">
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                              <tr>
                                <td class="headerCell"><h2>Upload Points</h2></td>
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
	break;
	case 'alerts':
		switch($_GET['action']) {
			case 'edit':
			//get info for the post!
			$newsResult = dbQuery("SELECT * FROM alerts WHERE alerts_id = " . $_GET['id']);
			$n = dbFetchArray($newsResult);
				?>
  <form id="event" name="webpage" method="post" action="<?=PAGE_MANAGE?>">
				<input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
				<input type="hidden" name="action" value="update" />
                <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                <table border="0" cellspacing="0" cellpadding="0" >
				  <tr>
					<td width="785" valign="top" class="formBody">
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
						<tr>
						  <td class="pageTitleSub">Title</td>
						</tr>
						<tr>
						  <td><input type="text" name="title" id="title" class="textField-title" style="width:650px" value="<?=output($n['alerts_title'])?>" /></td>
						</tr>
						
						<tr>
						  <td class="pageTitleSub">Content</td>
						</tr>
						<tr>
						  <td>
                          <? echo printEditorCB("content", "content", $n['alerts_content']); ?>
                        </td>
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
					</table></td>
				  </tr>
  </table></form>
                
				<?
				break;
				case 'manage':
				default:
					//display events 
				$sql = "SELECT * FROM alerts ORDER BY alerts_date_added";
				$alertResults = dbQuery($sql);
					
					
				?>
				<input name="end_calendar" type="text" class="textField-title" id="end_calendar" value="<?=$endDate?>"  />
				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <br />
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
			                <tr>
				                <td>&nbsp;</td>
			                  <td width="100"><a class="button" href="<?=PAGE_PUBLISH?>?section=alerts"><span class="add">Add Alert</span></a></td>
		                    </tr>
		                  </table></td>
			            </tr>
			          </table>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr>
				          <td class="tableRowHeader"width="39%" align="left">Title</td>
				          <td class="tableRowHeader" width="20%" align="left">Date</td>
				          <td class="tableRowHeader" width="10%" align="left">&nbsp;</td>
			            </tr>
                        <?
						$count=0;
						while($nInfo = dbFetchArray($alertResults)) {
						$row = $count % 2;
						?>
				        <tr>
				          <td nowrap="nowrap"><a href="<?=PAGE_MANAGE?>?action=edit&section=alerts&id=<?=$nInfo['alerts_id']?>"><?=output($nInfo['alerts_title'])?></a></td>
				          <td><? echo date('m/d/y', $nInfo['alerts_date_added']); ?></td>
				          <td align="right" ><a class="table_edit_link" href="<?=PAGE_MANAGE?>?action=edit&section=alerts&id=<?=$nInfo['alerts_id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&amp;section=alerts&amp;id=<?=$nInfo['alerts_id']?>" onclick="return confirm('Are you sure you want to delete this news item? this is NOT UNDOABLE');">Delete</a></td>
			            </tr>
                        <?
						$count++;
						}
						?>
		            </table>
                  </form></td>
			      </tr>
  </table>
                   <? }
	break;
	case 'event':
		switch($_GET['action']) {
			case 'edit':
			
			//get info for the post!
			$eventResult = dbQuery("SELECT * FROM calendar_events WHERE calendar_events_id = " . $_GET['id']);
			$e = dbFetchArray($eventResult);
			$startDate  = date('m/d/Y', $e['calendar_events_start_date']);
			if($e['calendar_events_end_date']) { $endDate = date('m/d/Y', $e['calendar_events_end_date']);  } else { $endDate = ''; }
			if($e['calendar_events_start_time']) { $startTime = date('h:i A', $e['calendar_events_start_time']); } else { $startTime = ''; }
			if($e['calendar_events_end_time']) { $endTime = date('h:i A', $e['calendar_events_end_time']); } else { $endTime = ''; }
			
			//check if event is in the db.. then select all keywords into csv!
			$catResults = dbQuery('SELECT * FROM calendar_events_categories WHERE calendar_events_id = ' . $_GET['id']);
			if(dbNumRows($catResults)) {
				while($cInfo = dbFetchArray($catResults)) {
					$keywords[] = $cInfo['calendar_events_categories_title'];
				}
			} else {
				$keywords = array();
			}	
			
		
				?>
				<form id="event" name="webpage" method="post" action="<?=PAGE_MANAGE?>" enctype="multipart/form-data">
				<input type="hidden" name="client" value="<?=$_SESSION['client_id']?>" />
				<input type="hidden" name="action" value="update" />
                <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                <input type="hidden" name="id" value="<?=$_GET['id']?>" />

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
                        	<td colspan="2"><strong>Do you want to modify the current event items? </strong><br /><span class="smallText">This will force you to redo your current dates/times</span>
                            <label><br />
                              <input type="radio" name="modifyRecurring" id="modifyRecurring" class="modRecur"  value="1" />
                              Yes, modify</label>
                            <label><input name="modifyRecurring" type="radio" class="modRecur" id="modifyRecurring_1" value="0" checked="checked" />No, do not modify</label>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                          <!-- Since we are editing the event.. we want to give the person the ability to not change any recurring events.. as they would have to always reconfigure the recurring event. -->
                          <div id="modifyRecurringEvent" style="display:none;">
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
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr> <tr>
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
                        </div>
                          <? 
						  $rEvents = dbQuery('SELECT * FROM calendar_events_items WHERE calendar_events_id = ' . $_GET['id']);
						  $recurCount = dbNumRows($rEvents);
						  if($recurCount) {
						  ?>
                          <!-- Show existing recurring emails -->
                          <h3>Current Recurring Dates</h3>
                          <div id="existingRecurring" style="max-height:300px; overflow:auto;">
                          <?
							//Multiple events! let's show em! yeaaahhh!
							echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" class=\"stripeTable\">\n";
							echo "<tr>\n";
							echo "<td class=\"tableRowHeader\">Start Date</td>\n";
							echo "<td class=\"tableRowHeader\">End Date</td>\n";
							echo "<td class=\"tableRowHeader\">Time</td>\n";
							echo "<td class=\"tableRowHeader\">&nbsp;</td>\n";
							echo "</tr>\n";
							while($rInfo = dbFetchArray($rEvents)) {
								echo "<tr>\n";
								echo "<td>".date('D F j, Y', $rInfo['calendar_events_items_start_date'])."</td>\n";
								echo "<td>";
								if($rInfo['calendar_events_items_end_date'] != 0) {
									echo date('D F j, Y', $rInfo['calendar_events_items_end_date']);
								}else{
									echo "&nbsp;";	
								}
								echo "</td>\n";
								echo "<td>";
								echo date('g:i a', $rInfo['calendar_events_items_start_time']);
								echo " - ";
								echo date('g:i a', $rInfo['calendar_events_items_end_time']);
								echo "</td>";
								echo "<td align=\"right\"><a href=\"\" class=\"table_delete_link\" onclick=\"return confirm('Are you sure you want to delete this event date?');\">delete</a></td>\n";
								echo "</tr>\n";
								
							}
							echo "</table>\n";
							?>
                             </div>
                             <!-- End existing recurring emails -->
                            <?
						  }
						  
						  
						  ?>
                          </td>
                        </tr>
					  </table>
					  <div class="mb20"></div>
					  <div class="mb20"></div>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
					    <tr>
					      <td class="pageTitleSub"><span class="fieldLabel">Event Image</span> <span class="smallText">250px x 200px</span><input type="file" name="event_image" id="event_image" /></td>
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
                       
						
					  </table>
					  
					 
					  
					<div class="mb20"></div></td>
					<td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
					  <tr>
						<td>
						<div style="margin:0px 10px 10px 10px;">
						
						<table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
						  <tr>
							<td class="headerCell"><h2>Publish Event</h2></td>
						  </tr><!--
                          <tr>
						    <td><label><input type="checkbox" name="calendar_events_featured" id="calendar_events_featured" value="1" <? if($e['calendar_events_featured']) echo " checked"; ?> />Feature Event?<br />
						     
						    </label>  </td>
					      </tr>-->
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
							  <option value="published" <? if($e['calendar_events_status'] == 'active') echo " selected"; ?>>Published</option>
							  <option value="pending" <? if($e['calendar_events_status'] == 'pending') echo " selected"; ?>>Pending Review</option>
							  <option value="private" <? if($e['calendar_events_status'] == 'inactive') echo " selected"; ?>>Unpublished</option>
							</select></td>
						  </tr>
						 
						  
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
												//console.log(keyToRemove);
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
				case 'delete':
					//confirm delete 
				break;
				case 'manage':
				default:
					//display events 
				$sql = "SELECT * FROM calendar_events ORDER BY calendar_events_date_added DESC";
				$eventResults = dbQuery($sql);
					
					
				?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <br />
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
			                <tr>
				                <td>&nbsp;</td>
			                  <td width="100"><a class="button" href="<?=PAGE_PUBLISH?>?section=event"><span class="add">Add Event</span></a></td>
		                    </tr>
		                  </table></td>
			            </tr>
			          </table>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr>
				          <td class="tableRowHeader" align="left">Title</td>
                          <td class="tableRowHeader" align="left">Start Date</td>
				          <td class="tableRowHeader" align="left">Recurring</td>
				          <td class="tableRowHeader" align="left">Author</td>
				          <td class="tableRowHeader" align="left">Status</td>
				          <td class="tableRowHeader" align="left">&nbsp;</td>
			            </tr>
                        <?
						$count=0;
						while($eInfo = dbFetchArray($eventResults)) {
						$row = $count % 2;
						?>
				        <tr>
				          
				          <td ><? if($eInfo['calendar_events_featured']) echo "<img src=\"images/star.png\" alt=\"Featured\">"; ?> <a href="<?=PAGE_MANAGE?>?action=edit&section=event&id=<?=$eInfo['calendar_events_id']?>"><?=output($eInfo['calendar_events_title'])?></a></td>
				          <td>
                          <?=date('M. j, Y',  $eInfo['calendar_events_start_date'])?>
                          </td>
                          <td>
                          <?php
						  if($eInfo['calendar_events_repeats'] == 1) { echo "Recurring"; } else { echo "Single Event"; }
						  ?>
                          </td>
				          <td ><?=getAuthor($eInfo['author'])?></td>
				          <td >
                          	<?
                            	if( $eInfo['event_status'] == 'pending') {
									$class = "textPending";	
								}else if($eInfo['event_status'] == 'inactive') {
									$class = "textInactive";
								}else{
									$class = "textActive";
								}
								echo "<span class=\"".$class."\">".$eInfo['calendar_events_status']."</span>\n";
							?> </td>
				          <td align="right" ><a class="table_edit_link" href="<?=PAGE_MANAGE?>?action=edit&section=event&id=<?=$eInfo['calendar_events_id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&amp;section=event&amp;id=<?=$eInfo['calendar_events_id']?>" onclick="return confirm('Are you sure you want to delete this event? this is NOT UNDOABLE');">Delete</a></td>
			            </tr>
                        <?
						$count++;
						}
						?>
		            </table>
                      </form></td>
			      </tr>
  </table>
				<br />
				<table border="0" cellspacing="0" cellpadding="3">
				  <tr>
				    <td><img src="images/star.png" width="16" height="16" /></td>
				    <td><strong>Featured Event</strong></td>
                  </tr>
  </table>
  	<?php
					
					
		break;
		}
	break;
	case 'article':
			
			switch($_GET['action']) {
				case 'edit':
				$articleResults = dbQuery("SELECT * FROM articles WHERE article_id = " . $_GET['id']);
				$a = dbFetchArray($articleResults);
				
						?>
						<form id="article" name="article" method="post" action="<?=PAGE_MANAGE?>">
						<input type="hidden" name="client" value="<?=$a['client_id']?>" />
						<input type="hidden" name="action" value="update" />
                        <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                        <input type="hidden" name="id" value="<?=$_GET['id']?>" />
						<table  border="0" cellspacing="0" cellpadding="0" >
						  <tr>
							<td width="785" valign="top" class="formBody">
							  <table width="100%" border="0" cellspacing="0" cellpadding="5">
								<tr>
								  <td class="pageTitleSub">Title</td>
								</tr>
								<tr>
								  <td><input type="text" name="title" id="title" class="textField-title" style="width:650px" value="<?=output($a['article_title'])?>" /></td>
								</tr>
								<tr>
								  <td class="pageTitleSub">Article URL</td>
								</tr>
								<tr>
								  <td>http://yoursite.com/article/ 
									<input type="text" name="url" id="url" class="textField-title" style="width:500px" readonly="readonly" value="<?=output($a['article_url'])?>" /></td>
								</tr>
								<tr>
								  <td class="pageTitleSub">Content</td>
								</tr>
								<tr>
								  <td>
                                   <? echo printEditorCB("content", "content", $a['article_text']); ?>
                                   
                                 </td>
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
								  <td><input type="text" name="seo_title" id="seo_title" class="textField-title" style="width:600px" value="<?=output($$a['article_seo_title'])?>" /></td>
								</tr>
								<tr>
								  <td class="fieldLabel">Description (max characters 160)</td>
								</tr>
								<tr>
								  <td><input type="text" name="seo_description" id="seo_description" class="textField-title" style="width:600px" value="<?=output($a['article_description'])?>" />
									<br />
									<strong>Character Count: 
									<input name="textfield5" type="text" class="textField-title" id="textfield5" style="width:30px;" />
									</strong></td>
								</tr>
								<tr>
								  <td class="fieldLabel">Keywords (comma seperated):</td>
								</tr>
								<tr>
								  <td class="fieldLabel"><input type="text" name="seo_keywords" id="seo_keywords" class="textField-title" style="width:600px" value="<?=output($article_seo_keyword)?>" /></td>
								</tr>
							  </table>
							  </div>
							  
							   <div class="mb20"></div>
							  <div class="expandable">
							  <div class="pageTitleSub">Page Order</div>
							  <table width="100%" border="0" cellspacing="0" cellpadding="5">
								<tr>
								  <td class="fieldLabel"><strong>
									<input name="sort_order" type="text" class="textField-title" id="sort_order" style="width:30px;" value="<?=$a['article_sort_order']?>" />
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
								  $authorResults =dbQuery("SELECT ui.*, u.* FROM user AS u, user_information AS ui WHERE ui.user_id = u.user_id AND u.client_id = " . $_SESSION['client_id']);
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
									  <option value="published" <? if($a['article_status']=='active') echo " selected"; ?>>Published</option>
									  <option value="pending" <? if($a['article_status']=='pending') echo " selected"; ?>>Pending Review</option>
									  <option value="private" <? if($a['article_status']=='inactive') echo " selected"; ?>>Unpublished</option>
									</select></td>
								  </tr>
								 
								  <tr>
									<td>
									<div class="mt10"></div>
									<strong>Published on:</strong>
									  <?=date("F j, Y", $a['article_publish_date'])?> <a href="javascript:void(0);" id="toggleDate" style="font-size:10px; font-weight:normal; color:#CC0000;">(change)</a>
									  <div id="publishDate" style="display:none;">
									  <div id="calendarContainer"></div>
									  <div class="mt10"></div>
									  <input type="text" name="publish_date" id="publish_date" value="<?=date('m/d/Y', $a['article_publish_date'])?>" readonly="readonly" />
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
                        <script language="javascript">
						$(document).ready(function(){
						
							$("#calendarContainer").datepicker(
							{
								altField: '#publish_date',
								altFormat: 'mm/dd/yy',
								defaultDate: new Date('<?=date('m/d/Y', $a['article_publish_date'])?>')
							}
							);
						  
							
						});
						</script>
						
						<?
				break;
				case 'manage':
				default:
				$sql = "SELECT * FROM articles ORDER BY article_added ASC";
				$articleResults = dbQuery($sql);
					
					
				?>
  				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <br />
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
				            <tr>
				              <td>&nbsp;</td>
				              <td width="100"><a class="button" href="<?=PAGE_PUBLISH?>?section=event"><span class="add">Add Article</span></a></td>
			                </tr>
			              </table></td>
			            </tr>
			          </table>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="stripeTable">
				        <tr >
				          <td class="tableRowHeader" width="1%"><input type="checkbox" name="checkAll" id="checkAll" /></td>
				          <td class="tableRowHeader"width="39%" align="left">Title</td>
				          <td class="tableRowHeader" width="20%" align="left">Publish Date</td>
				          <td class="tableRowHeader" width="20%" align="left">Author</td>
				          <td class="tableRowHeader" width="10%" align="left">Date Added</td>
				          <td class="tableRowHeader" width="10%" align="left">&nbsp;</td>
			            </tr>
                        <?
						$count=0;
						while($aInfo = dbFetchArray($articleResults)) {
						$row = $count % 2;
						?>
				        <tr>
				          <td ><input type="checkbox" name="checkbox" id="checkbox" /></td>
				          <td ><? if($a['article_sticky']) echo "<img src=\"images/star.png\" alt=\"Sticky\">"; ?><a href="<?=PAGE_MANAGE?>?action=edit&section=article&id=<?=$aInfo['article_id']?>"><?=output($aInfo['article_title'])?></a></td>
				          <td >
                          <?
						  echo date('m/d/Y', $aInfo['article_publish_date']);
						 
						  ?>
                          </td>
				          <td ><?=getAuthor($aInfo['author'])?></td>
				         <td >
                          <?
						  echo date('m/d/Y', $aInfo['article_added']);
						 
						  ?>
                          </td>
				         <td align="right" >
				         	<a class="table_edit_link" href="<?=PAGE_MANAGE?>?action=edit&section=article&id=<?=$aInfo['article_id']?>">Edit</a> 
				         	<a class="table_delete_link" href="<?=PAGE_MANAGE?>?action=delete&amp;section=article&amp;id=<?=$aInfo['article_id']?>" onclick="return confirm('Are you sure you want to delete this article? this is NOT UNDOABLE');">Delete</a>
				         </td>
			            </tr>
                        <?
						$count++;
						}
						?>
		            </table>
                      </form></td>
			      </tr>
  </table>
  <br />
  <table border="0" cellspacing="0" cellpadding="3">
    <tr>
      <td><img src="images/star.png" width="16" height="16" /></td>
      <td><strong>Sticky</strong></td>
    </tr>
  </table>
				<?
				break;
			}
	break;
	case 'webpage':
	default:
	
			switch($_GET['action']) {
			case 'edit':
			
			$pageResults = dbQuery('SELECT * FROM page_content WHERE page_content_id = ' . $_GET['id']);
			$p = dbFetchArray($pageResults);
						?>
                      <form action="<?=PAGE_MANAGE?>" method="post" enctype="multipart/form-data" name="webpage" id="contentForm">
                        <input type="hidden" name="client" value="<?=$p['client_id']?>" />
                        <input type="hidden" name="action" value="update" />
                        <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                        <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                       
			          </table>
						<table border="0" cellspacing="0" cellpadding="0" >
						  <tr>
							<td width="785" valign="top" class="formBody">
							  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                               <tr>
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
                                  <? echo printEditorCB("content", "content", $p['page_content_text']); ?>
                                 </td>
								</tr>
							  </table>
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
                                  	<input type="hidden" name="parent" id="parent" value="<?=$p['parent']?>" />
                                    <dl id="mirror_parent" class="select_dropdown_parent">
										<?php
                                        recurse_navigation_li_top($p['parent']);
                                        recurse_navigation_li($p['parent']);
                                        recurse_navigation_li_botttom();
                                        ?>
                                    </dl>
                                    
								  </td>
								</tr>
							  </table>
							  </div>          
							  
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
								    <input type="text" name="custom_url" id="custom_url" class="textField-title" style="width:600px" value="<?=output($p['custom_url'])?>" />
								    </span></td>
							    </tr>
                                 <tr>
                              <td><label>Show in main navigation?<input type="checkbox" name="page_content_show_in_menu" id="page_content_show_in_menu" value="1" <?php if($p['page_content_show_in_menu']) echo " checked"; ?> /></label></td>
                            </tr>
								<tr>
								  <td class="fieldLabel">SEO Title</td>
							    </tr>
								<tr>
								  <td><span class="fieldLabel">
								    <input type="text" name="seo_title" id="seo_title" class="textField-title" style="width:600px" value="<?=output($p['page_content_seo_title'])?>" />
								    </span></td>
							    </tr>
								<tr>
								  <td class="fieldLabel">SEO Description (max characters 160) </td>
							    </tr>
								<tr>
								  <td><input type="text" name="seo_description" id="seo_description" class="textField-title" style="width:600px" value="<?=output($p['page_content_seo_description'])?>" />
								    <br /></td>
							    </tr>
								<tr>
								  <td class="fieldLabel">SEO Keywords (comma seperated):</td>
							    </tr>
								<tr>
								  <td class="fieldLabel"><input type="text" name="seo_keywords" id="seo_keywords" class="textField-title" style="width:600px" value="<?=output($p['page_Content_seo_keywords'])?>" /></td>
							    </tr>
								<tr>
								  <td class="fieldLabel">&nbsp;</td>
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
									<input name="sort_order" type="text" class="textField-title" id="sort_order" style="width:30px;" value="<?=$p['page_content_sort_order']?>" />
								  </strong></td>
								</tr>
							  </table>
							  </div>
							  <div class="mb20"></div>
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
                                    <input name="member" type="checkbox" id="member" value="1" <? if($p['page_content_member'] == 1) echo " checked"; ?> />
                                </label></td>
                              </tr>
                                 -->
								  <tr>
									<td><strong>Status</strong></td>
								  </tr>
								  <tr>
									<td>
                                    <select name="status" id="status">
									  <option value="published" <? if($p['page_content_status'] == 'published') echo " selected"; ?> >Published</option>
									  <option value="pending" <? if($p['page_content_status'] == 'pending') echo " selected"; ?> >Pending Review</option>
									  <option value="private" <? if($p['page_content_status'] == 'private') echo " selected"; ?> >Unpublished</option>
									</select></td>
								  </tr>
								 
								  <tr>
									<td><strong>Published on:</strong><br />
									  <?=date("F j, Y, g:i a", time())?> <a href="javascript:void(0);" id="toggleDate" style="font-size:10px; font-weight:normal; color:#CC0000;">(change)</a>
									  <div id="publishDate" style="display:none;">
									  <div id="calendarContainer" style="padding:5px 0px;"></div>
									  <div class="mt10"></div>
									  <input type="text" name="publish_date" id="publish_date" value="<?=date('d/m/Y', $p['page_content_publish_date'])?>" readonly="readonly" />
									  </div><script language="javascript">
					  $(document).ready(function() {
						 $("#calendarContainer").datepicker(
							{
							altField: '#publish_date',
							altFormat: 'mm/dd/yy',
							defaultDate: new Date('<?=date('m/d/Y', $p['page_content_publish_date'])?>')
							}
						);
					  });
					  </script>
									</td>
								  </tr>
								  <tr>
									<td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="Save" /></td>
								  </tr>
								</table>
								</div>
								</td>
							  </tr>
							</table> <p>&nbsp;</p></td>
						  </tr>
						</table></form>
			          </table>
                      
						<?
			break;
			
			case 'manage':
			default:
			
				?>
  				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
				    <td valign="top" class="formBody">
				      <br />
				      <form>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
				        <?
						if($_SESSION['god'])  {
						?>
                        <tr>
				          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
				            <tr>
				              <td>&nbsp;</td>
				              <td width="100"><a class="button" href="<?=PAGE_PUBLISH?>?section=webpage&parent="><span class="add">Add Page</span></a></td>
			                </tr>
			              </table></td>
			            </tr>
                        <? } ?>
			          </table>
				      <table border="0" cellspacing="0" width="100%" cellpadding="5" class="stripeTable">
				        <tr >
				          <td class="tableRowHeader" ><input type="checkbox" name="checkAll" id="checkAll" /></td>
				          <td class="tableRowHeader"  align="left">Publish Date</td>
                          <td class="tableRowHeader" align="left">Title</td>
				          <td class="tableRowHeader"  align="left">Status</td>
				          <td class="tableRowHeader"  align="left">&nbsp;</td>
				          <td class="tableRowHeader"  align="left">&nbsp;</td>
			            </tr>
                        <?
						recurse_pages();
						?>
		            </table>
                </form>
                </td>
			  </tr>
  			</table>
                  <?
			break;
		}
	break;
	
	}
?>

</div>
</body>
</html>
