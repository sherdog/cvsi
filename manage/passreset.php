<?
include 'application.php';


	if (isset($_POST["loginsent"])) {

		//check to see if user exists and set cookie and sessions!
		$userResults = dbQuery('SELECT * FROM user WHERE user_name = "'.$_POST['uname'].'" AND user_password="'.$_POST['pword'].'"');
		
		if(dbNumRows($userResults)) {
			//user exists!
			$user = dbFetchArray($userResults);
			$infoResults = dbQuery('SELECT * FROM user_information WHERE user_id = ' . $user['user_id']);
			$info = dbFetchArray($infoResults);
			
			$_SESSION['client_id'] = $user['client_id'];
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['user_name'] = $info['user_first_name'].' '.$info['user_last_name'];
			$_SESSION['user_logged_in'] = true;
			$_SESSION['mc_rootpath'] = ROOT_PATH.'files/';
			
			//update last login!
			$row['user_last_login'] = time();
			dbPerform('user', $row, 'update', 'user_id = ' . $user['user_id']);
			
			redirect(PAGE_DEFAULT); //send user to index.php (unless they have something bookmarked =)
			
			
		} else {
			addError("Invalid username/password please try again.");
			redirect('login.php');
		}
		
	}
?>
<html>
<head>
<title>Intelligence Center :: Login</title>
<link rel="stylesheet" href="css/styles.css" />

<style>
body { 
	background-color:#F5F5F5;
	margin:0px; 
	font-family:Verdana, Arial, Helvetica, sans-serif; 
	font-size:11px; 
	color:#333;
	
}

.login { background-color:#FFF; margin:10px auto; }

a { font-family:Verdana, Arial, Helvetica, sans-serif; text-decoration:none; font-size:10px; color:#333; }
a:hover { text-decoration:underline; color:#990000; }
#loginTable .alertBox {
background: url(images/exclamation.png) no-repeat scroll 15px 50%;
background-color:#FFE6E6;
border-bottom:0px solid #FFD324;
border-top:0px solid #FFD324;
color:#333333;
margin:0px 20px;
padding:10px 20px 10px 45px;
text-align:left;
}
#loginTable .infoBox {
background: #F8FAFC url(images/information.png) no-repeat scroll 15px 50%;
border-bottom:0px solid #FFD324;
border-top:0px solid #FFD324;
color:#333333;
margin:0px 20px;
padding:10px 20px 10px 45px;
text-align:left;
}

.login input {
	font-size:14px;
	font-weight:bold;
	font-family:Tahoma;
	padding:4px;
}
</style>
</head>
<body>
<div style="height:130px"></div>
<table width="400" cellpadding="0" cellspacing="0" align="center">
	<tr>
    	<td valign="top">
            
            <div style="width:400px; margin:0px auto;">
            <? 
            printMessage();
            printError();
            ?>
            </div>
            <form method="post" action="login.php" id="loginform" name="loginform">
            <input type="hidden" name="loginsent" value="true">
             <div class="dashboardBox login" id="loginBox" style="width:400px; ">
                <h3 style="font-size:19px; padding-left:16px; background-color:#FFF; background-image:none;">Shh. It Happens</h3>
                
                <div class="dashboardBoxInside">
                <div style="font-size:13px;">Your password will be emailed to the email address associated with your account.</div>
                    <table width="100%" cellpadding="5" cellspacing="0">
                        <tr>
                            <td nowrap="nowrap" class="onhold">
                            <div style="font-size:13px;">Email Address</div>
                            <input type="text" tabindex="10"  value="" style="width:360px;" class="textField-title" id="uname" name="uname"/></td>
                        </tr>
                        
                    <tr>
                          <td align="right"><span class="submit" style="text-align:right">
                            <a href="passreset.php"></a>
                            <input type="submit" tabindex="100" value="Reset &raquo;" id="submit" name="submit"/>
                    </span></td>
                        </tr>
                    </table>
                
                </div>
                </div>
                </form>
  </td>
    </tr>
</table>
</body>
</html>