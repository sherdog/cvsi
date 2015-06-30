<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="bottom"><table cellpadding="0" cellspacing="0">
      <tr>
       
      	<td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_COMMUNICATION?>?section=queue" class="h1">
          <div class="<? if($page=='queue' || $page =='') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">Queue</div>
        </a></td>
        <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_COMMUNICATION?>?section=compose_email" class="h1">
          <div class="<? if($page=='compose_email') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">Compose Email</div>
        </a></td>
         <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_COMMUNICATION?>?section=lists" class="h1">
          <div class="<? if($page=='lists') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">Manage Lists</div>
        </a></td>
         <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_COMMUNICATION?>?section=subscribers" class="h1">
          <div class="<? if($page=='subscribers') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">Contacts</div>
        </a></td>
        <!--
        <? if(user_has_permission(18)) { ?>
        <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_COMMUNICATION?>?section=compose_sms" class="h1">
          <div class="<? if($page=='compose_sms') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>"> Send SMS</div>
        </a></td>
       <? } ?>
        -->
       <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_COMMUNICATION?>?section=import" class="h1">
          <div class="<? if($page=='import') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">Import Subscribers</div>
        </a></td>
         <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_COMMUNICATION?>?section=templates" class="h1">
          <div class="<? if($page=='templates') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">Templates</div>
        </a></td>
      </tr>
    </table></td><td width="100"><?
    //let's display an add button depending on area
	if($_GET['section'] == 'templates') {
	//show add button for event =)
		echo "<a class=\"button\" href=\"".PAGE_COMMUNICATION."?section=templates&action=new\"><span class=\"add\">New Template</span></a>";
	}
	
	?></td>
  </tr>
</table>
