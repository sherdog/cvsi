<?
include('master.inc.php');
include('application.php');

if(!$_SESSION['user_logged_in'] || !isset($_SESSION['client'])){
	addError('Your session has timed out please login again');
	redirect(PAGE_LOGIN);
}
$trail = new breadcrumb();//start breadcrumb class
$trail->add("Dashboard", PAGE_DEFAULT);
$trail->add("Storefront", PAGE_STORE);
$page = $_GET['section'];

if($_POST['action'] == 'updateorder') {
	//we are going to update the order! most likely just the status of the order
	//maybe even notify the customer!
	
	$row['orders_status'] = $_POST['orders_status'];
	if($_POST['orders_message'] != "") {
		$row['orders_message'] = $_POST['orders_message'];
	}
	if($_POST['orders_tracking_number'] != "") {
		$row['orders_tracking_number'] = $_POST['orders_tracking_number'];
	}
	
	if($_POST['orders_status'] == 'complete'){
		$row['orders_complete_date'] = time();	
	}
	dbPerform('store_orders', $row, 'update', 'orders_id = ' .$_POST['id']);
	
	//now we get order email!
	$orderResults = dbQuery('SELECT * FROM store_orders WHERE orders_id = ' . $_POST['id']);
	$oInfo = dbFetchArray($orderResults);
	
	$getContentResults = dbQuery('SELECT * FROM email_templates WHERE email_templates_id = 4');
	$c = dbFetchArray($getContentResults);
	
	
	$content = $c['email_templates_header'];
	$content .= "The status of your order number " . $oInfo['orders_time'] . " has been updated<br><br>";
	$content .= "<strong>Order Status</strong>: " . $oInfo['orders_status']."<br>";
	if($oInfo['orders_tracking_number'] != "")
		$content .= "<strong>Tracking Number</strong>:".$oInfo['orders_tracking_number']."<br>";
	if($oInfo['orders_message'] != "")
		$content .= "<strong>Message</strong>:".$oInfo['orders_message']."<br>";
	$content .= $c['email_templates_footer'];
	
	if(isset($_POST['notify'])) { //email user an order update
	include('classes/class.phpmailer.php');
		//get store email settings!
		//setup class email
		$mail = new PHPMailer();
		$mail->From     = getSetting('order_email_from');
		$mail->FromName = getSetting('order_email_from');
		$mail->isMail 	= true;
		$mail->Body    	= stripslashes(html_entity_decode($content));
		$mail->AddAddress($oInfo['orders_customer_email']);
		$mail->Subject = COMPANY_NAME." Order Update " . $oInfo['orders_time'];
		$mail->ContentType = 'text/html';
		$mail->Send();
		$mail->ClearAddresses();
	}
	
	$message = "Updated order";
	if(isset($_POST['notify'])) {
		$message .= " notified customer with order status";
	}	
	
	addMessage($message);
	redirect(PAGE_STORE."?section=orders");
	
}

if($_POST['action'] == 'addcategory' || $_POST['action'] == 'editcategory') {
	$row['categories_title'] = input($_POST['categories_title']);
	$row['categories_desc'] = input($_POST['categories_desc']);
	$row['categories_display'] = ($_POST['categories_display']) ? $_POST['categories_display']  : 0;
	$row['categories_enable_purchase'] = ($_POST['categories_enable_purchase']) ? $_POST['categories_enable_purchase'] : 0;
	
	if($_FILES['image']['name'] != '') {//upload image yo!
		$filename = time().fixFilename($_FILES['image']['name']);
		uploadFile($_FILES['image'], $filename);
		makeThumbnail($filename, UPLOAD_DIR, 600, '', 'large');
		makeThumbnail($filename, UPLOAD_DIR, 480, '', 'medium');
		makeThumbnail($filename, UPLOAD_DIR, 320, '', 'small');
		makeThumbnail($filename, UPLOAD_DIR, 200, '', 'thumb');
		
		$row['categories_image'] = $filename;
	}
	
	if($_POST['action'] == 'addcategory') {
		$row['categories_date_added'] = time();
		dbPerform('store_categories', $row, 'insert');
		$message = "Added category successfully";
	}
	if($_POST['action'] == 'editcategory') {
		dbPerform('store_categories', $row, 'update', 'categories_id = ' . $_POST['id']);
		$message = "Saved category "  . $_POST['categories_title'] . " successfully";
	}
	addMessage($message);
	redirect(PAGE_STORE."?action=manage&section=categories");
	
	
}

if($_GET['action'] == 'deletespecial') {
	
	dbQuery('DELETE FROM store_products_specials WHERE products_specials_id = ' . $_GET['id']);
	
	addMessage("Deleted special successfully");
	redirect(PAGE_STORE."?section=specials");
	
}
if($_GET['action'] == 'deleteorder') {
	
	dbQuery('DELETE FROM store_orders WHERE orders_id = ' . $_GET['id']);
	dbQuery('DELETE FROM store_orders_products WHERE orders_id = ' . $_GET['id']);
	
	addMessage("Deleted order successfully");
	redirect(PAGE_STORE."?section=orders");
	
}


if($_GET['action'] == 'deletecategory') {
	//check if category has products
	$error = false;
	
	$results = dbQuery('SELECT * FROM store_products WHERE categories_id = ' . $_GET['id']);
	if(dbNumRows($results))
		$error = true;
	
	if(!$error) {
		dbQuery('DELETE FROM store_categories WHERE categories_id = ' . $_GET['id']);
		addMessage("Deleted category successfully");
	} else {
		addError("Whoops, your must delete the products within the category before you can delete it");
	}
	
	redirect(PAGE_STORE."?section=categories");
	
}

