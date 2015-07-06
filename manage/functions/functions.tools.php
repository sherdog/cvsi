<?php

function get_calcutors_checkboxes() {
	//get array of available calculators
	$calcs = get_available_calculators();
	
	foreach($calcs as $key=>$val) {
		
		
	}
}

function get_available_calculators() {
	$results = dbQuery('SELECT * FROM tools_calculators');
	while($r=dbFetchArray($results)){
		$c[] = array('id'=>$r['tools_calculators_id'], 'name'=>$r['tools_calculators_name']);	
		echo "<li><label><input type=\"checkbox\" name=\"calculators[]\" value=\"".$val['id']."\">".$val['name']."</option></li>\n";
	}
	return $c;
}


?>