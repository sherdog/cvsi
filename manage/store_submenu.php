<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="bottom"><table cellpadding="0" cellspacing="0">
      <tr>
      <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_STORE?>?section=orders&action=manage" class="h1">
          <div class="<? if($page=='orders' || $page =='') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>"> Orders </div>
        </a></td>
        <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_STORE_CATEGORIES?>&action=manage" class="h1">
          <div class="<? if($page=='categories') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>"> Categories </div>
        </a></td>
        <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_STORE_PRODUCTS?>&action=manage" class="h1">
          <div class="<? if($page=='products') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>"> Products </div>
        </a></td>
        <!--
        <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_STORE_OPTIONS?>&action=manage" class="h1">
          <div class="<? if($page=='options') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">Product Options</div>
        </a></td> -->
        <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_STORE?>?section=specials&action=manage" class="h1">
          <div class="<? if($page=='specials') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">Specials</div>
        </a></td>
        <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_STORE?>?section=shipping&action=editshipping" class="h1">
          <div class="<? if($page=='shipping') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">Shipping</div>
        </a></td>
        <!--
    <td valign="top" class="subMenuTabWidth">
    <a href="<?=PAGE_MANAGE?>?section=class" class="h1">
    <div class="<? if($page=='class') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">
       Classes
    </div>
    </a>
    </td>
    -->
      </tr>
    </table></td>
  </tr>
</table>