if($_POST['action'] == 'addspecial' || $_POST['action'] == 'editspecial') {
	/*
	Array ( [action] => addspecial [id] => [products_specials_title] => St. Patricks Day [products_specials_discount] => 20 [products_specials_discount_type] => percentage [
	products_specials_shipping] => 1 [products_specials_description] => gdfgdfgdf [products_calendar_date_start] => 05/28/2009 [products_calendar_date_end] => 31/12/1969 
	[button] => Add Special ) 
	*/
	$row['products_specials_title'] = $_POST['products_specials_title'];
	$row['products_specials_discount'] = $_POST['products_specials_discount'];
	$row['products_specials_discount_type'] = $_POST['products_specials_discount_type'];
	$row['products_specials_description'] = $_POST['products_specials_description'];
	$row['products_specials_shipping'] = $_POST['products_specials_shipping'];
	$row['category_id'] = $_POST['categories'];
	$date1 = explode('/', $_POST['products_calendar_date_start']);
	$date2 = explode('/', $_POST['products_calendar_date_end']);

	$row['products_specials_date_start'] = mktime(0, 0, 0, $date1[0], $date1[1], $date1[2]);
	$row['products_specials_date_end'] = mktime(23, 59, 59, $date2[0], $date2[1], $date2[2]);
	
	if($_POST['action'] == 'addspecial') {
		dbPerform('store_products_specials', $row, 'insert');
		addMessage("Added special successfully");
	}
	if($_POST['action'] == 'editspecial') {
		dbPerform("store_products_specials", $row, 'update', 'products_specials_id='.$_POST['id']);
		addMessage("Saved special successfully");
		
	}
	
	redirect(PAGE_STORE."?section=specials");
	
	
}
if($_POST['action'] == 'addproduct' || $_POST['action'] == 'editproduct') {

	$row['products_title'] = input($_POST['products_title']);
	$row['products_price'] = (float)$_POST['products_price'];
	$row['products_stock_number'] = $_POST['products_stock_number'];
	$row['categories_id'] = $_POST['c'];
	$row['products_status'] = $_POST['products_status'];
	$row['products_featured'] = ($_POST['products_featured']) ? $_POST['products_featured'] : 0;
	$row['products_release_date'] = strtotime($_POST['products_release_date']);
	$row['products_flat_shipping_price'] = $_POST['products_flat_shipping_price'];
	
	$info['products_info_desc'] = $_POST['products_info_desc'];
	$info['products_info_custom_1'] = ($_POST['products_info_custom_1']) ? $_POST['products_info_custom_1'] : '';
	$info['products_info_custom_2'] = ($_POST['products_info_custom_2']) ? $_POST['products_info_custom_2'] : '';
	$info['products_info_custom_3'] = ($_POST['products_info_custom_3']) ? $_POST['products_info_custom_3'] : '';
	$info['products_info_custom_4'] = ($_POST['products_info_custom_4']) ? $_POST['products_info_custom_4'] : '';
	$info['products_info_custom_5'] = ($_POST['products_info_custom_5']) ? $_POST['products_info_custom_5'] : 0;
	
	if($_FILES['file']['name'] != "") {
		$filename = fixFilename($_FILES['file']['name']);
		
		uploadFile($_FILES['file'], $filename); 		
		$info['products_info_custom_6'] = $filename;
	} 
	
	if($_FILES['spec']['name'] != "") {
		$filename = fixFilename($_FILES['spec']['name']);
		
		uploadFile($_FILES['spec'], $filename);
		makeThumbnail($filename, UPLOAD_DIR, 150, '', 'small');
		makeThumbnail($filename, UPLOAD_DIR, 250, '', 'medium');
		makeThumbnail($filename, UPLOAD_DIR, 500, '', 'large');
		makeThumbnail($filename, UPLOAD_DIR, 800, '', 'xlarge');

		$info['products_info_custom_7'] = $filename;
		
		
	}
	
	if($_POST['action'] == 'addproduct') {
	
		$row['products_date_added'] = time();
		dbPerform('store_products', $row, 'insert');
		$productID  = dbInsertID();
		$info['products_id'] = $productID;
		dbPerform('store_products_info', $info, 'insert');
		
		$message = "Added " . $_POST['products_title'] . " successfully";
		
	
	} 
	
	if($_POST['action'] == 'editproduct') {
		$productID = $_POST['id'];
		dbPerform('store_products', $row, 'update', 'products_id = ' . $productID);
		dbPerform('store_products_info', $info, 'update', 'products_id = ' . $productID);
		$message = "Updated " . $_POST['products_title'] . " successfully";
		
	}
	
	if($_FILES['image']['name'] != "") {
		$filename = fixFilename($_FILES['image']['name']);
		
		uploadFile($_FILES['image'], $filename);
		
		makeThumbnail($filename, UPLOAD_DIR, 150, '', 'small');
		makeThumbnail($filename, UPLOAD_DIR, 250, '', 'medium');
		makeThumbnail($filename, UPLOAD_DIR, 500, '', 'large');
		makeThumbnail($filename, UPLOAD_DIR, 800, '', 'xlarge');
		
		$img['products_id'] = $productID;
		$img['products_images_title'] = $filename;
		$img['products_images_default'] = 1;
		$img['products_images_filename'] = $filename;
		
		if($_POST['action'] == 'editproduct') {
			dbPerform('store_products_images', $img, 'update', 'products_id = ' . $productID);
		} else {
			dbPerform('store_products_images', $img, 'insert');
		}
	} //we are going to upload an image for this product!
	addMessage($message);
	redirect(PAGE_STORE."?section=products&c=".$_POST['c']);

}

if($_GET['action'] == 'setinactive') {
	$row['products_status'] = 'inactive';
	dbPerform('store_products', $row, 'update', 'products_id='.$_GET['id']);
	addMessage("Set product to inactive successfully");
	redirect(PAGE_STORE."?section=products&c=".$_GET['c']);
}
if($_GET['action'] == 'setactive') {
	$row['products_status'] = 'active';
	dbPerform('store_products', $row, 'update', 'products_id='.$_GET['id']);
	addMessage("Set product to active successfully");
	redirect(PAGE_STORE."?section=products&c=".$_GET['c']);
}

if($_GET['action'] == 'deleteproduct') {
	//remove from the db!
	dbQuery('DELETE FROM store_products WHERE products_id = ' . $_GET['id']);
	dbQuery('DELETE FROM store_products_info WHERE products_id = ' . $_GET['id']);
	dbQuery('DELETE FROM store_products_images WHERE products_id = ' .$_GET['id']);
	
	addMessage("Deleted product successfully");
	redirect(PAGE_STORE."?section=products&c=".$_GET['c']);
}
if($_POST['action'] == 'editshipping') {
	
	
	$row['embryo_shipping_price'] = $_POST['embryo_shipping_price'];
	$row['embryo_shipping_desc'] = $_POST['embryo_shipping_desc'];
	$row['semen_shipping_price_1'] = $_POST['semen_shipping_price_1'];
	$row['semen_shipping_price_2'] = $_POST['semen_shipping_price_2'];
	$row['semen_shipping_desc'] = $_POST['semen_shipping_desc'];
	$row['semen_shipping_image'] =  $_POST['semen_shipping_image'];
	
	
	if($_FILES['semen_shipping_image']['name'] != '') {//upload image yo!
		$filename = time().fixFilename($_FILES['semen_shipping_image']['name']);
		uploadFile($_FILES['semen_shipping_image'], $filename);
		makeThumbnail($filename, UPLOAD_DIR, 480, '', 'medium');
		
		$row['semen_shipping_image'] = $filename;
	}
	
	dbPerform('store_shipping', $row, 'update', 'shipping_id = 1');
	
	addMessage("Updated shipping information successfully");
	redirect(PAGE_STORE."?section=shipping&action=editshipping");
	
	
	
}

switch($_GET['section']){ 
case 'manage':
$trail->add("Categories");
break;
case 'orders':
default:
$trail->add("Orders");
	if(isset($_GET['id'])) {
	$oresults =dbQuery('SELECT orders_time FROM store_orders WHERE orders_id = ' . $_GET['id']);
	$o = dbFetchArray($oresults);
	$trail->add("Order Number: " . $o['orders_time']);
	}
break;
case 'products': 
//get category and link to category overview with produicts
if(isset($_GET['c']) && $_GET['c'] != "") {
//get category
$cResults = dbQuery('SELECT categories_title FROM store_categories WHERE categories_id = ' . $_GET['c']);
$cInfo = dbFetchArray($cResults);
$trail->add(output($cInfo['categories_title']), PAGE_STORE."?section=products&c=".$_GET['c']);
}
$trail->add("Products");
break;
case 'addproduct':
$trail->add("Add Product");
break;
case 'specials':
$trail->add("Specials");
break;

case 'editproduct':
$trail->add("Edit Product");
break;
case 'editcategory':
$trail->add("Edit Category");
break;
case 'editoption':
$trail->add("Edit Option");
break;
case 'addcategory': 
$trail->add("Add Category");
break;
case 'addoption': 
$trail->add("Add Product Option");
break;
case 'options':
$trail->add("Product Options");
break;
case 'products':
$trail->add("Products");
	switch($_GET['action']){
	case 'addproduct':
	$trail->add("Add Product");
	break;
	case 'editproduct':
	$trail->add('Edit Product');
	break;
	}
break;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=COMPANY_NAME?> :: Powered by Intelligence Center</title>
<script language="javascript" src="nav.js"></script>
<script type="text/javascript" src="jscripts/cicfunctions.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" src="jscripts/jquery.js"></script>
<script type="text/javascript" src="jscripts/ui.datepicker.js"></script>
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
	altField: '#products_release_date',
	altFormat: 'mm/dd/yy'
	
	}
  );
  
  $("#calendarContainerEnd").datepicker(
	{
	altField: '#products_release_date',
	altFormat: 'mm/dd/yy'
	
	}
  );
  
  

	$('#product_options_toggle').click(function() {
		$('#product_options').slideToggle();										  
	});
	$('#product_description_toggle').click(function() {
		$('#product_description').slideToggle();										  
	});
	$('#additional_information_toggle').click(function() {
		$('#additional_information').slideToggle();										  
	});

});
</script>
<?
//only load tinymce is we are editing =0
if($_GET['action'] == 'addproduct' || $_GET['action'] == 'editproduct') { ?>
<script language="javascript">
  tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	// Theme options
	plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
	theme_advanced_buttons1 : "bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor, image, cleanup",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,| ,sub,sup",
	theme_advanced_buttons4 : "spellchecker,|, insertfile,insertimage",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "center",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	// Example content CSS (should be your site CSS)
	content_css : "css/cms.css",
	width: "620",
	height: "500"
	// Drop lists for link/image/media/template dialogs
	
	});
  
  
