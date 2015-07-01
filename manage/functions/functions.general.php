<?php

function redirect($url) {
	session_write_close();
	header('location: '.$url);	
}
function addMessage($msg){
	if(session_id() == "")
	    return;
	if(!isset($_SESSION["message"]
						) || $_SESSION["message"] == "")
		$_SESSION["message"] = $msg;
 	else
		$_SESSION["message"] .= "<br>" . $msg;
}
function showMessage(){
	if($_SESSION["message"] != ""){
	    print "<div class='infoBox'>" . $_SESSION["message"] . "</div>\n";
	    $_SESSION["message"] = "";
	}
}
function messageQue() {
	if(	$_SESSION['message'] != "") {
	return true;	
	}else{
	return false;	
	}
}

function fixTitle($text) {
	$text = strtolower(trim($text));
	// replace all white space sections with a dash
	$text = str_replace(' ', '-', $text);
	// strip all non alphanum or -
	$clean = ereg_replace("[^A-Za-z0-9\-]", "", $text);
	
	return $clean;
}

function create_page_url($url)
{
	$text = strtolower(trim($url));
	// replace all white space sections with a dash
	$text = str_replace(' ', '-', $text);
	// strip all non alphanum or -
	$cleanedUrl = ereg_replace("[^A-Za-z0-9\-]", "", $text);
	
	return $cleanedUrl;
}

function printEditorCB($id, $name, $value, $params=""){
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "<tr>\n";
	echo "<td valign=\"top\" width=\"72\">";
	echo "<div id=\"content_blocks\">\n";
	echo "<div class=\"contentBlocksTitle\">Content<br />Blocks</div>\n";
	echo "<ul>\n";
	//eventually these will be dynamic! but for now they aren't :(
	
	echo "<li><a href=\"javascript:addBlock(6);\" class=\"simpletip\" rel=\"Text Block<br>(click to add)\"><img src=\"images/communication/block_1col_1row.jpg\" border=\"0\" /></a></li>\n";
	echo "<li><a href=\"javascript:addBlock(3);\" class=\"simpletip\" rel=\"2 Column<br> Image Left<br>(click to add)\"><img src=\"images/communication/block_2col_img_left.jpg\" width=\"59\" border=\"0\" height=\"31\" /></a></li>\n";
	echo "<li><a href=\"javascript:addBlock(4);\" class=\"simpletip\" rel=\"2 Column<br> Image Right<br>(click to add)\"><img src=\"images/communication/block_2col_img_right.jpg\" border=\"0\" width=\"59\" height=\"31\" /></a></li>\n";
	echo "<li><a href=\"javascript:addBlock(7);\" class=\"simpletip\" rel=\"2 Column<br> Text<br>(click to add)\"><img src=\"images/communication/block_2col.jpg\" border=\"0\" width=\"59\" height=\"36\" /></a></li>\n";
	echo "<li><a href=\"javascript:addBlock(5);\" class=\"simpletip\" rel=\"3 Column<br>(click to add)\"><img src=\"images/communication/block_3_col.jpg\" border=\"0\" width=\"59\" height=\"36\" /></a></li>\n";
	   
	echo "</ul>\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "<td valign=\"top\">\n";
	echo "<div id=\"templateBody\"><textarea class=\"mceEditor\" name=\"".$name."\" id=\"".$id."\">".output(html_entity_decode($value))."</textarea></div>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	
	
}


function getSetting($key) {
	$results = dbQuery('SELECT store_settings_value FROM store_settings WHERE store_settings_key = "'.$key.'"');
	$row = dbFetchArray($results);
	return $row['store_settings_value'];
}
function getGeneralSetting($key) {
	$results = dbQuery('SELECT settings_value FROM settings WHERE settings_key = "'.$key.'"');
	$row = dbFetchArray($results);
	return $row['settings_value'];
}


function printMessage(){
	showMessage();
}

function addError($msg){
	if(session_id() == "")
	    return;
	if(!isset($_SESSION["error"]) || $_SESSION["error"] == "")
		$_SESSION["error"] = $msg;
 	else
		$_SESSION["error"] .= "<br>" . $msg;
}
function showError(){
	if($_SESSION["error"] != ""){
	    print "<div class='alertBox'>" . $_SESSION["error"] . "</div>\n";
	    $_SESSION["error"] = "";
	}
}
function errorQue() {
	if(	$_SESSION['error'] != "") {
	return true;	
	}else{
	return false;	
	}
}
function printError(){
	showError();
}

function input($str){
	$str = trim($str);
	$str = htmlentities($str);
	$str = remove_empty_p_tags($str);
	return $str;
}

