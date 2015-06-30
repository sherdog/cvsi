<?
include('master.inc.php');
include('application.php');
if(!$_SESSION['user_logged_in'] || !isset($_SESSION['client'])){
	addError('Your session has timed out please login again');
	redirect(PAGE_LOGIN);
}
$trail = new breadcrumb();//start breadcrumb class
$trail->add("Dashboard", PAGE_DEFAULT);
$trail->add("Settings", "");
$page = $_GET['section'];

if($_POST['action'] == 'emailSettings') {
	
	$row['settings_value'] = $_POST['contact_emails_to'];
	dbPerform('settings', $row, 'update', 'settings_key="contact_email"');
	
	
	
	addMessage("Your setting were saved");	
	redirect('settings.php');
}
if($_POST['action'] == 'updatestore') {
	//save store settings
	$row['store_settings_value'] = $_POST['order_email_to'];
	dbPerform('store_settings', $row, 'update', 'store_settings_key="order_email_to"');
	
	
	
	if(isset($_POST['store_enable_checkout'])) {
		$row['store_settings_value'] = 1;
		dbPerform('store_settings', $row, 'update', 'store_settings_key="store_enable_checkout"');
	} else {
		$row['store_settings_value'] = 0;
		dbPerform('store_settings', $row, 'update', 'store_settings_key="store_enable_checkout"');
	}
	
	if(isset($_POST['store_require_user_account'])) {
		$row['store_settings_value'] = 1;
		dbPerform('store_settings', $row, 'update', 'store_settings_key="store_require_user_account"');
	} else {
		$row['store_settings_value'] = 0;
		dbPerform('store_settings', $row, 'update', 'store_settings_key="store_require_user_account"');
	}
	
	$row['store_settings_value'] = $_POST['store_tax_value'];
	dbPerform('store_settings', $row, 'update', 'store_settings_key="store_tax_value"');
	
	addMessage('Saved store settings successfully');
	redirect(PAGE_SETTINGS.'?section=store');
	
}
switch($_GET['section']){ 
case 'queue':
default:
$trail->add("General Settings");
break;
case 'compose_email': 
$trail->add("Compose Email");
break;
case 'compose_sms':
$trail->add("Send SMS");
break;
case 'subscribers': 
$trail->add("Subscribers");
break;
case 'templates': 
$trail->add("Email Templates");
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
  
});
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
<? include('settings_submenu.php'); ?>
<?
switch($_GET['section']) {
case 'general':
default:
															
															$buttonText = "Save Changes";
															?>
                                                            <form id="email_settings" name="email_settings" method="post" action="settings.php">
                                                            <input type="hidden" name="section" value="<?=$_GET['section']?>" />
                                                            <input type="hidden" name="action" value="emailSettings" />
															<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                                                            	<tr>
																	<td valign="top" class="formBody">
                                                            		<div class="mb20"></div> 
                                                            		
                                                              			<table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                			<tr>
                                                                  				<td  nowrap="nowrap">
                                                                                <h2>General Settings</h2>
                                                                          <table width="100%" cellpadding="5" cellspacing="0">
                                                                                	<tr>
                                                                                    	<td colspan="2"><h3>Email Settings</h3></td>
                                                                                    </tr>
                                                                                	<tr>
                                                                                	  <td width="17%" valign="top"><strong>Send contact form emails to:</strong></td>
                                                                                	  <td width="83%"><textarea name="contact_emails_to" id="contact_emails_to" cols="45" rows="5"><?=getGeneralSetting('contact_email')?></textarea>
                                                                                      <br />
																					  Seperate multiple emails  by comma</td>
                                                                              	  </tr>
                                                                                	<tr>
                                                                                	  <td>&nbsp;</td>
                                                                                	  <td>&nbsp;</td>
                                                                              	  </tr>
                                                                                </table>
                                                                                
                                                                              </td>
                                                            				</tr>
                                                             			</table>
                                                        	 		
                                                      				</td>
                                                                    <td width="250" valign="top"><div style="margin:0px 10px 10px 10px;">
                                                                      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                                                          <tr>
                                                                            <td class="headerCell"><h2>Save Settings</h2></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td>Please make sure everything is correct</td>
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
                                                            </form>
															<?
break;
case 'store':
															$buttonText = "Save Settings";
															
															
															?>
                                                            <form id="form2" name="form1" method="post" action="<?=PAGE_SETTINGS?>">
                                                           <input type="hidden" name="action" value="updatestore" />
															<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                                                            	<tr>
																	<td valign="top" class="formBody">
                                                            		<div class="mb20"></div> 
                                                            		
                                                              			<table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                			<tr>
                                                                  				<td  nowrap="nowrap">
                                                                                <h2>Store Settings</h2>
                                                                              <table width="100%" cellpadding="5" cellspacing="0">
                                                                                	<tr>
                                                                                    	<td colspan="2"><h3>Store Order Settings</h3></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                    	<td colspan="2"><h3>Email Settings</h3></td>
                                                                                    </tr>
                                                                                	<tr>
                                                                                	  <td width="18%" align="right" valign="top"><strong>Send order emails to:</strong></td>
                                                                                	  <td width="82%">
                                                                               	        <textarea name="order_email_to" id="order_email_to" cols="45" rows="5"><?=getSetting("order_email_to")?></textarea>
                                                                           	          <br />
                                                                           	          Seperate multiple emails  by comma</td>
                                                                              	  </tr>
                                                                                	<tr>
                                                                                	  <td>&nbsp;</td>
                                                                                	  <td>&nbsp;</td>
                                                                              	  </tr>
                                                                                	<tr>
                                                                                	  <td colspan="2"><h3>General Settings</h3></td>
                                                                               	  </tr>
                                                                                	<tr>
                                                                                	  <td align="right"><strong>Enable Checkout</strong></td>
                                                                                	  <td><input name="store_enable_checkout" type="checkbox" id="store_enable_checkout" value="1" <? if(getSetting("store_enable_checkout")) echo " checked"; ?> /></td>
                                                                              	  </tr>
                                                                                	<tr>
                                                                                	  <td align="right"><strong>Require User Account</strong></td>
                                                                                	  <td><input type="checkbox" name="store_require_user_account" id="store_require_user_account" value="1" <? if(getSetting("store_require_user_account")) echo " checked"; ?> /></td>
                                                                              	  </tr>
                                                                                	<tr>
                                                                                	  <td align="right"><strong>Tax Rate</strong></td>
                                                                                	  <td class="pageTitleSub">.
                                                                                	    <input name="store_tax_value" type="text" class="textField-title" id="store_tax_value" style="width:50px;" value="<?=getSetting('store_tax_value')?>" /></td>
                                                                              	  </tr>
                                                                                	<tr>
                                                                                	  <td>&nbsp;</td>
                                                                                	  <td>&nbsp;</td>
                                                                              	  </tr>
                                                                                </table>
                                                                                
                                                                              </td>
                                                            				</tr>
                                                             			</table>
                                                        	 		
                                                      				</td>
                                                                    <td width="250" valign="top"><div style="margin:0px 10px 10px 10px;">
                                                                      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                                                        <tr>
                                                                          <td class="headerCell"><h2>Save Settings</h2></td>
                                                                        </tr>
                                                                        <tr>
                                                                          <td>Please make sure everything is correct</td>
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
                                                            
                                                            </form>
															<?
break;
}
?>
</div>
</body>
</html>
