<?
include('master.inc.php');
include('application.php');

/*
Array ( [action] => edit [id] => 11 [user_name] => sherdog@gmail.com [user_password] => mike [user_first_name] => Mike [user_last_name] => Sheridan [check_all_access] => ALL 
[access] => Array ( [0] => 6 [1] => 7 [2] => 8 [3] => 9 [4] => 10 [5] => 11 [6] => 12 [7] => 13 [8] => 14 [9] => 15 [10] => 16 [11] => 17 [12] => 18 [13] => 19 [14] => 4 [15] => 5 ) [
*/

if(!$_SESSION['user_logged_in'] || !isset($_SESSION['client'])){
	addError('Your session has timed out please login again');
	redirect(PAGE_LOGIN);
}
$trail = new breadcrumb();//start breadcrumb class
$trail->add("Dashboard", PAGE_DEFAULT);
$trail->add("Users", "");
$page = $_GET['section'];


if($_GET['action'] == 'delete') {
	if(!isset($_GET['id'])) { 
		addError('Whoops no user was selected');
		redirect(PAGE_USERS);
	} else {
		dbQuery('DELETE FROM user_information WHERE user_id = ' . $_GET['id']);
		dbQuery('DELETE FROM user WHERE user_id = ' . $_GET['id']);
		dbQuery('DELETE FROM user_access_pages WHERE user_id = ' . $_GET['id'] . ' AND user_access_pages_type = "manager"');
		dbQuery('DELETE FROM user_access_pages WHERE user_id = ' . $_GET['id'] . ' AND user_access_pages_type = "publisher"');
		addMessage('User was deleted successfully');
		redirect(PAGE_USERS);
	}

}

switch($_POST['action']) {
	case 'edit':
	case 'add':
		//save user!
		
			$user['user_name'] = $_POST['user_name'];
			$user['user_password'] = $_POST['user_password'];
		
			if($_POST['action'] == 'add') {
				
			}
			
			if($_POST['id'] != '') {
				dbPerform('user', $user, 'update', 'user_id = ' . $_POST['id']);
				$userID = $_POST['id'];
			} else {
				$user['user_created'] = time();
				dbPerform('user', $user, 'insert');
				$userID = dbInsertID();
			}
			
			$info['user_first_name'] = $_POST['user_first_name'];
			$info['user_last_name'] = $_POST['user_last_name'];
			
			if($_POST['id'] != '') {
				dbPerform('user_information', $info, 'update', 'user_id = ' . $userID);
				addMessage("Added user successfully");
			} else {
				$info['user_id'] = $userID;
				dbPerform('user_information', $info, 'insert');
				addMessage("Added user successfully");
			}
			
			dbQuery('DELETE FROM user_access_pages WHERE user_id = ' . $userID . ' AND user_access_pages_type = "manager"');
			dbQuery('DELETE FROM user_access_pages WHERE user_id = ' . $userID . ' AND user_access_pages_type = "publisher"');

			if(count($_POST['access'])) {
				
			$count=0;
			foreach($_POST['access'] as $key=>$val) {
				if($count){
					$access_values .= ",".$val	;	
				} else {
					$access_values .= $val	;		
				}
				
				if($val == "content" && count($_POST['content_manager']) ) {
					//update user_access_pages
					//first we delete them then add them again
					foreach($_POST['content_manager'] as $key=>$val) {
						$a['page_content_id'] = $val;
						$a['user_id'] = $userID;
						$a['user_access_pages_type'] = 'manager';
						dbPerform('user_access_pages', $a, 'insert');
						$a = array();
					}
				} 
				
				if($val == "content_publisher" && count($_POST['content_publisher']) ) {
					
					foreach($_POST['content_publisher'] as $key=>$val) {
						$access['page_content_id'] = $val;
						$access['user_id'] = $userID;
						$access['user_access_pages_type'] = 'publisher';
						dbPerform('user_access_pages', $access, 'insert');
						$access = array();
					}
				} 
			$count++;
			}
			
		}
		
		$useraccess['user_access'] = $access_values;
		dbPerform('user', $useraccess, 'update', 'user_id='.$userID);
		
		redirect(PAGE_USERS);
		
		
	break;
}