function remove_empty_p_tags($str) {
	$result = preg_replace('#<p[^>]*>(\s|&nbsp;?)*</p>#', '', $str);
	return $str;
}

function output($str, $striptags=false) {
	$str = html_entity_decode($str);
	$str = stripslashes($str);
	if($striptags)
		$str = strip_tags($str);
	return $str;

}

function selfURL() { 
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; 
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); return 
	$protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
} 
function strleft($s1, $s2) { 
	return substr($s1, 0, strpos($s1, $s2)); 
}

function get_article_count($status="") {

	if($status != "") {
		$where = 'WHERE article_status  = "' . $status . '"';
	} else {
		$where = "";
	}

	$articles = dbQuery('SELECT article_id FROM articles ' .$where);
	return dbNumRows($articles);
}

function print_help_tooltip($txt="") {
	echo "<a href=\"javascript:void(0);\" title=\"".$txt."\"><img src=\"images/icons/help_16x16.gif\" border=\"0\" width=\"16\" height=\"16\" /></a>\n";	
}

function get_news_count($status="") {
	
	if($status != "") {
		$where = 'WHERE news_status = "' . $status . '"';	
	} else {
		$where = "";
	}
	
	$news = dbQuery('SELECT news_id  FROM news ' .$where);
	return dbNumRows($news);
}

function get_event_count($status="") {
	if($status != "") {
		$where = 'WHERE event_status  = "' . $status . '"';
	} else {
		$where = "";
	}
	$events = dbQuery('SELECT event_id  FROM events ' .$where);
	return dbNumRows($events);
}

function recurse_navigation_li_top($id=0) {
	if($id == 0) {
		$name = "None";
		$value = "0";
	} else {
		$sql = "SELECT page_content_title, page_content_id FROM page_content WHERE page_content_id = ".$id;
		$pageResults = dbQuery($sql);
		$pInfo = dbFetchArray($pageResults);
		$name = $pInfo['page_content_title'];
		$value = $pInfo['page_content_id'];
		
	}
	
	echo "<dt>\n";
	echo "<a href=\"javascript:void(0);\">".output($name)."<span class=\"value\">".output($value)."</span></a>\n";
	echo "</dt>\n";
	echo "<dd>\n";
	echo "<ul>\n";
	echo "<li><a href=\"javascript:void(0);\">None<span class=\"value\">0</span></a></li>\n";
}

function recurse_navigation_li_botttom() {
	echo "</ul>\n";
	echo "</dd>\n";
}


function recurse_navigation_li($selected=0, $id=0, $level=0) {
  $level++;
  $sql = "SELECT * FROM page_content WHERE parent = ".$id." ORDER BY page_content_added ASC";
  $pageResults = dbQuery($sql);
  $count = 0;
  while($pInfo = dbFetchArray($pageResults)) {
	  $padding = 18 * $level;
	  if($level != 1) {
		$style = "style=\"padding-left:".($padding+18)."px; background-repeat:no-repeat; background-position:".$padding."px 0px; background-image:url(images/directory_arrow.gif);\"";
		
	  }
	  
		
		echo "<li ".$style."><a href=\"#\">".output($pInfo['page_content_title'])."<span class=\"value\">".output($pInfo['page_content_id'])."</span></a></li>\n";
	  
	 // echo "<option ".$style." value=\"".$pInfo['page_content_id']."\"";
	//	  if($selected == $pInfo['page_content_id']) echo " selected";
	//  echo ">".$pInfo['page_content_title']."</option>\n";
	
	recurse_navigation_li($selected, $pInfo['page_content_id'], $level);
	$count++;
	}
	
}

function recurse_navigation_select($selected=0, $id=0, $level=0) {
  $level++;
  $sql = "SELECT * FROM page_content WHERE parent = ".$id." ORDER BY page_content_added ASC";
  $pageResults = dbQuery($sql);
  $count = 0;
  while($pInfo = dbFetchArray($pageResults)) {
	  $padding = 18 * $level;
	  if($level != 1) {
		$style = "style=\"padding-left:".($padding+18)."px; background-repeat:no-repeat; background-position:".$padding."px 0px; background-image:url(images/directory_arrow.gif);\"";
		
	  }
	  echo "<option ".$style." value=\"".$pInfo['page_content_id']."\"";
		  if($selected == $pInfo['page_content_id']) echo " selected";
	  echo ">".$pInfo['page_content_title']."</option>\n";
	
	recurse_navigation_select($selected, $pInfo['page_content_id'], $level);
	$count++;
	}
}