</script>
<?
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
<? include('store_submenu.php'); ?>
<?
switch($_GET['section']) {
case 'orders':
default:

					switch($_GET['action']) {
					case 'view':
											//get order information
											$orderResults = dbQuery('SELECT * FROM store_orders WHERE orders_id = ' . $_GET['id']);
											$oInfo = dbFetchArray($orderResults);
											$buttonText = "Update Order";
											?>
                                            <form action="<?=PAGE_STORE?>" method="post" enctype="multipart/form-data" name="category" id="category2">
                                            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                                            <input type="hidden" name="action" value="updateorder" />
                                              <table width="900" border="0" cellspacing="0" cellpadding="0" >
                                                <tr>
                                                  <td width="650" valign="top" class="formBody"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                    
                                                    <tr>
                                                      <td colspan="2" class="pageTitleSub">Order Number: <?=$oInfo['orders_time']?></td>
                                                    </tr>
                                                    <tr>
                                                      <td width="47%" valign="top"><strong>Received</strong>:
                                                        <?=date('F j, Y, g:i a', $oInfo['orders_time'])?>
                                                      <?
													  if($oInfo['orders_complete_date'] != 0) {
													  	echo "<br><strong>Completed</strong>: " . date('F j, Y, g:i a', $oInfo['orders_complete_date']);
													  }
													  ?>
                                                      </td>
                                                      <td width="53%" align="right" valign="top"><strong>Order Total: $
                                                          <?=number_format($oInfo['orders_total'], 2, '.',',')?>
                                                      </strong></td>
                                                    </tr>
                                                    <?
													if($cInfo['categories_image'] != "") {  }
													//display image? naa just link to it
													?>
                                                    <tr>
                                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tr>
                                                          <td><strong>Customer  Information</strong></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                          <td>
                                                          <?
														  echo $oInfo['orders_customer_name']."<br>";
														  if($oInfo['orders_company'] != "") echo $oInfo['orders_company']."<br>";
														  echo "Email: <a href=\"mailto:".$oInfo['orders_customer_email']."\">".$oInfo['orders_customer_email']."</a><br>";
														  
														  echo "Ph: ".$oInfo['orders_phone_number']."<br>";
														  echo "Fax: ".$oInfo['orders_fax_number'];
														  ?>
                                                          </td>
                                                        </tr>
                                                      </table></td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tr>
                                                          <td><strong>Billing Information</strong></td>
                                                          <td><strong>Shipping Information</strong></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                          <td valign="top">
                                                          
                                                          <?
														  echo $oInfo['orders_customer_name']."<br>";
														  echo $oInfo['orders_shipping_address']."<br>";
														  if($oInfo['orders_shipping_address2'] != "")
														  echo $oInfo['orders_shipping_address2']."<br>";
														  echo $oInfo['orders_shipping_city']." ".$oInfo['orders_shipping_state']." ".$oInfo['orders_shipping_zipcode']."<br>";
														  echo "<strong>Card #:</strong> xxxx-xxxx-xxxx-".$oInfo['orders_card_number'] . "<br>";
														  echo "<strong>Expiry:</strong> " .$oInfo['orders_expire'];
														  ?>
                                                          </td>
                                                          <td valign="top">
                                                           <?
														  if($oInfo['orders_company'] != "") echo $oInfo['orders_company']."<br>";
														  echo $oInfo['orders_billing_address']."<br>";
														  if($oInfo['orders_billing_address2'] != "")
														  echo $oInfo['orders_billing_address2']."<br>";
														  echo $oInfo['orders_billing_city']." ".$oInfo['orders_billing_state']." ".$oInfo['orders_billing_zipcode'];
														  
														  
														  
														  ?>
                                                          </td>
                                                        </tr>
                                                      </table></td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2" class="pageTitleSub">Purchases</td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tr class="tableRowHeader">
                                                          <td><strong>[stock number] - Product Name</strong></td>
                                                          <td>&nbsp;</td>
                                                          <td>Qty</td>
                                                          <td align="right"><strong>Price</strong></td>
                                                        </tr>
                                                        <?
														$count = 0;
														$opResults = dbQuery('SELECT * FROM store_orders_products WHERE orders_id = ' . $oInfo['orders_id']);
														while($op = dbFetchArray($opResults)) {
															$row = $count % 2;
														?>
                                                        <tr>
                                                          <td class="row<?=$row?>"><?=output($op['orders_products_title'])?></td>
                                                          <td class="row<?=$row?>"><?=$op['orders_product_cert_qty']?></td>
                                                          <td class="row<?=$row?>"><?=output($op['orders_products_qty'])?></td>
                                                          <td align="right" class="row<?=$row?>">$<?=number_format($op['subtotal'],2,'.',',')?></td>
                                                        </tr>
                                                       
                                                        <?
														$count++;
														}
														?> <tr>
                                                          <td colspan="4" align="right" >
                                                          <strong>Shipping : $
                                                              <?=number_format($oInfo['orders_shipping'], 2, '.',',')?>
                                                          </strong><br />
                                                          <strong>Total : $
                                                              <?=number_format($oInfo['orders_total'], 2, '.',',')?>
                                                          </strong></td>
                                                          </tr>
                                                      </table></td>
                                                    </tr>
                                                     <tr>
                                                      <td colspan="2" class="pageTitleSub">Shipping Method</td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2"><?=$oInfo['orders_shipping_type']?></td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2" class="pageTitleSub">Customer Notes</td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2"><?=output($oInfo['orders_note'])?></td>
                                                    </tr>
                                                  </table>
                                                    <div class="mb20"></div>
                                                    <div class="expandable">
                                                      <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                          <td width="29"><img src="images/sectionTitleArrow.jpg" width="29" height="28" /></td>
                                                          <td class="sectionTitle">Sort Order</td>
                                                        </tr>
                                                      </table>
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tr>
                                                          <td class="fieldLabel"><strong>
                                                            <input name="categories_sort_order" type="text" class="textField-title" id="categories_sort_order" style="width:30px;" value="<?=$cInfo['categories_sort_order']?>" />
                                                          </strong></td>
                                                        </tr>
                                                      </table>
                                                    </div>
                                                    <div class="mb20"></div>
                                                    <div class="expandable">
                                                      <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                          <td width="29"><img src="images/sectionTitleArrow.jpg" width="29" height="28" /></td>
                                                          <td class="sectionTitle">Category Parent</td>
                                                        </tr>
                                                      </table>
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tr>
                                                          <td class="fieldLabel"><select name="categories_parent" class="textField-title" id="categories_parent">
                                                            <option value="0">Top Level</option>
                                                            <?
														   //get all pages that have parent of 0// these are top level pages =)
														   $parentResults = dbQuery('SELECT * FROM store_categories');
														   while($pInfo = dbFetchArray($parentResults)){
															  echo "<option value=\"".$pInfo['categories_id']."\"";
																  if($cInfo['categories_parent'] == $pInfo['categories_id'])
																	  echo " selected";
															  echo ">".output($pInfo['categories_title'])."</option>\n"; 
														   }
														   ?>
                                                          </select></td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                  <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
                                                    <tr>
                                                      <td><div style="margin:0px 10px 10px 10px;">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                                          <tr>
                                                            <td height="43" class="headerCell"><h2>Order Status</h2></td>
                                                          </tr>
                                                          <tr>
                                                            <td><strong>Update Order Status</strong><br />
                                                              <select name="orders_status" id="orders_status">
                                                                <option value="pending" <? if($oInfo['orders_status']=='pending') echo " selected";?>>Pending</option>
                                                                <option value="onhold" <? if($oInfo['orders_status']=='onhold') echo " selected";?>>On Hold</option>
                                                                <option value="complete" <? if($oInfo['orders_status']=='complete') echo " selected";?>>Complete</option>
                                                              </select></td>
                                                          </tr>
                                                              <tr>
                                                              	<td><strong>Order Message?</strong><br />
                                                           	    <span class="smallText">(this will be sent in the email if you choose to notify the customer) </span></td>
                                                              </tr>
                                                              <tr>
                                                              	<td><textarea style="height:60px; width:200px;" name="orders_message"><?=output($oInfo['orders_message'])?></textarea></td>
                                                              </tr>
                                                              <tr>
                                                              	<td><strong>Tracking #<br />
                                                           	      <span class="smallText">(This will be sent in the email to the user if you choose to notify the customer) </span></strong></td>
                                                              </tr>
                                                              <tr>
                                                              	<td><input type="text" name="orders_tracking_number" value="<?=$oInfo['orders_tracking_number']?>" /></td>
                                                              </tr>
                                                              <tr>
                                                              <td>
                                                            <br /><br />
                                                            <label><input name="notify" type="checkbox" id="notify" value="1" />Notify Customer</label>
                                                           </td>
                                                          </tr>
                                                          <tr>
                                                            <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="<?=$buttonText?>" /></td>
                                                          </tr>
                                                        </table>
                                                        <br />
                                                        <br />
                                                      </div></td>
                                                    </tr>
                                                  </table>
                                                    <p>&nbsp;</p></td>
                                                </tr>
                                              </table>
                                            </form>
                                            <?
					break;
					case 'manage':
					default:
													?>
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
													  <tr>
														<td valign="top" class="formBody"><br />
														  <form>
                                                         
															<table width="100%" border="0" cellspacing="0" cellpadding="5">
															  <tr>
																<td><table width="100%" border="0" cellspacing="0" cellpadding="5">
																  <tr>
																	<td>&nbsp;</td>
																	<td width="100"><a class="button" href="<?=PAGE_STORE?>?section=archive&action=addcategory"><span class="add">View Order Archive</span></a></td>
																  </tr>
																</table></td>
															  </tr>
															</table>
                                                             <h2>Pending & Onhold Orders </h2>
															<table width="100%" border="0" cellspacing="0" cellpadding="5">
															  <tr >
                                                              	<td class="tableRowHeader" >Order#</td>
																<td class="tableRowHeader" >Customer Name</td>
																<td class="tableRowHeader"  align="left">Order Time</td>
																<td class="tableRowHeader"  align="left">Order Total</td>
                                                                <td class="tableRowHeader"  align="left">Order Status</td>
                                                                <td class="tableRowHeader"  align="left">&nbsp;</td>
															  </tr>
															  <?
															  	$count = 0;
																$pendingOrderResults = dbQuery('SELECT * FROM store_orders WHERE orders_status = "pending" OR orders_status = "onhold" ORDER BY orders_time ASC');
																while($oInfo = dbFetchArray($pendingOrderResults)) {
																	$row = $count % 2;
																	?>
                                                                    <tr>
                                                                   	  <td class="row<?=$row?>"><a href="<?=PAGE_STORE?>?section=orders&action=view&id=<?=$oInfo['orders_id']?>" title="View Order <?=$oInfo['orders_time']?>"><?=$oInfo['orders_time']?></a></td>
                                                                   	  <td class="row<?=$row?> pageTitleSub"><?=output($oInfo['orders_customer_name'])?></td>
                                                                    	<td class="row<?=$row?>"><?=date('F j, Y, g:i a', $oInfo['orders_time'])?></td>
                                                                        <td class="row<?=$row?>">$<?=number_format($oInfo['orders_total'], 2, '.',',')?></td>
                                                                        <td class="row<?=$row?>"><?=$oInfo['orders_status']?></td>
                                                                        <td class="row<?=$row?>"><a href="<?=PAGE_STORE?>?section=orders&action=view&id=<?=$oInfo['orders_id']?>" title="View Order"><img src="images/icons/view_16x16.gif" width="15" height="16" border="0" /></a> | <a href="<?=PAGE_STORE?>?section=orders&action=deleteorder&id=<?=$oInfo['orders_id']?>" title="Delete This Order" onclick="return confirm('Are you sure you want to PERMANENTLY DELETE this order?');"><img src="images/icons/delete_16x16.gif" width="16" height="16" border="0" alt="Delete This Order" /></a></td>
                                                                    </tr>
                                                                    <?
                                                                 //   echo "<tr>\n";
																	//echo "<td class=\"row".$row." pageTitleSub\"><a href=\"".PAGE_STORE."?section=orders&action=view&id=".$oInfo['orders_id']."\" >".output($oInfo['orders_customer_name'])."</a></td>\n";
																	//echo "<td class=\"row".$row."\">".date('m/d/Y', $oInfo['orders_time'])."</td>\n";
																	///echo "<td class=\"row".$row."\">$".number_format($oInfo['orders_total'], 2, '.', ',')."</td>\n";
																	//echo "<td class=\"row".$row."\">".$oInfo['orders_status']."</td>\n";
																	//echo "<td width=\"50\" class=\"row".$row."\"><a href=\"".PAGE_STORE_CATEGORIES."&action=editcategory&id=".$cInfo['categories_id']."\" title=\"Edit ".output($cInfo['categories_title'])."\" ><img src=\"images/icons/edit_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a> | <a href=\"".PAGE_STORE."?action=deletecategory&id=".$cInfo['categories_id']."\" onclick=\"return confirm('Are you sure you want to delete ".$cInfo['categories_title']."?');\"><img src=\"images/icons/delete_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a></td>\n";
																	//echo "</tr>\n";
																$count++;
																}
															  ?>
															</table>
														  </form>
                                                          <div class="mb20"></div>
                                                          <form>
                                                          <?
														  $past = strtotime("-30 days");
														  $dateStart = mktime(0,0,0, date('m', $past), date('d', $past), date('Y', $past));
														  $dateEnd = mktime(11,59,59, date('m', time()), date('d', time()), date('Y', time()));
														  ?>
															<h2>Complete Orders <span style="font-size:10px;">(<?=date('m/d/Y', $dateStart)?>-<?=date('m/d/Y', $dateEnd)?>)</span></h2>
															<table width="100%" border="0" cellspacing="0" cellpadding="5">
															  <tr >
                                                              	<td class="tableRowHeader" >Order#</td>
																<td class="tableRowHeader" >Customer Name</td>
																<td class="tableRowHeader"  align="left">Order Time / Completed</td>
																<td class="tableRowHeader"  align="left">Order Total</td>
                                                                <td class="tableRowHeader"  align="left">Order Status</td>
                                                                <td class="tableRowHeader"  align="left">&nbsp;</td>
															  </tr>
															  <?
																$pendingOrderResults = dbQuery('SELECT * FROM store_orders WHERE orders_status = "complete" ORDER BY orders_time DESC');
																while($oInfo = dbFetchArray($pendingOrderResults)) {
																	$row = $count % 2;
																	?>
                                                                    <tr>
                                                                   	  <td class="row<?=$row?>"><a href="<?=PAGE_STORE?>?section=orders&action=view&id=<?=$oInfo['orders_id']?>"><?=$oInfo['orders_time']?></a></td>
                                                                   	  <td class="row<?=$row?> pageTitleSub"><?=output($oInfo['orders_customer_name'])?></td>
                                                                    	<td class="row<?=$row?>"><?=date('m/d/Y', $oInfo['orders_time'])?> / <?=date('m/d/Y', $oInfo['orders_complete_date'])?></td>
                                                                        <td class="row<?=$row?>">$<?=number_format($oInfo['orders_total'], 2, '.',',')?></td>
                                                                        <td class="row<?=$row?>"><?=$oInfo['orders_status']?></td>
                                                                        <td class="row<?=$row?>"><a href="<?=PAGE_STORE?>?section=orders&action=view&id=<?=$oInfo['orders_id']?>" title="View Order"><img src="images/icons/view_16x16.gif" width="15" height="16" border="0" /></a> | <a href="<?=PAGE_STORE?>?section=orders&action=deleteorder&id=<?=$oInfo['orders_id']?>" title="Delete This Order" onclick="return confirm('Are you sure you want to PERMANENTLY DELETE this order?');"><img src="images/icons/delete_16x16.gif" width="16" height="16" border="0" alt="Delete This Order" /></a></td>
                                                                    </tr>
                                                                    <?
																	$count++;
																}
															  ?>
															</table>
														  </form>
                                                          
                                                          </td>
													  </tr>
													</table>
                                                    
                                                    <?
					
					break;
					case 'view':
					
					break;
					case 'archive':
					
					break;						
					}
break;
case 'shipping':
					switch($_GET['action']) {
					case 'editshipping':
					default:
									$shippingResults = dbQuery("SELECT * FROM store_shipping WHERE shipping_id = 1");
									$sInfo = dbFetchArray($shippingResults);
									$buttonText = "Save Changes";
									?>
                                    <form action="<?=PAGE_STORE?>" method="post" enctype="multipart/form-data" name="category" id="category">
                                                            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                                                            <input type="hidden" name="action" value="<?=$_GET['action']?>" />
                                                            <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                                                            <table width="900" border="0" cellspacing="0" cellpadding="0" >
                                                              <tr>
                                                                <td width="650" valign="top" class="formBody">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                    <tr>
                                                                      <td width="100%" class="pageTitleSub">Shipping Description</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><textarea name="embryo_shipping_desc" id="embryo_shipping_desc" cols="45" rows="5"><?=output($sInfo['embryo_shipping_desc'])?></textarea></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="pageTitleSub">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="pageTitleSub">Shipping Description</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><textarea name="semen_shipping_desc" id="semen_shipping_desc" cols="45" rows="5"><?=output($sInfo['semen_shipping_desc'])?></textarea></td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                      <td>&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                  <div class="mb20"></div>
                                                                  <div class="expandable">
                                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                                      <tr>
                                                                        <td width="29"><img src="images/sectionTitleArrow.jpg" width="29" height="28" /></td>
                                                                        <td class="sectionTitle">Sort Order</td>
                                                                      </tr>
                                                                    </table>
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                    <tr>
                                                                      <td class="fieldLabel"><strong>
                                                                        <input name="categories_sort_order" type="text" class="textField-title" id="categories_sort_order" style="width:30px;" value="<?=$cInfo['categories_sort_order']?>" />
                                                                      </strong></td>
                                                                    </tr>
                                                                  </table>
                                                                  </div>
                                                                  
                                                                 <div class="mb20"></div>
                                                                  <div class="expandable">
                                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                                      <tr>
                                                                        <td width="29"><img src="images/sectionTitleArrow.jpg" width="29" height="28" /></td>
                                                                        <td class="sectionTitle">Category Parent</td>
                                                                      </tr>
                                                                    </table>
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                    <tr>
                                                                      <td class="fieldLabel">
                                                                      <select name="categories_parent" class="textField-title" id="categories_parent">
                                                                     <option value="0">Top Level</option>
                                                                     <?
                                                                     //get all pages that have parent of 0// these are top level pages =)
                                                                     $parentResults = dbQuery('SELECT * FROM store_categories');
                                                                     while($pInfo = dbFetchArray($parentResults)){
                                                                        echo "<option value=\"".$pInfo['categories_id']."\"";
																			if($cInfo['categories_parent'] == $pInfo['categories_id'])
																				echo " selected";
																		echo ">".output($pInfo['categories_title'])."</option>\n"; 
                                                                     }
                                                                     ?>
                                                                      </select></td>
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
                                                                        <td class="headerCell"><h2>Publish Shipping</h2></td>
                                                                      </tr>
                                                                     
                                                                     
                                                                      <tr>
                                                                        <td>&nbsp;</td>
                                                                      </tr>
                                                                      <tr>
                                                                        <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="<?=$buttonText?>" /></td>
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
					
					}
break;
case 'categories':
	
					switch($_GET['action']) {
						case 'manage':
						default:
													?>
													<table width="100%" border="0" cellspacing="0" cellpadding="0" >
													  <tr>
														<td valign="top" class="formBody"><br />
														  <form>
															<table width="100%" border="0" cellspacing="0" cellpadding="5">
															  <tr>
																<td><table width="100%" border="0" cellspacing="0" cellpadding="5">
																  <tr>
																	<td>&nbsp;</td>
																	<td width="100"><a class="button" href="<?=PAGE_STORE_CATEGORIES?>&action=addcategory"><span class="add">Add Category</span></a></td>
																  </tr>
																</table></td>
															  </tr>
															</table>
															<table width="100%" border="0" cellspacing="0" cellpadding="5">
															  <tr >
																<td width="1" class="tableRowHeader" >&nbsp;</td>
																<td class="tableRowHeader"  align="left">Name</td>
																<td class="tableRowHeader"  align="left">Created</td>
																
																<td class="tableRowHeader" width="50">&nbsp;</td>
															  </tr>
															  <?
															$count = 0;
															$topLevelResults = dbQuery('SELECT * FROM store_categories WHERE categories_parent = 0 ORDER BY categories_title');
															while($cInfo = dbFetchArray($topLevelResults)) {
																$row = $count % 2;
																echo "<tr>\n";
																echo "<td class=\"row".$row."\"><a href=\"".PAGE_STORE."?section=products&action=manage&c=".$cInfo['categories_id']."\" title=\"".output($cInfo['categories_title'])."Products\"><img src=\"images/icons/folder_closed_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a></td>\n";
																echo "<td class=\"row".$row." pageTitleSub\"><a href=\"".PAGE_STORE."?section=products&action=manage&c=".$cInfo['categories_id']."\" title=\"".output($cInfo['categories_title'])." Products\">".output($cInfo['categories_title'])."</a></td>\n";
																echo "<td class=\"row".$row."\">".date('m/d/Y', $cInfo['categories_date_added'])."</td>\n";
																echo "<td width=\"150\" align=\"right\" class=\"row".$row."\"><a href=\"".PAGE_STORE_CATEGORIES."&action=editcategory&id=".$cInfo['categories_id']."\" title=\"Edit ".output($cInfo['categories_title'])."\" ><img src=\"images/icons/edit_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a> | <a href=\"".PAGE_STORE."?action=deletecategory&id=".$cInfo['categories_id']."\" onclick=\"return confirm('Are you sure you want to delete ".$cInfo['categories_title']."?');\"><img src=\"images/icons/delete_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a></td>\n";
																echo "</tr>\n";
																recurseCategories($cInfo['categories_id'], 1);
															$count++;
															}
															?>
															</table>
														  </form></td>
													  </tr>
													</table>
							
													<?
						break;
						case 'addcategory':
						case 'editcategory':
															 
														  if($_GET['action'] == 'addcategory') {
														  
														  	$action = "Add";
															$uInfo = array();
															$uInfo['user_created'] = time();
															$buttonText = "Add Category";
														  	
														  }
														  if($_GET['action'] == 'editcategory') {
															  $action = "Edit";
															  //get userinfo!
															  $cResults = dbQuery('SELECT * FROM store_categories WHERE categories_id = ' . $_GET['id']);
															  $cInfo = dbFetchArray($cResults);
															  $buttonText = "Save Category";
														  }
														  ?>
                                                          <form action="<?=PAGE_STORE?>" method="post" enctype="multipart/form-data" name="category" id="category">
                                                            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                                                            <input type="hidden" name="action" value="<?=$_GET['action']?>" />
                                                            <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                                                            <table width="900" border="0" cellspacing="0" cellpadding="0" >
                                                              <tr>
                                                                <td width="650" valign="top" class="formBody">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                    <tr>
                                                                      <td width="100%" class="pageTitleSub">Title</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><input type="text" name="categories_title" id="categories_title" class="textField-title" style="width:650px" value="<?=output($cInfo['categories_title'])?>" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="pageTitleSub">Description</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><textarea name="categories_desc" id="categories_desc" cols="45" rows="5"><?=output($cInfo['categories_desc'])?></textarea></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="pageTitleSub">Catetgory Image </td>
                                                                    </tr>
                                                                    <?
																	if($cInfo['categories_image'] != "") { 
																	//display image? naa just link to it
																	?>
                                                                    <tr>
                                                                    	<td><strong>Current Image:</strong> <?=substr($cInfo['categories_image'],10)?> <a href="<?=UPLOAD_DIR_URL.$cInfo['categories_image']?>" target="_blank">view</a></td>
                                                                    </tr>
                                                                    <?
																	
																	}
																	?>
                                                                    <tr>
                                                                      <td><input type="file" name="image" id="image" /></td>
                                                                    </tr>
                                                                  </table>
                                                                  <div class="mb20"></div>
                                                                  <div class="expandable">
                                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                                      <tr>
                                                                        <td width="29"><img src="images/sectionTitleArrow.jpg" width="29" height="28" /></td>
                                                                        <td class="sectionTitle">Sort Order</td>
                                                                      </tr>
                                                                    </table>
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                    <tr>
                                                                      <td class="fieldLabel"><strong>
                                                                        <input name="categories_sort_order" type="text" class="textField-title" id="categories_sort_order" style="width:30px;" value="<?=$cInfo['categories_sort_order']?>" />
                                                                      </strong></td>
                                                                    </tr>
                                                                  </table>
                                                                  </div>
                                                                  
                                                                 <div class="mb20"></div>
                                                                  <div class="expandable">
                                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                                      <tr>
                                                                        <td width="29"><img src="images/sectionTitleArrow.jpg" width="29" height="28" /></td>
                                                                        <td class="sectionTitle">Category Parent</td>
                                                                      </tr>
                                                                    </table>
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                    <tr>
                                                                      <td class="fieldLabel">
                                                                      <select name="categories_parent" class="textField-title" id="categories_parent">
                                                                     <option value="0">Top Level</option>
                                                                     <?
                                                                     //get all pages that have parent of 0// these are top level pages =)
                                                                     $parentResults = dbQuery('SELECT * FROM store_categories');
                                                                     while($pInfo = dbFetchArray($parentResults)){
                                                                        echo "<option value=\"".$pInfo['categories_id']."\"";
																			if($cInfo['categories_parent'] == $pInfo['categories_id'])
																				echo " selected";
																		echo ">".output($pInfo['categories_title'])."</option>\n"; 
                                                                     }
                                                                     ?>
                                                                      </select></td>
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
                                                                        <td class="headerCell"><h2>Publish Category</h2></td>
                                                                      </tr>
                                                                     
                                                                     
                                                                      <tr>
                                                                        <td><strong>Published on:</strong><br />
                                                                          <?=date("F j, Y, g:i a", time())?>
                                                                          <div id="publishDate" style="display:none;">
                                                                          <div id="calendarContainer" style="padding:5px 0px;"></div>
                                                                          <div class="mt10"></div>
                                                                          <input type="text" name="publish_date" id="publish_date" value="<?=date('d/m/Y', time())?>" readonly="readonly" />
                                                                          </div>
                                                                          
                                                                        
                                                                        </td>
                                                                      </tr>
                                                                      <tr>
                                                                        <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="<?=$buttonText?>" /></td>
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
					}
break;
case 'specials':
										switch($_GET['action']) {
										case 'manage':
										default:
													?>
  													<table width="100%" border="0" cellspacing="0" cellpadding="0" >
													  <tr>
														<td valign="top" class="formBody"><br />
														  <form>
															<table width="100%" border="0" cellspacing="0" cellpadding="5">
															  <tr>
																<td><table width="100%" border="0" cellspacing="0" cellpadding="5">
																  <tr>
																	<td>&nbsp;</td>
																	<td width="100"><a class="button" href="<?=PAGE_STORE?>?section=specials&action=addspecial"><span class="add">Add Special</span></a></td>
																  </tr>
																</table></td>
															  </tr>
															</table>
															<table width="100%" border="0" cellspacing="0" cellpadding="5">
															  <tr >
																<td class="tableRowHeader"  align="left">Name</td>
                                                                <td class="tableRowHeader"  align="left">Category</td>
																<td class="tableRowHeader"  align="left">Start - End</td>
																<td class="tableRowHeader"  align="left">Discount Amt</td>
                                                                <td class="tableRowHeader"  align="left">Discount Type</td>
                                                                <td class="tableRowHeader"  align="left" width="14">&nbsp;</td>
															  </tr>
																	<?
                                                                    $specialResults = dbQuery('SELECT c.categories_title, c.categories_id, s.* FROM store_categories AS c, store_products_specials AS s WHERE c.categories_id = s.category_id  ORDER BY s.products_specials_date_start DESC');
                                                                    if(dbNumRows($specialResults)) {
																	$count = 0;
                                                                    while($sInfo = dbFetchArray($specialResults)) {
                                                                        $row = $count % 2;
																		echo "<tr>\n";
                                                                        
                                                                        echo "<td class=\"row".$row." pageTitleSub\"><a href=\"".PAGE_STORE."?section=specials&action=editspecial&id=".$sInfo['products_specials_id']."\" title=\" Edit ".output($sInfo['products_specials_title'])."\">".$sInfo['products_specials_title']."</a></td>\n";
                                                                        echo "<td class=\"row".$row." pageTitleSub\">".output($sInfo['categories_title'])."</td>\n";
																		echo "<td class=\"row".$row." pageTitleSub\">".date('m/d/Y', $sInfo['products_specials_date_start'])." - ".date('m/d/Y', $sInfo['products_specials_date_end'])."</td>\n";
                                                                        echo "<td class=\"row".$row."\">".$sInfo['products_specials_discount']."</td>\n";
                                                                        echo "<td class=\"row".$row."\">".$sInfo['products_specials_discount_type']."</td>\n";
                                                                        echo "<td width=\"50\" class=\"row".$row."\"><a href=\"".PAGE_STORE."?section=specials&action=editspecial&id=".$sInfo['products_specials_id']."\" title=\"Edit ".output($sInfo['products_specials_title'])."\" ><img src=\"images/icons/edit_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a> | <a href=\"".PAGE_STORE."?action=deletespecial&id=".$sInfo['products_specials_id']."\" onclick=\"return confirm('Are you sure you want to delete ".$sInfo['products_specials_title']."?');\"><img src=\"images/icons/delete_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a></td>\n";
                                                                        echo "</tr>\n";
																		$count++;
                                                                    }
                                                                    } else {
                                                                        echo "<tr>\n";
                                                                        echo "<td colspan=\"4\">No specials returned</td>\n";
                                                                        echo "</tr>\n";
                                                                    }
                                                                    ?>
															</table>
														  </form></td>
													  </tr>
													</table>
													<?
										break;
										case 'addspecial':
										case 'editspecial':
										
										if($_GET['action'] == 'addspecial') {
											$sInfo = array();
											$buttonText = "Add Special";
											$sInfo['products_specials_discount_type'] = 'percentage';
											$sInfo['products_specials_shipping'] = '0';
											$sInfo['products_specials_date_start'] = time();
											$sInfo['products_specials_date_end'] = time();
											
										} 
										if($_GET['action'] == 'editspecial') {
											$specialResults = dbQuery('SELECT * FROM store_products_specials WHERE products_specials_id = ' . $_GET['id']);
											$sInfo = dbFetchArray($specialResults);
											$buttonText = "Save Changes";
										}
										?>
													<form id="form1" name="form1" method="post" action="<?=PAGE_STORE?>">
                                                    <input type="hidden" name="action" value="<?=$_GET['action']?>" />
                                                    <input type="hidden" name="id" value="<?=$_GET['id']?>" />
													  <table width="900" border="0" cellspacing="0" cellpadding="0" >
													    <tr>
													      <td width="650" valign="top" class="formBody"><table width="100%" border="0" cellspacing="0" cellpadding="5">
													        <tr>
													          <td colspan="2" class="pageTitleSub">Specials Name</td>
												            </tr>
													        <tr>
													          <td colspan="2"><input type="text" name="products_specials_title" id="products_specials_title" class="textField-title" style="width:650px" value="<?=output($sInfo['products_specials_title'])?>" /></td>
												            </tr>
													        <tr>
													          <td valign="top" class="pageTitleSub">Category</td>
													          <td class="pageTitleSub">
                                                              <select name="categories" id="categories">
                                                              <option value="">Select one</option>
                                                              <?php
															  $cResults = dbQuery('SELECT * FROM store_categories ORDER BY categories_title ASC');
															  while($cInfo = dbFetchArray($cResults)) {
																	echo "<option value=\"".$cInfo['categories_id']."\"";
																	 if($sInfo['category_id'] == $cInfo['categories_id']) echo " selected";
																	echo ">".output($cInfo['categories_title'])."</option>\n";  
															  }
															  
															  ?>
												              </select></td>
												            </tr>
													        <tr>
													          <td width="23%" class="pageTitleSub">Discount Amount: </td>
													          <td width="77%" class="pageTitleSub"><input type="text" name="products_specials_discount" id="products_specials_discount" class="textField-title" style="width:100px" value="<?=output($sInfo['products_specials_discount'])?>" /></td>
												            </tr>
													        <tr>
													          <td class="pageTitleSub">Discount Type:</td>
													          <td><label>
													            <input type="radio" name="products_specials_discount_type" id="radio" value="percentage" <? if($sInfo['products_specials_discount_type'] == 'percentage') echo " checked"; ?> />
													            Percentage</label>
													            <br />
													            <label>
													              <input type="radio" name="products_specials_discount_type" id="radio2" value="monetary" <? if($sInfo['products_specials_discount_type'] == 'monetary') echo " checked"; ?> />
													              Monetary</label></td>
												            </tr>
													        <tr>
													          <td class="pageTitleSub">Free Shipping?</td>
													          <td><label>
													            <input type="radio" name="products_specials_shipping" id="radio" value="1" <? if($sInfo['products_specials_shipping'] == '1') echo " checked"; ?> />
													            Yes</label>
													            <br />
													            <label>
													              <input name="products_specials_shipping" type="radio" id="radio2" value="0" <? if($sInfo['products_specials_shipping'] == '0') echo " checked"; ?> />
												              No</label></td>
												            </tr>
													        <tr>
													          <td colspan="2" class="pageTitleSub">Special Description</td>
												            </tr>
													        <tr>
													          <td colspan="2"><textarea name="products_specials_description" id="products_specials_description" cols="45" rows="5"><?=output($sInfo['products_specials_description'])?></textarea></td>
												            </tr>
													        <tr>
													          <td colspan="2">&nbsp;</td>
												            </tr>
													        </table></td>
													      <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
													        <tr>
													          <td><div style="margin:0px 10px 10px 10px;">
													            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
													              <tr>
													                <td class="headerCell"><h2>Specials Status</h2></td>
												                  </tr>
													              <tr>
													                <td><strong>Display special on:</strong><br />
													                  <div id="calendarContainerSpecials" style="padding:5px 0px;" align="center"></div>
													                  <input type="text" name="products_calendar_date_start" id="products_calendar_date_start" value="<?=date('m/d/y', $sInfo['products_specials_date_start'])?>" readonly="readonly" />
													                  <div class="mt10"></div>
													                  <strong>Remove special on:</strong><br />
													                  <div id="calendarContainerSpecialsEnd" style="padding:5px 0px;" align="center"></div>
													                  <input type="text" name="products_calendar_date_end" id="products_calendar_date_end" value="<?=date('m/d/y', $sInfo['products_specials_date_end'])?>" readonly="readonly" />
													                  <div class="mt10"></div></td>
												                  </tr>
													              <tr>
													                <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="<?=$buttonText?>" /></td>
												                  </tr>
												                </table>
													            <br />
													            <br />
													            </div></td>
												            </tr>
													        </table>
													        <p>&nbsp;</p></td>
												        </tr>
												      </table>
  </form>
<script language="javascript">
$(document).ready(function(){

	$("#calendarContainerSpecials").datepicker(
	{
		altField: '#products_calendar_date_start',
		altFormat: 'mm/dd/yy',
		defaultDate: new Date('<?=date('m/d/Y', $sInfo['products_specials_date_start'])?>')
	}
	);
  
	$("#calendarContainerSpecialsEnd").datepicker(
	{
		altField: '#products_calendar_date_end',
		altFormat: 'mm/dd/yy',
		defaultDate: new Date('<?=date('m/d/Y', $sInfo['products_specials_date_end'])?>')
	}
	);
});
</script>
										<?
										
										break;
										}
break;
case 'products':
										switch($_GET['action']) {
										case 'manage':
										default:
													?>
													<table width="100%" border="0" cellspacing="0" cellpadding="0" >
													  <tr>
														<td valign="top" class="formBody"><br />
														  <form>
															<table width="100%" border="0" cellspacing="0" cellpadding="5">
															  <tr>
																<td><table width="100%" border="0" cellspacing="0" cellpadding="5">
																  <tr>
																	<td>&nbsp;</td>
																	<td width="100"><a class="button" href="<?=PAGE_STORE?>?section=products&action=addproduct&c=<?=$_GET['c']?>"><span class="add">Add Product</span></a></td>
																  </tr>
																</table></td>
															  </tr>
															</table>
															<table width="100%" border="0" cellspacing="0" cellpadding="5">
															  <tr >
																<td width="1" class="tableRowHeader" >&nbsp;</td>
																<td class="tableRowHeader"  align="left">Name</td>
																<td class="tableRowHeader"  align="left">Price</td>
																<td class="tableRowHeader"  align="left">Created</td>
                                                                <td class="tableRowHeader"  align="left">Release Date</td>
																<td class="tableRowHeader" width="50">&nbsp;</td>
                                                                <td class="tableRowHeader"  align="left" width="14">&nbsp;</td>
															  </tr>
															  <?
															if(isset($_GET['c']) && $_GET['c'] != "") 
																$where = "WHERE categories_id = " . $_GET['c'];
															else
																$where = "";
																
															$productResults = dbQuery('SELECT * FROM store_products '.$where.' ORDER BY products_title');
															if(dbNumRows($productResults)) {
															$count = 0;
															while($pInfo = dbFetchArray($productResults)) {
																$row = $count % 2;
																echo "<tr>\n";
																echo "<td class=\"row".$row."\"><input type=\"checkbox\" name=\"product_id[]\" id=\"product_".$pInfo['products_id']." value=\"".$pInfo['products_id']."\"></td>\n";
																
																echo "<td class=\"row".$row." pageTitleSub\"><a href=\"".PAGE_STORE."?section=products&c=".$_GET['c']."&action=editproduct&id=".$pInfo['products_id']."\" title=\"".output($pInfo['products_title'])."\">".output($pInfo['products_title'])."</a>";
																	if($pInfo['products_featured']) echo " <span class=\"smallText\">(featured)</span>";
																echo "</td>\n";
																echo "<td class=\"row".$row." pageTitleSub\">".number_format($pInfo['products_price'],2,'.',',')."</td>\n";
																echo "<td class=\"row".$row."\">".date('m/d/Y', $pInfo['products_date_added'])."</td>\n";
																echo "<td class=\"row".$row."\">".date('m/d/Y', $pInfo['products_release_date'])."</td>\n";
																
																echo "<td width=\"50\" class=\"row".$row."\"><a href=\"".PAGE_STORE."?section=products&c=".$_GET['c']."&action=editproduct&id=".$pInfo['products_id']."\" title=\"Edit ".output($pInfo['products_title'])."\" ><img src=\"images/icons/edit_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a> | <a href=\"".PAGE_STORE."?action=deleteproduct&id=".$pInfo['products_id']."&c=".$_GET['c']."\" onclick=\"return confirm('Are you sure you want to delete ".$pInfo['products_title']."?');\"><img src=\"images/icons/delete_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a></td>\n";
																echo "<td class=\"row".$row."\">";
																if($pInfo['products_status'] == 'active') {
																	echo "<a href=\"".PAGE_STORE."?action=setinactive&c=".$_GET['c']."&section=products&id=".$pInfo['products_id']."\" onclick=\"return confirm('Are you sure you want to set this product to INACTIVE?');\"><img src=\"images/icon_active.jpg\" border=\"0\"></a>";	
																} else {
																	echo "<a href=\"".PAGE_STORE."?action=setactive&c=".$_GET['c']."&section=products&id=".$pInfo['products_id']."\" onclick=\"return confirm('Are you sure you want to set this product to ACTIVE?');\"><img src=\"images/icon_inactive.jpg\" border=\"0\"></a>";	
																}
																echo "</td>";
																
																echo "</tr>\n";
															$count++;
															}
															} else {
																echo "<tr>\n";
																echo "<td colspan=\"4\">No Products Returned</td>\n";
																echo "</tr>\n";
															}
															
																 
																 
																 
																
															  ?>
															</table>
														  </form></td>
													  </tr>
													</table>
							
													<?
					
											break;
											case 'addproduct':
											case 'editproduct':
														
													if($_GET['action'] == 'addproduct') {
													$pInfo = array();
													$buttonText = "Add Product";
													$pInfo['products_release_date'] = time();
													}
													if($_GET['action'] == 'editproduct') {
													//we are editing
													$productResults = dbQuery('SELECT p.*, pi.* FROM store_products AS p, store_products_info AS pi WHERE pi.products_id = p.products_id AND p.products_id = ' . $_GET['id']);
													$pInfo = dbFetchArray($productResults);
													
													$buttonText = "Save Product";
													
													}
														
											
											?>
													<form action="<?=PAGE_STORE?>" method="post" enctype="multipart/form-data" name="article" id="article">
													  <input type="hidden" name="id" value="<?=$_GET['id']?>" />
													  <input type="hidden" name="action" value="<?=$_GET['action']?>" />
                                                      <input type="hidden" name="c" value="<?=$_GET['c']?>" />
													  <table width="900" border="0" cellspacing="0" cellpadding="0" >
													    <tr>
													      <td width="650" valign="top" class="formBody"><table width="100%" border="0" cellspacing="0" cellpadding="5">
													        <tr>
													          <td colspan="2" class="pageTitleSub">Product Name</td>
												            </tr>
													        <tr>
													          <td colspan="2"><input type="text" name="products_title" id="products_title" class="textField-title" style="width:650px" value="<?=output($pInfo['products_title'])?>" /></td>
												            </tr>
													        <tr>
													          <td width="28%" class="pageTitleSub">Product Number</td>
													          <td width="72%" class="pageTitleSub"><input type="text" name="products_stock_number" id="products_stock_number" class="textField-title" style="width:300px" value="<?=output($pInfo['products_stock_number'])?>" /></td>
												            </tr>
													        <tr>
													          <td class="pageTitleSub">Price $												              </td>
													          <td class="pageTitleSub"><input type="text" name="products_price" id="products_price" class="textField-title" style="width:90px" value="<?=number_format($pInfo['products_price'],2,'.', ',')?>" /></td>
												            </tr>
													        <tr>
													          <td class="pageTitleSub">Product Main Image</td>
													          <td class="pageTitleSub"><input name="image" type="file" class="textField-title" id="image" /></td>
												            </tr>
                                                             <?
															  if($_GET['action'] == 'editproduct') { //check to see if an image already exists if so then we want to allow the user to view the image
															  	$imgCheck = dbQuery('SELECT * FROM store_products_images WHERE products_id = ' . $_GET['id']);
																if(dbNumRows($imgCheck)) {
																	echo "<tr>";
																	echo "<td valign=\"top\" class=\"pageTitleSub\">Current Image <br><span style=\"font-size:10px; font-weight:normal;\">(click to enlarge)</a></td>";
																	echo "<td>";
																	$img = dbFetchArray($imgCheck);
																	echo "<a href=\"../files/".$img['products_images_filename']."\" target=\"_blank\" class=\"title\"><img src=\"../files/".getThumbnailFilename($img['products_images_filename'], 'small')."\"></a>";	
																	echo "</td>";
																	echo "</tr>";
																}
															  }
															  ?>
													        <?
															  if($_GET['action'] == 'editproduct') { //check to see if an image already exists if so then we want to allow the user to view the image
																if($pInfo['products_info_custom_7'] != "") {
																	echo "<tr>";
																	echo "<td valign=\"top\" class=\"pageTitleSub\">Current Spec Sheet Image <br><span style=\"font-size:10px; font-weight:normal;\">(click to enlarge)</a></td>";
																	echo "<td>";
																	echo "<a href=\"../files/".$pInfo['products_info_custom_7']."\" target=\"_blank\" class=\"title\"><img src=\"../files/".getThumbnailFilename($pInfo['products_info_custom_7'], 'small')."\"></a>";		
																	echo "</td>";
																	echo "</tr>";
																}
															  }
															  ?>
													        <tr>
													          <td class="pageTitleSub">Spec Sheet</td>
													          <td class="pageTitleSub"><input name="file" type="file" class="textField-title" id="file" /></td>
												            </tr>
													        <tr>
													          <td class="pageTitleSub">Category</td>
													          <td class="pageTitleSub"><select name="c" id="c" class="textField-title">
													            <?
															  $categories = dbQuery('SELECT * FROM store_categories ORDER BY categories_title');
															  while($cInfo = dbFetchArray($categories)) {
																echo "<option value=\"".$cInfo['categories_id']."\"";
																	if($cInfo['categories_id'] == $_GET['c'])
																		echo " selected";
																echo ">".output($cInfo['categories_title'])."</option>\n";
															  }
															  ?>
													            </select></td>
												            </tr>
													        <?
															  if($_GET['action'] == 'editproduct') { //check to see if an image already exists if so then we want to allow the user to view the image
																if($pInfo['products_info_custom_6'] != "") {
																	echo "<tr>";
																	echo "<td valign=\"top\" class=\"pageTitleSub\">Current Spec Sheet <br><span style=\"font-size:10px; font-weight:normal;\">(click to enlarge)</a></td>";
																	echo "<td>";
																	echo "<a href=\"../files/".$pInfo['products_info_custom_6']."\" target=\"_blank\" class=\"title\">[view spec sheet]</a>";	
																	echo "</td>";
																	echo "</tr>";
																}
															  }
															  ?>
                                                              <tr>
                                                                <td class="pageTitleSub">Shipping Flat Rate </td>
                                                                <td class="pageTitleSub"><input type="text" name="products_flat_shipping_price" id="products_flat_shipping_price" class="textField-title" style="width:90px" value="<?=number_format($pInfo['products_flat_shipping_price'],2,'.', ',')?>" /></td>
                                                              </tr>
													        </table>
                                                            <div class="mb20"></div>
                                                            
                                                            <div id="product_description_toggle">
                                                            <table width="100%" cellpadding="0" cellspacing="0">
                                                              	<tr>
                                                                	<td width="29"><img src="images/sectionTitleArrow.jpg" width="29" height="28" id="" /></td>
                                                                    <td class="sectionTitle">Product Description</td>
                                                                </tr>
                                                            </table>
                                                            </div>
													        <div class="expandable" id="product_description">
													        <table width="100%" border="0" cellspacing="0" cellpadding="5"> 
                                                                <tr>
                                                                  <td><textarea name="products_info_desc" id="products_info_desc" cols="45" rows="5"><?=output($pInfo['products_info_desc'])?></textarea></td>
                                                                </tr>
												            </table>
												            </div>
                                                            <div class="mb20"></div>
                                                            
                                                            <!--
                                                            <div id="product_options_toggle">
                                                            <table width="100%" cellpadding="0" cellspacing="0">
													              <tr>
													                <td width="29"><img src="images/sectionTitleArrow.jpg" width="29" height="28" id="" /></td>
													                <td class="sectionTitle">Product Options</td>
												                  </tr>
											                </table>
                                                            </div>
										              <div class="expandable" id="product_options">
													            
													            <table width="100%" border="0" cellspacing="0" cellpadding="5">
													              <tr>
													                <td width="16%" class="fieldLabel">Option 1</td>
													                <td width="84%" class="fieldLabel"><select name="select" class="textField-title" id="select">
													                  <option value="">None</option>
												                    </select></td>
												                  </tr>
													              <tr>
													                <td class="fieldLabel">Option 2</td>
													                <td class="fieldLabel"><select name="select" class="textField-title" id="select">
													                  <option value="">None</option>
												                    </select></td>
												                  </tr>
													              <tr>
													                <td class="fieldLabel">Option 3</td>
													                <td class="fieldLabel"><select name="select" class="textField-title" id="select">
													                  <option value="">None</option>
												                    </select></td>
												                  </tr>
													              <tr>
													                <td colspan="2" class="fieldLabel">&nbsp;</td>
												                  </tr>
												                </table>
												              </div>
                                                            
                                                            -->
												          <div class="mb20"></div></td>
													      <td width="250" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="">
													        <tr>
													          <td><div style="margin:0px 10px 10px 10px;">
													            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
													              <tr>
													                <td class="headerCell"><h2>Post Product</h2></td>
												                  </tr>
													              <tr>
													                <td><strong>Status</strong></td>
												                  </tr>
													              <tr>
													                <td><select name="products_status" id="products_status">
													                  <option value="active" <? if($a['products_status']=='active') echo " selected"; ?>>Active</option>
													                  <option value="inactive" <? if($a['products_status']=='inactive') echo " selected"; ?>>Inactive</option>
													                  </select></td>
												                  </tr>
                                                                  <tr>
                                                                  	<td><label>Feature on homepage? <input name="products_featured" type="checkbox" id="products_featured" value="1" <? if($pInfo['products_featured']) echo " checked"; ?> /></label></td>
                                                                  </tr>
													              <tr>
													                <td><div class="mt10"></div>
													                  <strong>Display product on:</strong><br />
													                  <?=date("F j, Y", $pInfo['products_release_date'])?>
													                  <a href="javascript:void(0);" id="toggleDate" style="font-size:10px; font-weight:normal; color:#CC0000;">(change)</a>
													                  <div id="publishDate" style="display:none;">
													                    <div id="calendarContainer"></div>
													                    <div class="mt10"></div>
													                    <input type="text" name="products_release_date" id="products_release_date" value="<?=date('d/m/Y', $pInfo['products_release_date'])?>" readonly="readonly" />
												                      </div></td>
												                  </tr>
													              <tr>
													                <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="<?=$buttonText?>" /></td>
												                  </tr>
												                </table>
												              </div></td>
												            </tr>
													        </table></td>
												        </tr>
												      </table>
  </form>
											<?
											
											break;
						
						
						
						
						
						
					}


break;


}
?>


</div>
</body>
</html>
