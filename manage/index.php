<?
include('application.php');

if(!$_SESSION['user_logged_in'] || !isset($_SESSION['client'])){
	addError('Your session has timed out please login again');
	redirect('login.php');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=COMPANY_NAME?> :: Central Intelligence Center</title>
<script language="javascript" src="nav.js"></script>
<script language="javascript" src="jscripts/jquery.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>

<script language="javascript">
$(document).ready(function(){
  $('.cpNavOff').mouseover( function(){ 
		$(this).removeClass().addClass("navHover");
  }).mouseout( function() {
		$(this).removeClass().addClass("cpNavOff");
  });


});
</script>
<link rel="stylesheet" href="css/styles.css" />
</head>
<div id="topHeader"><? include('header.php'); ?></div>
<div id="header"></div>

<? include('navigation.php'); ?>
<div class="submenu-vertical contentStart"></div>
<?
//error bar here
?>
<div class="contentStart">
<div class="pageTitle">Dashboard</div>

<div class="columnLeft">
  <div class="dashboardBox" id="storefront-quick">
    <h3 class="handle"><span>Communication</span></h3>
    <div class="dashboardBoxInside">
    	<div class="table">
		<table width="100%" cellpadding="5" cellspacing="0">
        	<tr>
            	<td colspan="2" class="tableTitle">Pending Newsletters</td>
            </tr>
            <tr>
            	<td class="tableHeader">Subject</td>
                <td align="right" class="tableHeader">Release Date</td>
            </tr>
			<?
			$pendingNewsletters = dbQuery('SELECT email_queue_release_date, email_queue_subject FROM email_queue WHERE email_queue_status = "pending" ORDER BY email_queue_release_date ASC');
			if(dbNumRows($pendingNewsletters)) {
				while($pnInfo = dbFetchArray($pendingNewsletters)) {
					?>
					<tr>
					  <td width="25%" nowrap="nowrap"><?=output($pnInfo['email_queue_subject'])?></td>
					  <td align="right"><?=date('m/d/Y', $pnInfo['email_queue_release_date'])?></td>
					</tr>
					<?						 
				}
			} else {
			  ?>
			  <tr>
				  <td width="25%"  colspan="2" nowrap="nowrap"><strong>There are no Pending Newsletters</strong></td>
			  </tr>
			  <?
			}
			?>
            
            
        </table>
    </div>
    </div>
    </div>
</div>
<!--
<div class="columnRight">

	<div class="dashboardBox" id="storefront-quick">
    <h3 class="handle"><span>Content</span></h3>
    <div class="dashboardBoxInside">
    <div class="table">
      <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td valign="top"><strong><?=get_article_count() ?> Article(s)</strong></td>
          <td valign="top">
		  	<?=get_article_count('active')?> Published<br />
            <?=get_article_count('pending')?> Pending
            </td>
        </tr>
        <tr>
          <td valign="top"><strong><?=get_news_count() ?> News Item(s)</strong></td>
          <td valign="top">
			 <?=get_news_count('active')?> Published<br />
             <?=get_news_count('pending')?> Pending
          </td>
        </tr>
        <tr>
          <td valign="top"><strong><?=get_event_count() ?> Event(s)</strong></td>
          <td valign="top">
          		<?=get_event_count('active')?> Published<br />
             	<?=get_event_count('pending')?> Pending
          </td>
        </tr>
      </table>
      </div>
    </div>
    </div>
</div>
-->
</div>
</body>
</html>
