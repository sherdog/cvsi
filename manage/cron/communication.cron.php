<?php
include('../master.inc.php');
include('../application.php');

//we are going to send out any communications that are in the queue.
$start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
$end = mktime(23,59, 59, date('m'), date('d'), date('Y'));
echo date("F j, Y, g:i a", $start)."<br>";
echo date("F j, Y, g:i a", $end)."<br><br>";
$queueResults = dbQuery('SELECT * FROM email_queue WHERE email_queue_release_date BETWEEN "'.$start.'" AND "'.$end.'" AND email_queue_status = "pending"');
if(dbNumRows($queueResults)) { //send out the newsletters!
//we want to include the class file

$count=0;
while($q=dbFetchArray($queueResults)) {
	
	
sendNewsletter($q['email_queue_id']);
$count++;		
}
echo "Sent " . $count . " newsletters at " . date("F j, Y, g:i a", time());
}

?>