switch($_GET['section']){ 
case 'manage':
default:
$trail->add("Manage Users");
break;
case 'edit': 
$trail->add("Edit User");
break;
case 'add':
$trail->add("Add User");
break;
case 'setpermissions': 
$trail->add("Set User Permissions");
break;
case 'delete': 
$trail->add("Delete User");
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
<script language="javascript">

function checkAll()
{
	if( $("#user_access INPUT[@name^=access][type='checkbox']").is(':checked') ) {
		$("#user_access INPUT[@name^=access][type='checkbox']").attr('checked', false);
	} else {
		$("#user_access INPUT[@name^=access][type='checkbox']").attr('checked', true);
	}
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
<? include('users_submenu.php'); ?>
<?
switch($_GET['section']) {
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
                <td width="100"><a class="button" href="<?=PAGE_USERS?>?section=add"><span class="add">Add User</span></a></td>
              </tr>
            </table></td>
          </tr>
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr >
            <td width="1" class="tableRowHeader" ><input type="checkbox" name="checkAll" id="checkAll" /></td>
            <td class="tableRowHeader" align="left">Name</td>
            <td class="tableRowHeader" align="left">Created</td>
            <td class="tableRowHeader" align="left">Last Login</td>
            <td class="tableRowHeader" align="right">&nbsp;</td>
          </tr>
          <?
		  $count=0;
		  
		  $sql = "SELECT u.*, ui.* FROM user AS u, user_information AS ui WHERE ui.user_id = u.user_id ORDER BY u.user_created ASC";
		  $userResults = dbQuery($sql);
		  while($uInfo = dbFetchArray($userResults)) {
		  $row = $count % 2;
		  ?>
          <tr>
            <td class="row<?=$row?>"><input type="checkbox" name="checkbox" id="checkbox" /></td>
            <td class="row<?=$row?>"><?=getAuthor($uInfo['user_id'])?></td>
            <td class="row<?=$row?>"><?=getDateCreated($uInfo['user_id'])?></td>
            <td class="row<?=$row?>"><?=getLastLogin($uInfo['user_id'])?></td>
            <td class="row<?=$row?>" align="right"><a class="table_edit_link" href="<?=PAGE_USERS?>?section=edit&id=<?=$uInfo['user_id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_USERS?>?action=delete&id=<?=$uInfo['user_id']?>" onclick="return confirm('Are you sure you want to delete <?=$uInfo['user_first_name']?>?');">Delete</a></td>
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
case 'add':
case 'edit':
														 
														  if($_GET['section'] == 'add') {
														  
														  	$action = "Add Control Panel User";
															$uInfo = array();
															$uInfo['user_created'] = time();
															$buttonText = "Add User";
														  	
														  }
														  if($_GET['section'] == 'edit') {
															  $action = "Edit";
															  //get userinfo!
															  $userResults = dbQuery('SELECT u.*, ui.* FROM user AS u, user_information AS ui WHERE ui.user_id = u.user_id AND u.user_id = ' . $_GET['id']);
															  $uInfo = dbFetchArray($userResults);
															  $buttonText = "Save User";
															  if(strstr($uInfo['user_access'], ',')) {
															  	$values = explode(',', $uInfo['user_access']);
																
																foreach($values as $key=>$val) {
																	$accessArray[] = $val;
																}
																
															  } else {
																$accessArray[] = $uInfo['user_access'];
															  }
														  }
														  ?>
                                                          <form id="form2" name="form1" method="post" action="<?=PAGE_USERS?>">
                                                                    <input type="hidden" name="action" value="<?=$_GET['section']?>" />
                                                                    <input type="hidden" name="id" value="<?=$_GET['id']?>" />
														  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                                                            	<tr>
																	<td valign="top" class="formBody">
                                                            		<div class="mb20"></div> 
                                                            		
                                                              			<table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                			<tr>
                                                                  				<td  nowrap="nowrap">
                                                                                
                                                                                
                                                                                <h2><?=$action?> User</h2>
                                                                                
                                                                                
                                                                                <table width="100%" cellpadding="5" cellspacing="0">
                                                                                	<tr>
                                                                                    	<td colspan="2"><h3>Login Information</h3></td>
                                                                                    </tr>
                                                                                	<tr>
                                                                                	  <td width="10%" align="right" valign="top"><strong>Email Address</strong></td>
                                                                                	  <td width="90%"><input name="user_name" type="text" class="textField-title" id="user_name" value="<?=$uInfo['user_name']?>" /></td>
                                                                              	  </tr>
                                                                                	<tr>
                                                                                	  <td align="right"><strong>Password:</strong></td>
                                                                                	  <td><input name="user_password" type="text" class="textField-title" id="user_password" value="<?=$uInfo['user_password']?>" /></td>
                                                                              	  </tr>
                                                                                	<tr>
                                                                                	  <td colspan="2"><h3>User Information</h3></td>
                                                                               	  </tr>
                                                                                	<tr>
                                                                                	  <td align="right"><strong>First Name</strong></td>
                                                                                	  <td><input name="user_first_name" type="text" class="textField-title" id="user_first_name" value="<?=$uInfo['user_first_name']?>" /></td>
                                                                              	  </tr>
                                                                                	<tr>
                                                                                	  <td align="right"><strong>Last Name</strong></td>
                                                                                	  <td><input name="user_last_name" type="text" class="textField-title" id="user_last_name" value="<?=$uInfo['user_last_name']?>" /></td>
                                                                              	  </tr>
                                                                                	<tr>
                                                                                	  <td colspan="2"><h3>User Access</h3></td>
                                                                               	  </tr>
                                                                                	<tr>
                                                                                	  <td>&nbsp;</td>
                                                                                	  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                	    <tr>
                                                                                	      <td  valign="top">
                                                                                          <table width="100%">
                                                                                          <tr>
                                                                                          <td valign="top">
                                                                                         
                                                                                          
                                                                                          <div id="user_access">
																							  <?php
                                                                                              
                                                                                             function recurse_page_checkboxes($checkbox_id, $id=0, $level=0) {
																								 $level++;
																								  $sql = "SELECT * FROM page_content WHERE parent = ".$id." ORDER BY page_content_added ASC";
																								  $pageResults = dbQuery($sql);
																								  $count = 0;
																								  while($pInfo = dbFetchArray($pageResults)) {
																									  $padding = 18 * $level;
																									  if($level != 1) {
																										$style = "style=\"list-style:none; padding-left:".($padding+18)."px; background-repeat:no-repeat; background-position:".$padding."px 0px; background-image:url(images/directory_arrow.gif);\"";
																									  } else {
																										$class = "style=\"list-style:none;\"";  
																									  }
																										echo "<li ".$style."><label><input type=\"checkbox\" name=\"".$checkbox_id."[]\" id=\"".$checkbox_id."_".$count."\"  value=\"".$pInfo['page_content_id']."\"";
																										//check to see if this page is in the user_access_page table thing
																										if($checkbox_id == "content_manager") { $type="manager"; } 
																										if($checkbox_id == "content_publisher") { $type="publisher"; }
																										
																										if($_GET['section'] == 'edit') {
																											$accessCheck = dbQuery('SELECT page_content_id FROM user_access_pages WHERE user_id = ' . $_GET['id'] . ' AND page_content_id = ' . $pInfo['page_content_id'] . ' AND user_access_pages_type = "'.$type.'"');
																											if(dbNumRows($accessCheck)) echo " checked";
																										}
																										
																										echo "/>".output($pInfo['page_content_title'])."</label></li>\n";
																										recurse_page_checkboxes($checkbox_id, $pInfo['page_content_id'], $level);
																										$count++;
																									} 
																							 }
																							 if(user_is_god()) {
                                                                                              $accessResults = dbQuery('SELECT * FROM user_access ORDER BY user_access_id DESC');
																							 } else {
                                                                                              $accessResults = dbQuery('SELECT * FROM user_access WHERE display=1 ORDER BY user_access_id DESC');
																							 }
                                                                                              while($a=dbFetchArray($accessResults)) {
                                                                                                echo "<div id=\"".output($a['user_access_title'])."\" style=\"border:1px solid #99BBDF; background-color:#B3D0EF; padding:5px; margin:3px 0px;\">\n";
																									echo "<div class=\"checkBox\" style=\"float:left; width:20px; padding:10px;\">";
																										echo "<input type=\"checkbox\" name=\"access[]\" id=\"".$a['user_access_name']."\" value=\"".$a['user_access_name']."\"";
																											if($_GET['section'] == 'edit') { 
																												if(in_array($a['user_access_name'], $accessArray)) echo " checked"; 
																							  				}
																										echo "/>".output($s['user_access_title'])."";
                                                                                              		echo "</div>\n";
																							    
																									echo "<div class=\"checkBoxLabel\" style=\"float:left; \">\n";
																										echo "<label for=\"".$a['user_access_name']."\">";
																											echo "<div style=\"font-size:14px; font-weight:bold;\">".output($a['user_access_title'])."</div>\n";
																											echo "<div style=\"font-size:10px; font-weight:normal;\">".output($a['user_access_desc'])."</div>\n";
																										echo "</label>\n";
																											if($a['user_access_name'] == 'content') {
																												if($_GET['section'] == 'edit') { 
																												 	if(in_array($a['user_access_name'], $accessArray)) { $display="block"; } else { $display = "none"; } 
																												} else {
																													$display = "none";
																												}
																												//recurst page content in checkbox form iwth name of page_manage[]
																												echo "<div id=\"content_pages_manage\" style=\"display:".$display.";\" class=\"\">\n";
																												recurse_page_checkboxes("content_manager"); //pass the id of the checkbox you want to use..
																												echo "</div>\n";
																											} 
																											if($a['user_access_name'] == 'content_publisher') {
																												if($_GET['section'] == 'edit') { 
																													if(in_array($a['user_access_name'], $accessArray)) { $display="block"; } else { $display = "none"; } 
																												} else {
																													 $display="none";
																												}
																												echo "<div id=\"content_pages_publish\" style=\"display:".$display.";\" class=\"\">\n";
																												recurse_page_checkboxes("content_publisher"); //pass the id of the checkbox you want to use..
																												echo "</div>\n";
																											}
																									echo "</div>\n";
																								echo "<div class=\"clear\"></div>\n";
																								echo "</div>\n";
                                                                                                
                                                                                              
                                                                                                
                                                                                                
                                                                                              }
																							 
                                                                                              ?>
                                                                                          </div>
                                                                                          <script language="javascript">
                                                                                          $(document).ready(function() {
																							
																							$('#content').click(function() {
																								$('#content_pages_manage').slideToggle();
																							});
																							$('#content_publisher').click(function() { 
																								$('#content_pages_publish').slideToggle();
																							});					 
																							  
																						  });
                                                                                          </script>
                                                                                          </td>
                                                                                          </tr>
                                                                                          </table>
                                                                                          </td>
                                                                              	        </tr>
                                                                              	    </table></td>
                                                                              	  </tr>
                                                                                </table>
                                                                                
                                                                              </td>
                                                            				</tr>
                                                             			</table>
                                                        	 		
                                                      				</td>
                                                                    <td width="250" valign="top">
                                                                    
                                                                    <div style="margin:0px 10px 10px 10px;">
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="actionBox">
                                                                      <tr>
                                                                        <td class="headerCell"><h2>&nbsp;</h2></td>
                                                                      </tr>
                                                                      <tr>
                                                                        <td><strong>User added on:</strong><br />
                                                                        <?=date("F j, Y, g:i a", $uInfo['user_created'])?></td>
                                                                      </tr>
                                                                      <tr>
                                                                        <td class="saveField"><input name="button" type="submit" class="secondaySubmit" id="button" value="<?=$buttonText?>" /></td>
                                                                      </tr>
                                                                    </table>
                                                                    </div>
                                                                    
                                                                    </td>
                                                    			</tr>
                                                  			</table>
                                                            </form>
															<?
break;
case 'members':
															?>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                                              <tr>
                                                                <td valign="top" class="formBody"><br />
                                                                  <form>
                                                                    
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                      <tr >
                                                                        <td class="tableRowHeader"  align="left">Name</td>
                                                                        <td class="tableRowHeader"  align="left">Email/Username</td>
                                                                        <td class="tableRowHeader" width="50">&nbsp;</td>
                                                                      </tr>
                                                                      <?
                                                                     
                                                                      
                                                                      $sql = "SELECT * FROM members  ORDER BY members_first_name ASC";
                                                                      $memberResults = dbQuery($sql);
																	  if(dbNumRows($memberResults)) {
																		  
																		  $count=0;
																		  while($uInfo = dbFetchArray($userResults)) {
																			  $row = $count % 2;
																			  ?>
																			  <tr>
																				  <td class="row<?=$row?>"><?=ucfirst(strtolower($uInfo['members_first_name']))." ".ucfirst(strtolower($uInfo['members_last_name']))?></td>
																				  <td class="row<?=$row?>"><a href="mailto:<?=$uInfo['members_email_address']?>"><?=$uInfo['members_email_address']?></a></td>
            																	  <td class="row<?=$row?>"><a class="table_edit_link" href="<?=PAGE_USERS?>?section=members&action=edit&id=<?=$uInfo['user_id']?>">Edit</a> <a class="table_delete_link" href="<?=PAGE_USERS?>?section=members&action=delete&id=<?=$uInfo['user_id']?>" onclick="return confirm('Are you sure you want to delete <?=$uInfo['user_first_name']?>?');">Delete</a></td>
																			  </tr>
																			  <?
																			  $count++;
																		  }
																		  
																	  } else {
																		  ?>
                                                                          <tr>
                                                                          	<td>No results returned</td>
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



}
?>


</div>
</body>
</html>