function page_has_children($pageID) {
	$results = dbQuery('SELECT page_content_id FROM page_content WHERE parent = ' . $pageID);	
	return (dbNumRows($results));
}

function recurse_pages($id=0, $level=0) {
 $level++;
  $sql = "SELECT * FROM page_content WHERE parent = ".$id." ORDER BY page_content_title ASC";
  $pageResults = dbQuery($sql);
  $count = 0;
  while($pInfo = dbFetchArray($pageResults)) {
	  $padding = 18 * $level;
	  if($level != 1) {
		$style = "style=\"padding-left:".($padding+18)."px; background-repeat:no-repeat; background-position:".$padding."px 0px; background-image:url(images/directory_arrow.gif);\"";
	  } else {
		$class = "";  
	  }
	  
	  //SECURITY CHECK
	  //ONLY SHOW PAGES THAT THE USER HAS ACCESS TOO
	  
		$row = $count % 2;
		echo "<tr>\n";
		echo "<td  nowrap width=\"1\">\n";
		if($pInfo['page_content_member'])  echo "<a href=\"javascript:void(0);\" title=\"Membership Required\"><img src=\"images/icons/lock_16x16.gif\" border=\"0\"><a>";
		echo "</td>\n";
		echo "<td nowrap width\"1\">".date('m/d/Y', $pInfo['page_content_publish_date'])."</td>\n";
		echo "<td ".$style."><a href=\"".PAGE_MANAGE."?action=edit&section=webpage&id=".$pInfo['page_content_id']."\">".output($pInfo['page_content_title'])."</a></td>\n";
		echo "<td nowrap width\"1\">";
		
		if($pInfo['page_content_status'] == 'pending') {
			echo "<span class=\"textPending\">Pending</span>";  
		  } else if ($pInfo['page_content_status'] == 'published') {
			echo "<span class=\"textActive\">Published</span>";
		  } else {
			echo "<span class=\"textInactive\">Unpublished</span>";
		} 
		
		echo "</td>\n";
		echo "<td nowrap width\"1\">\n";
		echo "<span class=\"smallText\"><abbr title=\"by ".getAuthor($pInfo['page_content_author']) ."\" style=\"margin:2px;\">Created: ".date('m/d/y g:i a', $pInfo['page_content_added'])." </abbr></span>\n";
		//check to see if this page has been edited 
		//display
		$modifiedResults = dbQuery('SELECT * FROM page_content_log WHERE page_content_id = ' . $pInfo['page_content_id'] . ' LIMIT 1');
		
		if(dbNumRows($modifiedResults)){
			$m=dbFetchArray($modifiedResults);
			echo "<br>";
			echo "<span class=\"smallText\" style=\"font-style:italic;\"><abbr style=\"margin:2px;\" title=\"by ".getAuthor($m['user_id'])."\">Last Modified: ".date('m/d/y g:i a', $m['page_content_log_timestamp'])."</abbr></span>";
		}
		
		
		echo "</td>\n";
		echo "<td align=\"right\" >";
		
		
		if($level==1) {
			echo "<a class=\"table_addsubpage_link\" href=\"".PAGE_PUBLISH."?section=webpage&parent=".$pInfo['page_content_id']."\" title=\"Add Sub Page\">Add Subpage</a>";
			echo " ";
		}
		
		if(user_has_permission('banners')) { 
			//echo "<a class=\"table_banner_link\" href=\"".PAGE_MANAGE."?action=banners&section=webpage&id=".$pInfo['page_content_id']."\" title=\"Add Banner\">Advert</a>\n";
			//echo " ";
		}
		if(user_has_permission('content') && user_has_page_permissions($pInfo['page_content_id'])) { 
			echo "<a class=\"table_edit_link\" href=\"".PAGE_MANAGE."?action=edit&section=webpage&id=".$pInfo['page_content_id']."\" title=\"Edit " . output($pInfo['page_content_title']) . "\">Edit</a>\n";
			echo " ";
		}
		
		if(user_has_permission('admin') ) { 
			echo "<a class=\"table_delete_link\" href=\"".PAGE_MANAGE."?action=delete&section=webpage&id=".$pInfo['page_content_id']."\" title=\"Delete " . output($pInfo['page_content_title']) . "\" onclick=\"return confirm('Are you sure you want to delete this page? THIS IS NOT UNDOABLE');\">Delete</a>\n";
		}
		
		echo "</td>\n";
		echo "</tr>\n";
		
		
	
	recurse_pages($pInfo['page_content_id'], $level);
	$count++;
	}
}



function print_download_files($category=0){
	//display downloads section files
	
	
	
	
}


?>