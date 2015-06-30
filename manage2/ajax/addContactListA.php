<?
include('../master.inc.php');
include('../application.php');
//this will capture and add the post data.
if(!$_SESSION['user_logged_in']){
	echo "You are not authorized to view this page";
	exit(); 
}


$row['subscriber_lists_name'] = input($_POST['subscriber_lists_name']);
$row['subscriber_lists_desc'] = input($_POST['subscriber_lists_desc']);


if($_POST['action'] == 'add') {
	$row['subscriber_lists_date_added'] = time();
	$row['subscriber_author'] = $_SESSION['userID'];
	dbPerform('subscriber_lists', $row, 'insert');
	echo "Added list successfully";
} else {
	dbPerform('subscriber_lists', $row, 'update', 'subscriber_lists_id = ' . $_POST['id']);
	echo "Updated list successfully";
}



?>