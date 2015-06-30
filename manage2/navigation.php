<div class="navigationContainer">
<table cellpadding="0" cellspacing="0" border="0" >
      <tr>
      	  <td class="navCell">&nbsp;</td>
          <td class="navCell"><a href="index.php"><div id="publish" class="<? if(this_php == 'index.php') echo "cpNavOn"; else echo "cpNavOff"; ?>">Dashboard</div></a></td>
		  <? if(user_has_permission('content_publisher') ) { ?>
             <td class="navCell"><a href="publish.php"><div id="publish" class="<? if(this_php == 'publish.php') echo "cpNavOn"; else echo "cpNavOff"; ?>">Publish</div></a></td>
          <? } ?>
          <? if(user_has_permission('content') ) { ?>
              <td class="navCell"><a href="manage.php"><div id="publish" class="<? if(this_php == 'manage.php') echo "cpNavOn"; else echo "cpNavOff"; ?>">Manage</div></a></td>
          <? } ?>
        <? if(user_has_permission('content') ) { ?>
          <td class="navCell"><a href="store.php"><div id="store" class="<? if(this_php == 'store.php') echo "cpNavOn"; else echo "cpNavOff"; ?>">Store</div></a></td>
        <? } ?>
			  <? if(user_has_permission('communication') ) { ?>
                  <td class="navCell"><a href="communication.php"><div id="publish" class="<? if(this_php == 'communication.php') echo "cpNavOn"; else echo "cpNavOff"; ?>">Email Manager</div></a></td>
			  <? } ?>
          
			  <? if(user_has_permission('admin')) { ?>
                  <td class="navCell"><a href="settings.php"><div id="publish" class="<? if(this_php == 'settings.php') echo "cpNavOn"; else echo "cpNavOff"; ?>">Settings</div></a></td>
			  <? } ?>
		  <? if(user_has_permission('admin')) { ?>
              <td class="navCell"><a href="users.php"><div id="publish" class="<? if(this_php == 'users.php') echo "cpNavOn"; else echo "cpNavOff"; ?>">Users</div></a></td>
          <? } ?>
          
          <? if(user_is_god()) { ?>
         <!--  <td class="navCell"><a href="admin.php"><div id="admin" class="<? if(this_php == 'admin.php') echo "cpNavOn"; else echo "cpNavOff"; ?>">Admin</div></a></td>-->
          <? } ?>
          <!--<td class="navCell"><a href="help.php"><div id="publish" class="<? if(this_php == 'help.php') echo "cpNavOn"; else echo "cpNavOff"; ?>">Help</div></a></td> -->
      	  <td class="navCell">&nbsp;</td>
      </tr>
 </table>
</div>