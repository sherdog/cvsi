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
			
			$_SESSION['client'] = $user['client_id'];
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['user_name'] = $info['user_first_name'].' '.$info['user_last_name'];
			$_SESSION['user_logged_in'] = true;
			$_SESSION['mc_rootpath'] = MEDIA_LIBRARY_PATH;
			
			//get users access and set it to a session it will be an array of areas to which this user has access to.
			$accessValues = $user['user_access'];
			
			if(strstr($accessValues,  ",")) {
				$values = explode(',', $accessValues);
				foreach($values as $key=>$val) {
					$accessArray[] = $val;	
				}
			} else {
				$accessArray[] = $user['user_access'];
			}
			$_SESSION['access_permissions'] = $accessArray;
			
			//update last login!
			$row['user_last_login'] = time();
			dbPerform('user', $row, 'update', 'user_id = ' . $user['user_id']);
			
			redirect('manage.php'); //send user to index.php (unless they have something bookmarked =)
			
			
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
	padding:0px;
	font-family:Verdana, Arial, Helvetica, sans-serif; 
	font-size:11px; 
	color:#333;
	
}
.login { background-color:#FFF; margin:10px auto; }

a { font-family:Verdana, Arial, Helvetica, sans-serif; text-decoration:none; font-size:10px; color:#333; }
a:hover { text-decoration:underline; color:#990000; }

.infoBox {
	background: #F8FAFC url(images/information.png) no-repeat scroll 15px 50%;
	border-bottom:0px solid #FFD324;
	border-top:0px solid #FFD324;
	color:#333333;
	margin:0px;
		font-size:12px; 

	padding:10px 20px 10px 45px;
	text-align:left;
	-webkit-border-bottom-right-radius: 10px;
	-webkit-border-bottom-left-radius: 10px;
	-webkit-border-top-right-radius: 10px;
	-webkit-border-top-left-radius: 10px;

	-khtml-border-radius-bottomright: 10px;
	-khtml-border-radius-bottomleft: 10px;
	-khtml-border-radius-topright: 10px;
	-khtml-border-radius-topleft: 10px;

	-moz-border-radius-bottomright: 10px;
	-moz-border-radius-bottomleft: 10px;
	-moz-border-radius-topright: 10px;
	-moz-border-radius-topleft: 10px;

	border-bottom-right-radius: 10px;
	border-bottom-left-radius: 10px;
	border-top-right-radius: 10px;
	border-top-left-radius: 10px;

}

.login input {
	font-size:14px;
	font-weight:bold;
	font-family:Tahoma;
	padding:4px;
}
.alertBox {
	background:#FFE6E6 url(images/exclamation.png) no-repeat scroll 15px 50%;
	border-bottom:0 solid #FFD324;
	border-top:0 solid #FFD324;
	color:#333333;
	margin:0px;
	padding:10px 20px 10px 45px;
	text-align:left;
	font-size:12px; 
	
	-webkit-border-bottom-right-radius: 10px;
	-webkit-border-bottom-left-radius: 10px;
	-webkit-border-top-right-radius: 10px;
	-webkit-border-top-left-radius: 10px;

	-khtml-border-radius-bottomright: 10px;
	-khtml-border-radius-bottomleft: 10px;
	-khtml-border-radius-topright: 10px;
	-khtml-border-radius-topleft: 10px;

	-moz-border-radius-bottomright: 10px;
	-moz-border-radius-bottomleft: 10px;
	-moz-border-radius-topright: 10px;
	-moz-border-radius-topleft: 10px;

	border-bottom-right-radius: 10px;
	border-bottom-left-radius: 10px;
	border-top-right-radius: 10px;
	border-top-left-radius: 10px;
}

</style>
</head>
<body>
<div style="height:130px"></div>
<table width="400" cellpadding="0" cellspacing="0" align="center">
	<tr>
    	<td valign="top">
        
<div style="width:420px; margin:0px auto;">
<? 
printMessage();
printError();
?>
</div>
 <div class="dashboardBox login" style="width:400px;">
    <h3 style="font-size:19px; padding-left:16px; background-color:#FFF; background-image:none;">Login</h3>
    <div class="dashboardBoxInside">
		<form method="post" action="login.php" id="loginform" name="loginform" style="margin:0px; padding:0px;">
        <input type="hidden" name="loginsent" value="true">
        <table width="100%" cellpadding="5" cellspacing="0">
            <tr>
            	<td nowrap="nowrap" class="onhold">
                <div style="font-size:13px;">Email Address</div>
                <input type="text" tabindex="10"  value="" style="width:100%;" class="textField-title" id="uname" name="uname"/></td>
            </tr>
            <tr>
              <td nowrap="nowrap" class="onhold">
              <div style="font-size:13px;">Password</div>        
              <input type="password" tabindex="20" style="width:100%;" value="" class="textField-title" id="pword" name="pword"/></td>
            </tr>
            <tr>
              <td align="right"><span class="submit" style="text-align:right">
                <a href="passreset.php">Forget your password?</a>
                <input type="submit" tabindex="100" value="Login &raquo;" id="submit" name="submit"/>
              </span></td>
            </tr>
        </table></form>
    </div>
 </div>

        
        </td>
    </tr>
</table></body>
</html>