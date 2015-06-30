<?php





function getAuthor($user_id) {

	if($user_id == NULL)

		return "Anonymous";

	if($user_id == 0)

		return 'n/a';

	$results = dbQuery('SELECT user_first_name, user_last_name FROM user_information WHERE user_id = ' . $user_id);

	$row = dbFetchArray($results);

	

	return $row['user_first_name'].' '.$row['user_last_name'];

}



function client_has_module($module, $client) {

	$clientModulesResults = dbQuery('SELECT * FROM client_config WHERE client_id = ' . $client);

	$client = dbFetchArray($clientModulesResults);

	$availableModules = explode(',', $client['client_modules']);

	return (in_array($module, $availableModules));

}



function getLastLogin($userID) {

	

	$results = dbQuery('SELECT user_last_login FROM user WHERE user_id = ' . $userID);

	$row = dbFetchArray($results);

	

	if($row['user_last_login'] == 0)

		return "n/a";

	else

		return date('m/d/Y g:i a', $row['user_last_login']);

	

}



function getDateCreated($userID) {

	$results = dbQuery('SELECT user_created FROM user WHERE user_id = ' . $userID);

	$row = dbFetchArray($results);

	

	if($row['user_created'] == 0) {

		return "n/a";

	} else { 

		return date('m/d/Y', $row['user_created']);

	}

}





function user_has_page_permissions($page_content_id, $type="manager") {

	if(in_array('admin', $_SESSION['access_permissions'])) return true;
	if(in_array('god', $_SESSION['access_permissions'])) return true;
	$results = dbQuery('SELECT * FROM user_access_pages WHERE page_content_id = ' . $page_content_id . ' AND user_id = ' . $_SESSION['user_id'] . ' AND user_access_pages_type = "'.$type.'"');

	return(dbNumRows($results));

}



function user_is_god() {

	if(in_array('god', $_SESSION['access_permissions']) ) {

		return true;	

	} else  { return false; }

}





function user_has_permission($level) {
	if(in_array('admin', $_SESSION['access_permissions'])) return true;
	//check to see if the user has correct permission to view this area!
	if( in_array($level, $_SESSION['access_permissions'])) {
		return true;
	} else {
		return false;
	}
}



function user_has_top_permission($level) {

	//check to see if use has perms!

	

}





?>