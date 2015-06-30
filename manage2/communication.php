<?
include('master.inc.php');
include('application.php');

if(!$_SESSION['user_logged_in'] || !isset($_SESSION['client'])){
	addError('Your session has timed out please login again');
	redirect(PAGE_LOGIN);
}

$trail = new breadcrumb();//start breadcrumb class
$trail->add("Dashboard", PAGE_DEFAULT);
$trail->add("Communication", "");
if($_GET['action'] == 'senditout') {
	//we are going to send out the newsletter now!
	sendNewsletter($_GET['id']);
}
if($_GET['action'] == 'pause_message') {
	pauseMessage($_GET['id']);
	addMessage("The message you selected has been put on hold");
	redirect(PAGE_COMMUNICATION);
}
if($_GET['action'] == 'unpause_message') {
	unpauseMessage($_GET['id']);
	addMessage("The message you selected has been put back into queue");
	redirect(PAGE_COMMUNICATION);
}

if($_GET['action'] == 'deletemessage') {
	deleteFromQueue($_GET['id']);
	addMessage("The message you selected has been deleted");
	redirect(PAGE_COMMUNICATION);
}
if($_GET['action'] == 'deletetemplate') {
	dbQuery('DELETE FROM email_templates WHERE email_templates_id = ' . $_GET['id']);
	addMessage("Removed template successfully");
	redirect(PAGE_COMMUNICATION.'?section=templates');
}
if($_GET['action'] == 'deletearchive') {
	dbQuery('DELETE FROM email_queue_sent WHERE email_queue_sent = ' . $_GET['id']);
	addMessage("The archived message was deleted");
	redirect(PAGE_COMMUNICATION);
}	
if($_GET['action'] == 'deletesubscriber') {
	dbQuery('DELETE FROM subscribers WHERE subscriber_id = ' . $_GET['id']);
	addMessage("The subscriber has been removed from the list");
	redirect(PAGE_COMMUNICATION."?section=subscribers");
}

if($_GET['action'] == 'delete' && $_GET['section'] == "subscribers") {
      dbQuery('DELETE FROM subscriber_to_lists WHERE subscriber_id = ' . $_GET['id']);
      dbQuery('DELETE FROM subscribers WHERE subscriber_id = ' . $_GET['id']);
      
      addMessage("Removed subscriber successfully");
      redirect('communication.php?section=subscribers');    
}

if($_GET['action'] == 'deletelist' && $_GET['id'] != ''){
	dbQuery('DELETE FROM subscriber_lists WHERE subscriber_lists_id = ' . $_GET['id']);

	addMessage("Removed list successfully");
	redirect('communication.php?section=lists');
}

