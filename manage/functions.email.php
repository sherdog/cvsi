<?php

function checkEmail($email = "") {
  if (ereg("[[:alnum:]]+@[[:alnum:]]+\.[[:alnum:]]+", $email)) {
    return true;
  } else {
    return false;
  }
}
function upload_contacts($file) {
$file_handle = fopen($file, "r");
	$count = 0;
	while (!feof($file_handle) ) {
	
		$line_of_text = fgetcsv($file_handle, 1024);
		$row['subscriber_email_address'] = $line_of_text[0];
		$row['subscriber_newsletter_agree'] = 1;
		$row['subscriber_date_added'] = time();
		$row['subscriber_name'] = $line_of_text[1];
		
		if(checkEmail($line_of_text[0])) {
			dbPerform('subscribers', $row, 'insert');
		}
	$count++;
	}
	
	fclose($file_handle);
	
	return $count;
}
function sendNewsletter($queueID=0){
	
	if($queueID == 0) 
		return false; //wtf? not queueID was defined musta been a booboo!
		
	
	//k we need to umm grab the info from the queue
	$queueResults = dbQuery('SELECT e.*, t.* FROM email_queue AS e, email_templates AS t WHERE t.email_templates_id = e.email_template_id AND e.email_queue_id = ' . $queueID);
	$q = dbFetchArray($queueResults);
	
	
	//we have everything we need i believe!
	//lets send out the newsletter, then remove it from the queue and add to the sent table
	//the sent table will allow the admin to resend at anytime
	
	//include the class phpmailer()
	include_once('classes/class.phpmailer.php');
	//now we need to get stuff!
	$content = output($q['email_templates_header']);
	$content .= output($q['email_queue_email_text']);
	$content .= output($q['email_templates_footer']);
	
	$q['content'] = $content;
	$recipients = explode(',', $q['email_queue_recipients']);	
	
	foreach($recipients  as $key=>$val) {
			$mail = new PHPMailer();
			$mail->From     = output($q['email_queue_from']);
			$mail->FromName = output($q['email_queue_from']);
			$mail->isMail 	= true;
			$mail->Body    	= output($content);
			$mail->AddAddress($val);
			$mail->Subject = output($q['email_queue_subject']);
			
			//check to see if there is an attachment
			if($q['email_queue_attachment'] != ""  ) {
				$mail->AddAttachment(UPLOAD_DIR_NEWSLETTER.$q['email_queue_attachment'], $q['email_queue_attachment']);
			}
			
			$mail->ContentType = 'text/html';
			$mail->Send();
			$mail->ClearAddresses();
	}
	
	$row['client_id'] = 1;
	$row['email_queue_date_sent'] = time();
	$row['email_queue_subject'] = $q['email_queue_subject'];
	$row['email_queue_content'] = $q['content'];
	$row['email_templates_id'] = $q['email_template_id'];
	$row['email_recipients'] = $q['email_queue_recipients'];
	$row['email_display_home'] = $q['email_display_home'];

	dbPerform('email_queue_sent', $row, 'insert');
	dbQuery('DELETE FROM email_queue WHERE email_queue_id = ' . $q['email_queue_id']);
	
	return true;

}

function pauseMessage($queueID) {
	//set status from pending to on hold!
	$row['email_queue_status'] = 'onhold';
	dbPerform('email_queue', $row, 'update', 'email_queue_id='.$queueID);
}

function unpauseMessage($queueID) {
	//set status from pending to on hold!
	$row['email_queue_status'] = 'pending';
	dbPerform('email_queue', $row, 'update', 'email_queue_id='.$queueID);
}

function deleteFromQueue($queueID, $status='pending') {
	//delete! DELETE DELETE! =)	
	if($status == 'pending') {
		dbQuery('DELETE FROM email_queue WHERE email_queue_id = ' .$queueID);	
	} else {
		dbQuery('DELETE FROM email_queue_sent WHERE email_queue_sent = ' .$queueID);
	}
}

?>