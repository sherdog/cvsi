<?php

include('master.inc.php');
include('application.php');

//what section

switch($_GET['load']){

	case'header':

		//get html for header =)
		$headeResults = dbQuery('SELECT email_templates_header FROM email_templates WHERE email_templates_id = ' . $_GET['id']);
		if(!dbNumRows($headeResults)) { echo "no Results!"; }
		$h=dbFetchArray($headeResults);
		echo html_entity_decode(output($h['email_templates_header']));
	break;

	case 'footer':
		//get html for header =)
		$headeResults = dbQuery('SELECT email_templates_footer FROM email_templates WHERE email_templates_id = ' . $_GET['id']);
		$h=dbFetchArray($headeResults);
		echo html_entity_decode(output($h['email_templates_footer']));
	break;

	case 'body':
		//get html for header =)
		$headeResults = dbQuery('SELECT email_templates_body FROM email_templates WHERE email_templates_id = ' . $_GET['id']);
		$h=dbFetchArray($headeResults);
		echo "<textarea name=\"emailTemplateBody\" id=\"mceEditor\">".output($h['email_templates_body ']),"</textarea>\n";
	break;

}
?>