function addUserToList($vars) {
	//delete them first!
	dbQuery('DELETE FROM subscriber_to_lists WHERE subscriber_id = ' . $vars['id']);
	//check if array
	if(isset($vars['lists'])) {
	  foreach($vars['lists'] as $list) {
		  $row['subscriber_lists_id'] = $list;
		  $row['subscriber_id'] = $vars['id'];
		  dbPerform('subscriber_to_lists', $row, 'insert');
	  }
  
	}
}
//save the differet types of content here =) 
if(isset($_POST['action'])) {
	switch($_POST['action']){
	case 'list':
						$row['subscriber_lists_name'] = $_POST['subscriber_lists_name'];
						$row['subscriber_lists_desc'] = $_POST['subscriber_lists_desc'];
						$row['subscriber_lists_call_name'] = $_POST['subscriber_lists_call_name'];
						
						if($_POST['id']) {
							dbPerform('subscriber_lists', $row, 'update', 'subscriber_lists_id = ' . $_POST['id']);
							addMessage('Updated list successfully');
							redirect(PAGE_COMMUNICATION.'?section='.$_POST['section']);
						} else {
							$row['subscriber_lists_date_added'] = time();
							dbPerform('subscriber_lists', $row, 'insert');
							addMessage('Add list successfully');
							redirect(PAGE_COMMUNICATION.'?section='.$_POST['section']);
						}
			
	break;
	case 'addsubscriber':
	case 'savesubscriber':
							$exists = false;
							if($_POST['action'] == 'addsubscriber') {
								//check to see if the user exists first!
								$check = dbQuery('SELECT * FROM subscribers WHERE subscriber_email_address = "'.$_POST['subscriber_email_address'].'"');
								if(dbNumRows($check)) {
									$exists = true;
								}
							}
							
							//this user might be atached to multiple lists! so muahah? bahah? who?
							
							$row['subscriber_name'] = $_POST['subscriber_name'];
							$row['subscriber_email_address'] = $_POST['subscriber_email_address'];
							$row['subscriber_sms_agree'] = $_POST['subscriber_sms_agree'];
							$row['subscriber_newsletter_agree'] = $_POST['subscriber_newsletter_agree'];
							$row['subscriber_phone'] = $_POST['subscriber_phone'];
							$row['subscriber_mobile'] = $_POST['subscriber_mobile'];
							$row['subscriber_address'] = $_POST['subscriber_address'];
							$row['subscriber_address2'] = $_POST['subscriber_address2'];
							$row['subscriber_city'] = $_POST['subscriber_city'];
							$row['subscriber_state'] = $_POST['subscriber_state'];
							$row['subscriber_zipcode'] = $_POST['subscriber_zipcode'];
							$row['subscriber_notes'] = $_POST['subscriber_notes'];
							
							
							if($_POST['action'] == 'addsubscriber') {
								if(!$exists) {
								$row['subscriber_date_added'] = time();
								dbPerform('subscribers', $row, 'insert');
								$_POST['id'] = dbInsertID();
								addUserToList($_POST);
									addMessage("Add subscriber successfully");
									redirect(PAGE_COMMUNICATION."?section=subscribers");
								} else {
									addError("The email address already exists!");
									redirect(PAGE_COMMUNICATION."?section=subscribers");	
								}
							} 
							if($_POST['action'] == 'savesubscriber'){
								addUserToList($_POST);
								dbPerform('subscribers', $row, 'update', 'subscriber_id='.$_POST['id']);
								addMessage("saved subscriber successfully");
								redirect(PAGE_COMMUNICATION."?section=subscribers");
							}
	break;
	case 'import':
						
							//check to make sure it's set
							if($_FILES['fileField']['name'] != "") {
								
								//now we are going to import them to the list!
								$numberOfContacts = upload_contacts($_FILES['fileField']['tmp_name']);
								
								addMessage("Added " . $numberOfContacts . " to your subscriber list");
								
							} else {
								addMessage("You did not upload anything");	
							}
							redirect(PAGE_COMMUNICATION."?section=subscribers");
						
	break;
	case 'savetemplate':
					//save the template!!!!
					
							$row['email_templates_name'] = input($_POST['title']);
							$row['email_templates_desc'] = input($_POST['desc']);
							$row['email_templates_code'] = input($_POST['code']);
							//$row['email_templates_body'] = input($_POST['bodyCode']);
							if($_POST['id'] != "") {
								dbPerform('email_templates', $row, 'update', 'email_templates_id='.$_POST['id']);
								addMessage('Saved template successfully');
								redirect(PAGE_COMMUNICATION."?section=templates");
							}else{
								$row['email_templates_date_added'] = time();
								dbPerform('email_templates', $row, 'insert');
								addMessage('Added template successfully');
								redirect(PAGE_COMMUNICATION."?section=templates");
							} 
					
	break;
	case 'queue_message':
	
					/*
					Add to queue:
						 Array ( [client_id] => 1 [email_queue_date_added] => 1240331339 
						[email_queue_release_date] => 1241067600
						[email_queue_recipients] => bob@hotmail.com,mike@interactivearmy.com [email_queue_subject] => 
						[email_queue_email_text] => [email_template_id] => 4 )

					*/
					//check sendto.. if all then we need to compile a list of email addresses!
					if($_POST['sendto'] == 'all' ){ //we need to manually get all emails from db table.
						$subscriberResults = dbQuery('SELECT subscriber_name, subscriber_email_address FROM subscribers WHERE subscriber_email_address != "" AND subscriber_newsletter_agree = 1');
						while($s=dbFetchArray($subscriberResults)) {
							$recips .= $s['subscriber_name'].'<'.$s['subscriber_email_address'].'>,';
							
						}
						$recips = "all";
					} 
					
					if($_POST['sendto'] == 'subscribers'){
						//means the recipients are going to be in the textarea
						$recips = $_POST['emailAddresses'];
					}
					
						//get the content of the email!
						//we should store the template ID
						//store text!
						//we need to be able to archive  emails they have sent.
						//we are going to store the content which includes the template content as well as the email content and combine this into 1
						
						//get header footer and text!
						//email_content_text
						
						/* REMOVED THE TEMPLATE FUNCTIONALITY! */
						
						//$tResults = dbQuery('SELECT * FROM email_templates WHERE email_templates_id = ' . $_POST['email_template']);
						//$tInfo = dbFetchArray($tResults);
						
						
						//$content = $tInfo['email_templates_header'];
						$content = output($_POST['email_content_text']);
						
						$field['client_id'] = 1;
						$field['email_queue_date_added'] = time();
						$field['email_queue_release_date'] = strtotime($_POST['publish_date']);
						$field['email_queue_recipients'] = $recips;
						$field['email_queue_subject'] = $_POST['email_subject'];
						$field['email_queue_email_text'] = $content;
						//$field['email_template_id'] = $_POST['email_template'];//we are using this template!
						
						
						
						if($_POST['email_from_new'] != "") {
							$field['email_queue_from'] = $_POST['email_from_new'];
							//add this to the fromt table
							$from['email_queue_from_email'] = $_POST['email_from_new'];
							dbPerform('email_queue_from', $from, 'insert');
						} else {
							$field['email_queue_from'] = $_POST['email_from'];
						}
						//add to queue!
						
						
						$field['email_display_home'] = $_POST['email_display_home'];
						
						if($_FILES['attachment']['name'] != "") {
							//we are going to attach a document to the newsletter!
							$filename = fixFilename($_FILES['attachment']['name']);
							uploadAttachment($_FILES['attachment'], $filename);
							$field['email_queue_attachment'] = $filename;
						}
						
						if($_POST['email_display_home']) {
							//add this to the newsletter table which is displayed on the homepage!
							$rows['newsletter_title'] = $_POST['email_subject'];
							$rows['newsletter_subject'] = $_POST['email_subject'];
							$rows['newsletter_attachment'] = $filename;
							$rows['newsletter_date_added'] = time();
							$rows['newsletter_content'] = $content;
							
							
						}
						
						if(isset($_POST['email_id']) && $_POST['email_id'] != '') {
							if($_POST['send'] == 'now'){
							//	sendNewsletter($_POST['email_id']);	
								$field['email_queue_release_date'] = time();//set the time to now! other wise, if it's set to go on specific date we add the time as 1:00am on that day!
							}
							dbPerform('email_queue', $field, 'update', 'email_queue_id = ' . $_POST['email_id']);
							$queueID = dbInsertID();
							
							if($_POST['email_display_home']) {
								dbPerform("newsletters", $rows, "update", 'email_queue_id = ' . $_POST['email_id']);
							}
							
							
							addMessage('Your message has been saved');
							redirect(PAGE_COMMUNICATION);
							
						} else {
							if($_POST['send'] == 'now'){
								//sendNewsletter($queueID);	
								$field['email_queue_release_date'] = time();//set the time to now! other wise, if it's set to go on specific date we add the time as 1:00am on that day!
							}
							dbPerform('email_queue', $field, 'insert');
							$queueID = dbInsertID();
							
							if($_POST['email_display_home']) {
								$rows['email_queue_id'] = $queueID;
								dbPerform('newsletters', $rows, 'insert');
							}
							addMessage('Your message has been added to the queue, you can view the queue at anytime by clicking on the Queue tab');
							redirect(PAGE_COMMUNICATION);
						}
						
						
						
	break;
	}
}
if(!$_GET['section']) {
	if(user_has_permission(18)){
		$default = 'templates';	
	}
	if(user_has_permission(17)){
		$default = 'compose_email';	
	}
	if(user_has_permission(16)){
		$default = 'queue';	
	}
	
	
	$_GET['section'] = $default;
}

