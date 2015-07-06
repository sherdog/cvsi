<?php
include('master.inc.php');
include('application.php');
$id = $_GET['id'];

$results = dbQuery('SELECT email_templates_code FROM email_templates WHERE email_templates_id = ' . $id);
$row = dbFetchArray($results);
echo  output($row['email_templates_code']);

?>