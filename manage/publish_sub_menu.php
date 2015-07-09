<?php
$subpageArray = array(
                'Pages' => 'webpage',
                'Photo Gallery' => 'gallery'
                );
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="bottom"><table cellpadding="0" cellspacing="0">
      <tr>
        <?php
            foreach($subpageArray as $name => $slug)
            {
                echo '<td valign="top" class="subMenuTabWidth"><a href="'.PAGE_MANAGE.'?section='.$slug.'" class="h1">';
                $selected = ($page == $slug) ? 'subMenuTabSelected' : 'subMenuTab';
                echo '<div class="' . $selected . '">'.$name.'</div></a></td>';
            }
        ?>
        <td valign="top" class="subMenuTabWidth"><a href="<?=PAGE_MANAGE?>?section=medialibrary" class="h1">
          <div class="<? if($page=='medialibrary') echo "subMenuTabSelected"; else echo "subMenuTab"; ?>">Media Library</div>
        </a></td>
      </tr>
    </table></td>
  </tr>
</table>