$page = $_GET['section'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=COMPANY_NAME?> :: Powered by Intelligence Center</title>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	 google.load("jquery", "1.4.2");
</script>
<script language="javascript" src="jscripts/jquery.colorbox-min.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />
<script language="javascript" src="jscripts/jquery.simpletip-1.3.1.min.js"></script>
<script language="javascript" src="jscripts/jquery.field.min.js"></script>
<script language="javascript" src="nav.js"></script>
<script type="text/javascript" src="jscripts/cicfunctions.js"></script>
<script type="text/javascript" src="jscripts/ui.datepicker.js"></script>
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
  
  $('#now').click(function(){
	$('#calendarContainer').slideUp();
  });
  $('#queue').click(function(){
	$('#calendarContainer').slideDown();
  });
  $("#calendarContainer").datepicker(
	{
	altField: '#publish_date',
	altFormat: 'mm/dd/yy',
	minDate: "+1",
	maxDate: "+6M"
	<? 
	if($_GET['section'] == 'compose_email' && isset($_GET['id'])) 
	{
		$dResults = dbQuery('SELECT email_queue_release_date FROM email_queue WHERE email_queue_id='.$_GET['id']);
		$date = dbFetchArray($dResults);?>
		,
		defaultDate: new Date("<?=date('m/d/Y', $date['email_queue_release_date'])?>") <?
	}
	?>
	}
  );

	$("input[name='recipient']").click(function() {
		$('#emailList').val( $("input[name='recipient']").getValue() );
	});
	
	$('#all').click(function() {
		$('#emailAddresses').value = "";
	});

	$('#addToQueue').click( function() {
			var valid = true;
			var alertMessage = "";
			//check to see if there are any errors in adding or sending the message.
			if($('#email_subject').val() == "") {
				valid = false;
				alertMessage = alertMessage + "Please enter a subject\n";
			}
			if($('#subscribers:checked').val()!=null && $('#emailList').val() == "") {
				valid = false;
				alertMessage = alertMessage + "Please select some recipients\n";
				
			}
			
			if(valid){
				return true;	
			} else {
				alert(alertMessage);
				return false;	
			}
	});
	$('#subscribers').click(function() { 
		//display subscriber area
		$('#subscriberList').slideDown();
		
	});
	$('#email').click(function() { 
		//display subscriber area
		$('#subscriberList').slideUp();
	});
	$('#all').click(function() { 
		//display subscriber area
		$('#subscriberList').slideUp();
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
	paste_auto_cleanup_on_paste : true,
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
	height: "500",
	<? 
	if(!isset($_GET['id'])){
	?>
	oninit : function(ed) {
		$.get("__getContentBlock.php", { id: 2 },
			function(data){
				tinyMCE.get('email_content_text').setContent(data);
		});
	}
	<?
	}
	?>
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
<? include('communication_sub_menu.php'); ?>
<?
switch($_GET['section']) {
case 'templates': 
				switch($_GET['action']) {
				case 'manage':
				default:
									?>
                                    
                                   
                                   <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                                   <tr>
                                    <td valign="top" class="formBody">
                                    <div class="mb20"></div> 
                                    <form id="form2" name="form1" method="post" action="">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                        <tr>
                                          <td  nowrap="nowrap"><h2>Current Templates</h2>
                                          
                                      
                                      <?php
									  $templateResults = dbQuery('SELECT * FROM email_templates WHERE client_id = ' . $_SESSION['client'] . ' ORDER BY email_templates_date_added DESC');
									  
									  if(dbNumRows($templateResults)) {
										  ?>
                                      <br />
                                      <div style="margin-left:10px;"></div>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                        <tr >
                                          <td class="tableRowHeader" ><input type="checkbox" name="checkAll2" id="checkAll2" /></td>
                                          <td class="tableRowHeader" align="left">Title</td>
                                          <td class="tableRowHeader"  align="left">Date Added</td>
                                          <td class="tableRowHeader"  align="left">&nbsp;</td>
                                        </tr>
                                        <?
										$count=0;
										while($tInfo = dbFetchArray($templateResults)) {
										$row = $count % 2;
										?>
                                        <tr>
                                          <td class="row<?=$row?>"><input type="checkbox" name="checkbox2" id="checkbox2" /></td>
                                          <td class="row<?=$row?>"><a href="<?=PAGE_COMMUNICATION?>?action=edit&amp;section=templates&amp;id=<?=$tInfo['email_templates_id']?>"><?=output($tInfo['email_templates_name'])?></a></td>
                                          <td class="row<?=$row?>"><? echo date('m/d/Y', $tInfo['email_templates_date_added']); ?></td>
                                          <td align="right" class="row<?=$row?>"><a class="table_edit_link" href="<?=PAGE_COMMUNICATION?>?action=edit&amp;section=templates&amp;id=<?=$tInfo['email_templates_id']?>">Edit</a> <a class="table_delete_link" onclick="return confirm('Are you sure you want to delete this template? this is not undoable.');" href="<?=PAGE_COMMUNICATION?>?action=deletetemplate&amp;section=templates&amp;id=<?=$tInfo['email_templates_id']?>">Delete</a></td>
                                        </tr>
                                        <?
										$count++;
										}
										?>
                                      </table>
                                      <?
									  } else {
										echo "No Templates found <a href=\"".PAGE_COMMUNICATION."?section=templates&action=new\">Create a new template</a>";  
									  }
									  ?>
                                      
                                      </td>
                                    </tr>
                                  </table>
                                </form>
                              </td>
                            </tr>
                          </table>
                            
                            <?
				break;
				case 'new':
				case 'edit':
				
							if($_GET['action'] == 'add') {
								$tInfo = array();
								$heading = 'New Template';
							}
							
							if($_GET['action'] == 'edit') {
								$templateResults = dbQuery('SELECT * FROM email_templates WHERE email_templates_id = '.$_GET['id']);
								$tInfo = dbFetchArray($templateResults);
								$heading = output($tInfo['email_templates_name']);
							}
							?>
                             <form id="form" name="form1" method="post" action="<?=PAGE_COMMUNICATION?>">
                             <input type="hidden" name="action" value="savetemplate" />
                             <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                             <input type="hidden" name="return_url" value="<?=selfURL()?>" />
                             
                             
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                              <tr>
                                <td valign="top" class="formBody">
                                <div class="mb20"></div>
                                  
                                  <table border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td  nowrap="nowrap">
                                      <h2><?=$heading?></h2>
                                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                        <tr>
                                          <td class="pageTitleSub">Template Name (100 chars max)</td>
                                        </tr>
                                        <tr>
                                          <td><input name="title" type="text" class="textField-title" id="title" style="width:650px" value="<?=output($tInfo['email_templates_name'])?>" maxlength="100" /></td>
                                        </tr>
                                        <tr>
                                          <td class="pageTitleSub">Template Desc</td>
                                        </tr>
                                        <tr>
                                          <td><input type="text" name="desc" id="desc" class="textField-title" style="width:650px" value="<?=output($tInfo['email_templates_desc'])?>" /></td>
                                        </tr>
                                        <tr>
                                          <td class="pageTitleSub">Template HTML Code <a href="javascript:void(0);" style="font-size:10px; font-weight:normal;" onmousedown="tinyMCE.get('code').hide();">[Hide Editor]</a> | <a href="javascript:void(0);" style="font-size:10px; font-weight:normal;" onmousedown="tinyMCE.get('code').show();">[Show Editor]</a></td>
                                        </tr>
                                        <tr>
                                          <td><textarea name="code" id="code" style="width:650px;" class="mceEditor" rows="15"><?=output($tInfo['email_templates_code'])?></textarea></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                  </table>
                                </td><td width="250" valign="top">
                                <div style="margin:0px 10px 10px 10px;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                  <tr>
                                    <td class="headerCell"><h2>Publish Email Template</h2></td>
                                  </tr>
                                  <tr>
                                    <td>Save the email template?</td>
                                  </tr>
                                 
                                  <tr>
                                    <td class="saveField"><input name="button4" type="submit" class="secondaySubmit" id="button4" value="Save" /></td>
                                  </tr>
                                </table> 
                                
                                </div>
                                
                                
                                </td>
                              </tr>
                            </table></form>
                            <?
				break;
				case 'deleteconfirm':
				
				break;
				}
							

break;
case 'import':
						?>
                         <form action="<?=PAGE_COMMUNICATION?>" method="post" enctype="multipart/form-data" name="form1" id="form">
                         <input type="hidden" name="action" value="import" />
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                              <tr>
                                <td valign="top" class="formBody"><table width="100%" border="0" cellpadding="5" cellspacing="0">
                                <tr>
                                      <td  nowrap="nowrap">
                                      <h2>Import Contacts</h2>
                                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                        <tr>
                                          <td class="pageTitleSub">Upload a CSV of email contacts                                          </td>
                                        </tr>
                                        <tr>
                                          <td><span class="pageTitleSub">
                                            <input name="fileField" type="file" class="textField-title" id="fileField" />
                                          </span></td>
                                        </tr>
                                        <tr>
                                          <td class="pageTitleSub">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td><p>The CSV file needs to be in a well formed format, an example below</p>
                                            <table width="265" border="0" cellspacing="0" cellpadding="5" bgcolor="#FFFFFF" style="border:1px solid #ccc;">
                                              <tr>
                                                <td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc;">john@doe.com</td>
                                                <td style="border-bottom:1px solid #ccc;">Name (optional)</td>
                                              </tr>
                                              <tr>
                                                <td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc;">mike@interactivearmy.com</td>
                                                <td style="border-bottom:1px solid #ccc;">Name (optional)</td>
                                              </tr>
                                            
                                            </table>
                                            <p>and so on</p>
                                          <p>&nbsp;</p></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                  </table>
                                </td><td width="250" valign="top">
                                <div style="margin:0px 10px 10px 10px;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                  <tr>
                                    <td class="headerCell"><h2>Import Contacts</h2></td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                  </tr>
                                 
                                  <tr>
                                    <td class="saveField"><input name="button4" type="submit" class="secondaySubmit" id="button4" value="Save" /></td>
                                  </tr>
                                </table> 
                                
                                </div>
                                
                                
                                </td>
                              </tr>
                            </table></form>
  </table>
					   <?
break;
case 'lists':

	switch($_GET['action']) {
		case 'view':
		default:
		?>
							<table width="100%" border="0" cellspacing="0" cellpadding="0" >
							  <tr>
							    <td valign="top" class="formBody">
                                
                                <?
								if(isset($_GET['action'])){
									if($_GET['action'] == 'new') {
										$buttonText = "Add list";
										$sInfo = array();
										$title = "Add list";
										$newsletter_agree = true;
										$sms_agree = false;
										$action = 'add';
									} 
									if($_GET['action'] == 'edit') {
										$buttonText = "Save List";
										$subscriberResults = dbQuery('SELECT * FROM subscribers WHERE subscriber_id = ' . $_GET['id']);
										$sInfo = dbFetchArray($subscriberResults);
										$title = "Edit Subscriber";
										$newsletter_agree = $sInfo['subscriber_newsletter_agree'];
										$sms_agree = $sInfo['subscriber_sms_agree'];
										$action = 'save';
									}
									?>
									
								  
									<?
								}
								?>
                                  <?
							 //get umm the abc's
						   $count = 0;
						   foreach($alpha as $key=>$val) {
							  $menu .=  ($count != 0 ) ?  " | " : "";
							  $menu .= "<a href=\"".PAGE_COMMUNICATION."?section=lists&s=".$val."\">".$val."</a>";
							  $count++;
						   }
								 if($_GET['s'] && $_GET['s'] != 'all' ) {
									  $where = "WHERE subscriber_lists_name LIKE '".$_GET['s']."%'";
								  } else {
									  $where = "";
								  }
								  $subscriberListResults = dbQuery('SELECT * FROM subscriber_lists '.$where.' ORDER BY subscriber_lists_name ASC');
								  ?>
                                  <div class="mb20"></div>
                                  <h2>Manage your contact lists</h2>
							      
                                  <form>
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td><strong>Sort by: </strong>
                                        <?=$menu?></td>
                                    </tr>
                                  </table>
                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                        <tr>
                                          <td>&nbsp;</td>
                                          <td width="100"><a class="button" href="<?=PAGE_COMMUNICATION?>?section=lists&action=add"><span class="add">Add list</span></a></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                  </table>
                                    <?php 
									if(dbNumRows($subscriberListResults)) { 
									?>
							        <table width="100%" border="0" cellspacing="0" cellpadding="5">
							          <tr>
							            <td class="tableRowHeader" align="left">Name</td>
                                        
                                      	<td align="right" class="tableRowHeader">&nbsp;</td>
                                    </tr>
						             </tr>
							          <?
										$count=0;
										while($sInfo = dbFetchArray($subscriberListResults)) {
										$row = $count % 2;
										?>
							          <tr>
							            <td class="row<?=$row?>"><?=output($sInfo['subscriber_lists_name'])?></td>
							            <td class="row<?=$row?>" align="right">
                                        <a class="table_user_link" href="<?=PAGE_COMMUNICATION?>?section=subscribers&filter=<?=$sInfo['subscriber_lists_call_name']?>">Contacts</a>
                                        <a class="table_edit_link" href="<?=PAGE_COMMUNICATION?>?action=edit&section=lists&id=<?=$sInfo['subscriber_lists_id']?>">Edit</a>
                                        <a class="table_delete_link" href="<?=PAGE_COMMUNICATION?>?action=deletelist&section=lists&id=<?=$sInfo['subscriber_lists_id']?>" onclick="return confirm('Are you sure you want to delete this list?');">Delete</a>
                                        </td>
							            
						              </tr>
							          <?
									  $count++;
									  }
									  ?>
						            </table>
						          </form>
							      <?
								  } else {
								   ?>
							      <div class="" style="padding:10px;">0 Results Returned</div>
							      <?
								  }
								  ?>
                                  </td>
                                </tr>
                                </table><div class="mb20"></div>

<?		
break;
		case 'add':
		case 'edit':
		if($_GET['action'] == 'edit') { 
			$title = "Edit list"; 
			$listResults = dbQuery('SELECT * FROM subscriber_lists WHERE subscriber_lists_id = ' . $_GET['id']);
			$lInfo = dbFetchArray($listResults);
		} else { 
			$title = "Add list"; 
			$lInfo = array();
		}
		?>
        <form id="contactListForm" action="communication.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="section" value="<?=$_GET['section']?>" />
        <input type="hidden" name="action" value="list" />
        <input type="hidden" name="id" value="<?=$_GET['id']?>" />
        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr>
                 <td valign="top" class="formBody">
                 <div id="contactList">
                 <div class="mb20"></div>
                 <h2><?=$title?></h2>
                    <table width="100%" border="0" cellspacing="1" cellpadding="5">
                      <tr>
                        <td width="110"><strong>List Name</strong></td>
                        <td width=""><input name="subscriber_lists_name" type="text" style="width:400px;" value="<?=$lInfo['subscriber_lists_name']?>" class="textField-title" id="subscriber_lists_name" /></td>
                      </tr>
                      <tr>
                        <td valign="top"><strong>Description</strong></td>
                        <td><textarea name="subscriber_lists_desc" id="subscriber_lists_desc" style="width:400px;" cols="45" rows="5"><?=$lInfo['subscriber_lists_call_name']?></textarea></td>
                      </tr>
                      <tr>
                        <td width="110"><strong>Call Name</strong><br /><span class="smallText">This should be 1 word, used to describe the list</span></td>
                        <td width=""><input name="subscriber_lists_call_name" type="text" style="width:400px;" value="<?=$lInfo['subscriber_lists_call_name']?>" class="textField-title" id="subscriber_lists_call_name" /></td>
                      </tr>
                    </table>
                  </div>
                  <div id="formResponse" style="display:none;"></div>
                </td>
                <td width="250" valign="top">
                <div style="margin:0px 10px 10px 10px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                  <tr>
                    <td class="headerCell"><h2>Contact Lists</h2></td>
                  </tr>
                  <tr>
                    <td>Added on <?=date('m/d/Y g:i a')?></td>
                  </tr>
                  <tr>
                    <td class="saveField"><input name="button4" type="submit" class="secondaySubmit" id="button4" value="Save" /></td>
                  </tr>
                </table> 
                </div>
                </td>
            </tr>
        </table></form>
        <?
		break;
			
	}
						
break;

case 'compose_email':		

							
							
							if(isset($_GET['id']) && $_GET['id'] != "") { //we are editing a current message!
								
								if($_GET['archive'] == 'true') {
									$messageResults = dbQuery('SELECT * FROM email_queue_sent WHERE email_queue_sent  = ' . $_GET['id']);
									$mInfo = dbFetchArray($messageResults);
									$mInfo['email_queue_email_text'] = $mInfo['email_queue_content'];
									$publish_date = $mInfo['email_queue_release_date'];
									$send = 'later';
								} else {
									$messageResults = dbQuery('SELECT * FROM email_queue WHERE email_queue_id = ' . $_GET['id']);
									$mInfo = dbFetchArray($messageResults);
									$publish_date = $mInfo['email_queue_release_date'];
									$send = 'later';
								}
								
								
								
							} else {
								$mInfo = array();
								$publish_date = time();
								$send = 'now';
							}
							
							if(isset($_GET['sentid'])) {
								$messageResults = dbQuery('SELECT * FROM email_queue_sent WHERE email_queue_sent = ' . $_GET['sentid'] . ' AND client_id = ' . $_SESSION['client']);
								$mInfo = dbFetchArray($messageResults);
								$mInfo['email_content_text'] = $mInfo['email_queue_content'];
								
								$publish_date = $mInfo['email_queue_date_sent'];
								$send = 'later';
							}
							?>
                                <form action="<?=PAGE_COMMUNICATION?>" method="post" enctype="multipart/form-data" name="form1" id="form2" style="margin:0px; padding:0px;">
                                <input type="hidden" name="action" value="queue_message" />
                                <input type="hidden" name="user_id" value="<?=$_SESSION['user_id']?>" />
                                <input type="hidden" name="email_id" value="<?=$_GET['id']?>" />
                               
							<table width="100%" border="0" cellspacing="0" cellpadding="0" >
							  <tr>
								<td valign="top" class="formBody"><table width="100%" border="0" cellspacing="0" cellpadding="5">
						        <tr>
								      <td colspan="2" class="pageTitleSub">Email Subject</td>
							        </tr>
								    <tr>
								      <td colspan="2" class="pageTitleSub"><input type="text" name="email_subject" id="email_subject" class="textField-title" style="width:650px" value="<?=output($mInfo['email_queue_subject'])?>" /></td>
							        </tr>
									<tr>
									  <td colspan="2">
                                       <? echo printEditorCB("email_content_text", "email_content_text", ''); ?>
                                      
                                     
                                      </td>
								    </tr>
									<tr>
									  <td colspan="2" class="pageTitleSub">Email From</td>
								    </tr>
									<tr>
									  <td colspan="2" class="pageTitleSub"><select name="email_from" id="email_from" class="textField-title">
									    <?
										$fromResults = dbQuery('SELECT DISTINCT(email_queue_from_email) FROM email_queue_from');
										while($f=dbFetchArray($fromResults)){
											echo "<option value=\"".$f['email_queue_from_email']."\">".$f['email_queue_from_email']."</option>\n";
										}
										?>
									    </select>
									    or add new:
									    <input type="text" name="email_from_new" id="email_from_new" class="textField-title" style="width:250px;" /></td>
								    </tr>
									<tr>
									  <td colspan="2" class="pageTitleSub">Email Attachment: </td>
								    </tr>
									<tr>
									  <td colspan="2" class="pageTitleSub"><input name="attachment" type="file" class="textField-title" id="attachment" /></td>
							      </tr>
									<tr>
									  <td colspan="2" class="pageTitleSub">Select Recipients</td>
								    </tr>
									<tr>
									  <td colspan="2"><table width="500">
									    <tr>
									      <td><label>
									        <input name="sendto" type="radio" id="all" value="all" checked="checked" />
									        Everyone</label></td>
								        </tr>
									    <tr>
									      <td><label>
									        <input type="radio" name="sendto" value="subscribers" id="subscribers" />
									        Selected Subscribers</label>
                                             <div id="subscriberList" style="display:none; border:1px solid #CCC; margin:10px 5px;">
                                      		 
                                             <?
											 $subscriberResults = dbQuery('SELECT * FROM subscribers WHERE subscriber_email_address != ""  ORDER BY subscriber_name');
											 $subscriberCount = dbNumRows($subscriberResults);
											 if($subscriberCount > 10){
												echo '<div  style="height:300px; overflow:auto; ">'; 
											 }
											 echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\">\n";
											 $count = 0;
											 while($sInfo = dbFetchArray($subscriberResults)) {
												 $row = $count % 2;
												 echo "<tr>\n";
												 echo " <td width=\"1\" class=\"row".$row."\"><input type=\"checkbox\" id=\"checkbox_".$count."\" name=\"recipient\" value=\"".$sInfo['subscriber_email_address']."\" /></td>\n";
												 echo "	<td class=\"row".$row."\"><label for=\"checkbox_".$count."\">".$sInfo['subscriber_name']."&lt;".$sInfo['subscriber_email_address']."&gt;</label></td>\n";
												 echo "</tr>\n";
											 $count++;
											 }
											 
											 echo "</table>\n";
											
											 ?>
                                             </div>
                                             
                                             <div id="emailAddressList"></div></td>
								        </tr>
									    <tr>
									      <td><strong>Email Address</strong><br />
									        <span class="smallText">(seperate by comma)<br />
									        </span>
                                          <textarea name="emailAddresses" id="emailList" style="width:500px; height:50px;"><?=output($mInfo['email_queue_recipients'])?></textarea></td>
								        </tr>
								      </table></td>
								    </tr>
									<tr>
									  <td width="198">&nbsp;</td>
									  <td width="894">&nbsp;</td>
									</tr>
								  </table>
								</td>
								<td valign="top" width="250"><div style="margin:0px 10px 10px 10px;">
								  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
									<tr>
									  <td class="headerCell"><h2>Send </h2> </td>
									</tr>
										
									<tr>
									  <td><strong>
                                      <input name="send" type="radio" id="now" value="now" <? if($send == 'now') echo " checked"; ?> />
Send it out now</strong></td>
									</tr>
									<tr>
									  <td><strong>
										<input type="radio" name="send" id="queue" value="queue" <? if($send == 'later') echo " checked"; ?> />
										Schedule day</strong></td>
									</tr>
									<tr>
									  <td><div id="calendarContainer" style="padding:5px 0px; <? if(!isset($_GET['id'])) echo " display:none;";?>" align="center">
										
									  </div>
                                        <strong>Date: </strong>
                                        <input type="text" name="publish_date" id="publish_date" value="<?=date('m/d/y', $publish_date)?>" readonly="readonly" />
										<div class="mt10"></div></td>
									</tr>
									<tr>
									  <td class="saveField"><input name="button2" type="submit" class="secondaySubmit" id="addToQueue" value="Add to queue" /></td>
									</tr>
								  </table>
								</div></td>
							  </tr>
							</table></form>
							<?
break;
case 'queue':
default:
								
							$pendingQueueResults = dbQuery('SELECT * FROM email_queue ORDER BY email_queue_release_date');	
								
							?>
							<table width="100%" border="0" cellspacing="0" cellpadding="0" >
							  <tr>
								<td valign="top" class="formBody">
                                <div class="mb20"></div>
								<h2>Pending newsletters</h2>
								 
                                      <?
									  if(dbNumRows($pendingQueueResults)) { ?>
								     <form>
									  <table width="100%" border="0" cellspacing="0" cellpadding="5">
										<tr>
										  <td><div style="margin-left:10px;"></div></td>
										</tr>
									  </table>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
									  <tr >
									    <td class="tableRowHeader" align="left">Title</td>
									    <td class="tableRowHeader" align="left">Date Added</td>
									    <td class="tableRowHeader" align="left">Release Date</td>
									    <td class="tableRowHeader" align="left">Status</td>
										<td class="tableRowHeader" align="left">&nbsp;</td>
									  </tr>
									  <?
										$count=0;
										while($eInfo = dbFetchArray($pendingQueueResults)) {
										$row = $count % 2;
										?>
									  <tr>
									    <td class="row<?=$row?>">  <a href="<?=PAGE_COMMUNICATION?>?action=edit&amp;section=compose_email&amp;id=<?=$eInfo['email_queue_id']?>"><?=output($eInfo['email_queue_subject'])?></a></td>
									    <td class="row<?=$row?>">
										<?
											if($eInfo['email_queue_date_added'] != 0 && $eInfo['email_queue_date_added'] != '') {
												echo date('m/d/Y', $eInfo['email_queue_date_added']);  
											}
										?>
                                        </td>
									    <td class="row<?=$row?>">
											<?
											 if($eInfo['email_queue_release_date'] != 0 && $eInfo['email_queue_release_date'] != '') {
												echo date('m/d/Y', $eInfo['email_queue_release_date']);  
											}
											?>
                                        </td>
									    <td class="row<?=$row?>">
											<? 
                                            if( $eInfo['email_queue_status'] == 'pending') {
                                                $class = "textPending";	
                                            }else if($eInfo['email_queue_status'] == 'onhold') {
                                                $class = "textInactive";
                                            }else{
                                                $class = "textActive";
                                            }
                                            echo "<span class=\"".$class."\">".$eInfo['email_queue_status']."</span>\n";
											?>			
                                        </td>
									    <td class="row<?=$row?>" align="right">
                                         <? if($eInfo['email_queue_status'] == 'pending') { ?>
                                            <a class="table_pause_link" href="<?=PAGE_COMMUNICATION?>?action=pause_message&id=<?=$eInfo['email_queue_id']?>" title="Put the message on hold">Hold</a>
                                            <? }if($eInfo['email_queue_status'] == 'onhold') { ?>
                                            <a class="table_unpause_link" href="<?=PAGE_COMMUNICATION?>?action=unpause_message&id=<?=$eInfo['email_queue_id']?>" title="Take message off hold">Resume</a>
                                            <? } ?>
                                        <a class="table_edit_link" href="<?=PAGE_COMMUNICATION?>?section=compose_email&id=<?=$eInfo['email_queue_id']?>&" title="Edit message">Edit</a>
                                        <a class="table_mail_link" href="<?=PAGE_COMMUNICATION?>?action=senditout&id=<?=$eInfo['email_queue_id']?>" title="Send it out now!" onclick="return confirm('Are you sure you want to send out this newsletter now?? This is not undoable!');">Send</a>
                                        
                                           
                                            
                                            
                                            <a class="table_delete_link" href="<?=PAGE_COMMUNICATION?>?action=deletemessage&id=<?=$eInfo['email_queue_id']?>" title="Delete this message" onclick="return confirm('Are you sure you want to delete this message? THIS WILL DELETE THE MESSAGE ENTIRELY');">Delete</a>
                                          </td>
									  </tr>
									  <?
													$count++;
													}
													?>
								    </table>
								  </form>
                                      <?
									  } else {
									  	?>
                                        <div class="alertBox">Queue is empty</div>
                                        <?
									  }
									  ?>
									
								  <div class="mb20"></div>
								  <h2>Sent Newsletters</h2>
									<?
									$sentQueueResults = dbQuery('SELECT * FROM email_queue_sent ORDER BY email_queue_date_sent');
									?>
                                      <?
									  if(dbNumRows($sentQueueResults)) { ?>
									  <form>
									  <table width="100%" border="0" cellspacing="0" cellpadding="5">
										<tr>
										  <td><div style="margin-left:10px;"></div></td>
										</tr>
									  </table>
                                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
										<tr >
										  <td class="tableRowHeader" width="1%"><input type="checkbox" name="checkAll" id="checkAll" /></td>
										  <td class="tableRowHeader"width="39%" align="left">Title</td>
										  <td class="tableRowHeader" width="20%" align="left">Date Sent</td>
										  <td class="tableRowHeader" width="10%" align="left">Status</td>
										  <td class="tableRowHeader" width="10%" align="left">&nbsp;</td>
										</tr>
										<?
										$count=0;
										while($eInfo = dbFetchArray($sentQueueResults)) {
										$row = $count % 2;
										?>
										<tr>
										  <td class="row<?=$row?>"><input type="checkbox" name="checkbox" id="checkbox" /></td>
										  <td class="row<?=$row?>">
											  <a href="<?=PAGE_COMMUNICATION?>?section=compose_email&archive=true&id=<?=$eInfo['email_queue_sent']?>">
												<?=output($eInfo['email_queue_subject'])?>
											  </a></td>
										  <td class="row<?=$row?>"><?
													  echo date('m/d/Y', $eInfo['email_queue_date_sent']);
													  
													  ?>										  </td>
										  <td class="row<?=$row?>"><?
															
															echo "<span class=\"textInactive\">Sent</span>\n";
														?></td>
										  <td class="row<?=$row?>"><table border="0" align="right" cellpadding="5" cellspacing="0">
                                            <tr>
                                            <?php
											if(file_exists($eInfo['email_log_file']) && $eInfo['email_log_file'] != "") { ?>
                                              <td><a href="<?=$eInfo['email_log_file']?>" target="_blank" title="View Log File"><img src="images/icons/notepad_16x16.gif" alt="Log File" width="16" height="16" border="0" /></a></td>
                                            	<?php 
											} 
												?>
                                              <td><a href="<?=PAGE_COMMUNICATION?>?action=deletearchive&id=<?=$eInfo['email_queue_sent']?>" title="Delete this message" onclick="return confirm('Are you sure you want to delete this message? THIS WILL DELETE THE MESSAGE ENTIRELY');"><img src="images/icons/delete_16x16.gif" width="16" height="16" border="0" /></a></td>
                                            </tr>
                                          </table></td>
										</tr>
										<?
													$count++;
													}
													?>
									  </table> 
									  </form>
                                      <?
									  } else {
									  	?>
                                        <div class="alertBox">No archived results returned</div>
                                        <?
									  }
									  ?>
								 
									
								</td>
							  </tr>
							</table>
							<?

break;
case 'compose_sms':
						?>
							<table width="100%" border="0" cellpadding="0" cellspacing="0" >
							  <tr>
							    <td valign="top" class="formBody"><div class="mb20"></div><h2>Coming Soon</h2><div class="mb20"></div></td>
							    <td width="250" valign="top"><div style="margin:0px 10px 10px 10px;"></div></td>
						      </tr>
  </table>
					   <?
break;
case 'subscribers':


					switch($_GET['action']) {
					case 'view':
					default:
								?>
                                <form>
                                <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                      <tr>
                                        <td valign="top" class="formBody">
                                        
                                    <div class="mb20"></div>
                                    <h2>Contacts</h2>
                                          <?
                                     //get umm the abc's
                                   $count = 0;
                                   foreach($alpha as $key=>$val) {
                                      $menu .=  ($count != 0 ) ?  " | " : "";
                                      $menu .= "<a href=\"".PAGE_COMMUNICATION."?section=subscribers&s=".$val."\"";
                                      if($_GET['filter']) $menu.= "&filter=".$_GET['filter'];
                                      $menu .= ">".$val."</a>";
                                      $count++;
                                   }
                                    
                                    if($_GET['s'] && $_GET['s'] != 'all' ) {
                                        $where = "AND subscriber_name LIKE '".$_GET['s']."%'";
                                    } else {
                                        $where = "";
                                    }
                                    
                                    if($_GET['filter'] && $_GET['filter'] != '') {
                                        $subscriberResults = dbQuery('SELECT * FROM subscribers WHERE  subscriber_id IN(SELECT DISTINCT(subscriber_id) FROM subscriber_to_lists) ' . $where .' ORDER BY subscriber_name ASC');
                                    } else {
                                        $subscriberResults = dbQuery('SELECT * FROM subscribers '.$where.' ORDER BY subscriber_name ASC');
                                    }
                                    
                                          ?>
                                          
                                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                            <tr>
                                              <td><strong>Sort by: </strong>
                                                <?=$menu?></td>
                                            </tr>
                                             <tr>
                                              <td><strong>By list: <select name="filter" class="textField-title" onchange="this.form.submit();">
                                              <option value="">All</option>
                                              <?php
											  $filterResults = dbQuery('SELECT * FROM subscriber_lists ORDER BY subscriber_lists_name ASC');
											  while($fInfo = dbFetchArray($filterResults)) {
												echo "<option value=\"".output($fInfo['subscriber_lists_call_name'])."\"";
													if($_GET['filter'] == $fInfo['subscriber_lists_call_name']) echo " selected";
												echo ">".$fInfo['subscriber_lists_name']."</optio>\n";  
											  }
											  ?>
                                              </select> </strong>
                                               </td>
                                            </tr>
                                          </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                              <tr>
                                                <td><a style="float:right;" class="button" href="<?=PAGE_COMMUNICATION?>?section=subscribers&action=add<? if($_GET['filter'] != '') echo "&filter=".$_GET['filter'];?>"><span class="add">New Subscriber</span></a><div class="clear"></div></td>
                                              </tr>
                                            </table>
                                            <?php 
                                            if(dbNumRows($subscriberResults)) { 
                                            ?>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                              <tr >
                                                <td class="tableRowHeader" align="left">Name</td>
                                                <td class="tableRowHeader" align="left">Phone</td>
                                                <td class="tableRowHeader" align="left">Mobile</td>
                                                <td class="tableRowHeader" align="left">Email</td>
                                                <td class="tableRowHeader" align="left">&nbsp;</td>
                                              </tr>
                                              <?
                                                $count=0;
                                                while($sInfo = dbFetchArray($subscriberResults)) {
                                                $row = $count % 2;
                                                ?>
                                              <tr>
                                                <td class="row<?=$row?>"><?=output($sInfo['subscriber_name'])?></td>
                                                <td class="row<?=$row?>"><?=output($sInfo['subscriber_phone'])?></td>
                                                <td class="row<?=$row?>"><?=output($sInfo['subscriber_mobile'])?></td>
                                                <td class="row<?=$row?>"><a href="mailto:<?=$sInfo['subscriber_email_address']?>"><?=$sInfo['subscriber_email_address']?></a></td>
                                                <td class="row<?=$row?>" align="right" nowrap="nowrap">
                                                <a class="table_edit_link" href="<?=PAGE_COMMUNICATION?>?action=edit&section=subscribers&id=<?=$sInfo['subscriber_id']?>">Edit</a>
                                                <a class="table_delete_link" href="<?=PAGE_COMMUNICATION?>?action=delete&section=subscribers&id=<?=$sInfo['subscriber_id']?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                                </td>
                                              </tr>
                                              <?
                                              $count++;
                                              }
                                              ?>
                                            </table>
                                         <div class="mb20"></div>
                                          <?
                                          } else {
                                           ?>
                                          
                                          <div style="padding:10px;">0 results returned</div>
                                          <?
                                          }
                                          ?></td>
                                      </tr>
                                    </table></form>
                                <?
					break;
					case 'add':
					case 'edit':
								if(isset($_GET['action'])){
									if($_GET['action'] == 'add') {
										$buttonText = "Add contact";
										$sInfo = array();
										$title = "Add contact";
										$newsletter_agree = true;
										$sms_agree = false;
										$action = 'add';
										if(isset($_GET['filter']) && $_GET['filter'] != '') {
											//get filter name
											$filterResults = dbQuery('SELECT * FROM subscriber_lists WHERE subscriber_lists_call_name = "'.$_GET['filter'].'"');
											$fInfo = dbFetchArray($filterResults);
											$title .= " to " . strtolower($fInfo['subscriber_lists_name']) . ' list';
											$contactLists[] = $fInfo['subscriber_lists_id'];	
										}
									} 
									if($_GET['action'] == 'edit') {
										$buttonText = "Save contact";
										$subscriberResults = dbQuery('SELECT * FROM subscribers WHERE subscriber_id = ' . $_GET['id']);
										$sInfo = dbFetchArray($subscriberResults);
										$title = "Edit contact";
										$newsletter_agree = $sInfo['subscriber_newsletter_agree'];
										$sms_agree = $sInfo['subscriber_sms_agree'];
										$action = 'save';
										//get contact lists this user is attached to.
										$contactListResults = dbQuery('SELECT * FROM subscriber_to_lists WHERE subscriber_id = ' . $_GET['id']);
										if(dbNumRows($contactListResults)) {
											while($cl = dbFetchArray($contactListResults)) {
												$contactLists[] = $cl['subscriber_lists_id'];	
											}
										} else {
											$contactLists = array(0);	
										}
									}
									?>
                                    <form action="<?=PAGE_COMMUNICATION?>" method="post" id="<?=$action?>subscriber">
                                    <input type="hidden" name="action" value="<?=$action?>subscriber" />
                                    <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                  <tr>
                                    <td valign="top" class="formBody">
                                    <div class="mb20"></div>
									<h2><?=$title?></h2>
								  	
                                    <table width="601" border="0" cellpadding="5" cellspacing="0">
                                      <tr>
                                        <td width="145"><strong>Name</strong></td>
                                        <td width="436"><input name="subscriber_name" type="text" class="textField-title" id="subscriber_name" value="<?=$sInfo['subscriber_name']?>" style="width:300px;" /></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Email</strong></td>
                                        <td><input name="subscriber_email_address" type="text" class="textField-title" id="subscriber_email_address" value="<?=$sInfo['subscriber_email_address']?>" style="width:300px;" /></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Phone #</strong></td>
                                        <td><input name="subscriber_phone" type="text" class="textField-title" id="subscriber_phone" value="<?=$sInfo['subscriber_phone']?>" style="width:300px;" /></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Mobile #</strong></td>
                                        <td><input name="subscriber_mobile" type="text" class="textField-title" id="subscriber_mobile" value="<?=$sInfo['subscriber_mobile']?>" style="width:300px;" /></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Addres</strong></td>
                                        <td><input name="subscriber_address" type="text" class="textField-title" id="subscriber_address" value="<?=$sInfo['subscriber_address']?>" style="width:300px;" /></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Address 2</strong></td>
                                        <td><input name="subscriber_address2" type="text" class="textField-title" id="subscriber_address2" value="<?=$sInfo['subscriber_address2']?>" style="width:300px;" /></td>
                                      </tr>
                                      <tr>
                                        <td><strong>City</strong></td>
                                        <td><input name="subscriber_city" type="text" class="textField-title" id="subscriber_city" value="<?=$sInfo['subscriber_city']?>" style="width:300px;" /></td>
                                      </tr>
                                      <tr>
                                        <td><strong>State</strong></td>
                                        <td>
                                        <?
										$stateResults = dbQuery('SELECT * FROM state ORDER BY state_abbr ASC');
										?>
                                        <select name="subscriber_state" id="subscriber_state" class="textField-title  style="width:300px;"">
                                        <?
										while($state = dbFetchArray($stateResults)) {
											echo "<option value=\"".$state['state_abbr']."\"";
											if($sInfo['subscriber_state'] == $state['state_abbr']) echo " selected";
											echo ">".$state['state_name']."</option>";	
										}
										?>
                                        </select></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Zipcode</strong></td>
                                        <td><input name="subscriber_zipcode" type="text" class="textField-title" id="subscriber_zipcode" value="<?=$sInfo['subscriber_zipcode']?>" style="width:300px;" /></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Notes</strong></td>
                                        <td>
                                        <textarea name="subscriber_notes" id="subscriber_notes" style="width:300px;" class="textField-title"><?=output($sInfo['subscriber_notes'])?></textarea>
                                        </td>
                                      </tr>
                                      <tr>
                                     	<td valign="top" class=""><strong>Attach contact to list</strong></td>
                                        <td>
                                                <div class="selection">
                                                <ul>
                                                    <?php
                                                    $listResults = dbQuery('SELECT * FROM subscriber_lists ORDER BY subscriber_lists_name ASC');
                                                    while($lInfo = dbFetchArray($listResults)) {
                                                        echo "<li>";
														echo "<label><input type=\"checkbox\" name=\"lists[]\" id=\"list_".$lInfo['subscriber_lists_id']."\"";
                                                        if(is_array($contactLists)) {
                                                            if( in_array( $lInfo['subscriber_lists_id'], $contactLists)) echo " checked";
                                                        }
                                                        echo " value=\"".$lInfo['subscriber_lists_id']."\">".output($lInfo['subscriber_lists_name'])."</label></li>\n";
                                                    }	
                                                    ?>
                                                </ul>
                                                </div>
                                        	</td>
                                        </tr>
                                        </table>
                                     
                                      </td>
                                      <td width="250" valign="top">
                                          <div style="margin:0px 10px 10px 10px;">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                            <tr>
                                              <td class="headerCell"><h2>Contact</h2></td>
                                            </tr>
                                            <tr>
                                              <td>
                                                  <label><input name="subscriber_newsletter_agree" type="checkbox" id="subscriber_newsletter_agree" value="1" <? if($newsletter_agree ){ echo " checked"; } ?> />Send newsletters to this user</label>
                                              </td>
                                           </tr>
                                            <tr>
                                              <td>Added on <?=date('m/d/Y g:i a')?></td>
                                            </tr>
                                            <tr>
                                              <td class="saveField"><input name="button4" type="submit" class="secondaySubmit" id="button4" value="<?=$buttonText?>" /></td>
                                            </tr>
                                          </table> 
                                          </div>
                                      </td>
                                  </tr></table>
                                  </form>
									<?
								}
								?>
                                <?php
					break;	
					}//end action switch
break;
case 'templates':

break;

}
?>


</div>
</body>
</html>