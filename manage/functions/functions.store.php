<?php

function recurseCategories($parent=0, $count=1) {
	$sql = 'SELECT * FROM store_categories WHERE categories_parent = ' . $parent . ' ORDER BY categories_title ASC';
	
	for( $i=0; $i<=$count; $i++ )
	{ 
		$padding = 10 * $count;
	}
	
	$results = dbQuery($sql);
	while($cInfo = dbFetchArray($results)) {
		
		echo "<tr>\n";
		echo "<td class=\"row".$row."\"><a href=\"".PAGE_STORE."?section=products&action=manage&c=".$cInfo['categories_id']."\" title=\"".output($cInfo['categories_title'])."Products\"><img src=\"images/icons/folder_closed_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a></td>\n";
		echo "<td class=\"row".$row." pageTitleSub \"><img src=\"images/filler.gif\" width=\"".$padding."\" height=\"5\"> <a href=\"".PAGE_STORE."?section=products&action=manage&c=".$cInfo['categories_id']."\" title=\"".output($cInfo['categories_title'])." Products\">".output($cInfo['categories_title'])."</a></td>\n";
		echo "<td class=\"row".$row."\">".date('m/d/Y', $cInfo['categories_date_added'])."</td>\n";
		echo "<td width=\"50\" class=\"row".$row."\"><a href=\"".PAGE_STORE_CATEGORIES."&action=editcategory&id=".$cInfo['categories_id']."\" title=\"Edit ".output($cInfo['categories_title'])."\" ><img src=\"images/icons/edit_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a> | <a href=\"".PAGE_STORE."?action=deletecategory&id=".$cInfo['categories_id']."\" onclick=\"return confirm('Are you sure you want to delete ".$cInfo['categories_title']."?');\"><img src=\"images/icons/delete_16x16.gif\" width=\"16\" height=\"16\" border=\"0\" /></a></td>\n";
		echo "</tr>\n";
	
	recurseCategories($cInfo['categories_id'], $count+1);
	
	
	}
	
	
}
function outputPrice($price, $strip=false) {
	//just return the price of the product!
	$theprice = "";
	if(!$strip) $theprice .= "$";
	$theprice .= number_format($price, 2, '.', ',');
	return $theprice;
}

function get_pending_count() {
	$pendingOrders = dbQuery('SELECT orders_id FROM store_orders WHERE orders_status = "pending"');
	return dbNumRows($pendingOrders);
}

function get_onhold_count() {
	$onholdOrders = dbQuery('SELECT orders_id FROM store_orders WHERE orders_status = "onhold"');
	return dbNumRows($onholdOrders);
		
}
function get_complete_count() {
	$completeOrder = dbQuery('SELECT orders_id FROM store_orders WHERE orders_status = "complete"');
	return dbNumRows($completeOrder);
}
?>