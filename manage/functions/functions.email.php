<?php

function uploadAttachment($formfile,$filename="") {

	if ($filename=="")
		$filename = $formfile['name'];
			
	move_uploaded_file($formfile['tmp_name'], UPLOAD_DIR_NEWSLETTER.$filename);

}
function checkEmail($email = "") {
  if (ereg("[[:alnum:]]+@[[:alnum:]]+\.[[:alnum:]]+", $email)) {
    return true;
  } else {
    return false;
  }
}
function buildHTMLFilename($name) {
	//removes all non alpha/numeric characters
	$name = preg_replace("/[^a-zA-Z0-9\s]/", "", $name);
	$name = str_replace(' ', '_', $name);
	return $name.".html";
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

function sendNewsletter($queueID=0, $startfrom=0){
	
	if($queueID == 0) 
		return false; //wtf? not queueID was defined musta been a booboo!
	
	//k we need to umm grab the info from the queue
	$queueResults = dbQuery('SELECT * FROM email_queue WHERE email_queue_id = ' . $queueID);
	$q = dbFetchArray($queueResults);
	/*
	
	*/
	//we have everything we need i believe!
	//lets send out the newsletter, then remove it from the queue and add to the sent table
	//the sent table will allow the admin to resend at anytime
	
	//include the class phpmailer()
	
	//now we need to get stuff!
	
	//$content .= output($q['email_templates_header']);
	
	//buid web version!
	
	//$content .= output($q['email_templates_footer']);
	
	/*Create HTML VERSION! added 5/28/10 */
	
	//create friendy name
	$newsFile = buildHTMLFilename($q['email_queue_subject']);
	/* END Create HTML Version */
	$htmlContent = "<html>";
	$htmlContent .= "<head>";
	$htmlContent .= "<title></title>";
	$htmlContent .= "</head>";
	$htmlContent .= "<body style=\"margin:0px; padding:0px;\">\n";
	$htmlContent .= "<style>".file_get_contents(SITE_PATH."manage/css/cms.css")."</style>";
	$content .= "<div style=\"font-size:10px; padding:5px; border:1px solid #666;\">\n";
	$content .= "<a href=\"".UPLOAD_DIR_NEWSLETTER_URL.$newsFile."\" target=\"_blank\" style=\"font-size:10px;\">Web Version</a><br />\n";
	
	$content .= "You received this email as a guest of  " . COMPANY_NAME . "<br />";
	$content .= "To ensure delivery, please add " .  $q['email_queue_from'] . " to your address book or safe senders list<br />\n";
	$content .= "</div>\n";
	$content .= output($q['email_queue_email_text']);
	$htmlContent .=  output($q['email_queue_email_text']);
	$content .= "<div style=\"font-size:10px; padding:5px; border:1px solid #666;\">\n";
	$content .= "<span style=\"font-size:13px; font-weight:bold;\">How to unsubscribe</span><br>\n";
	$content .= "If you no longer wish to receive emails from  " . COMPANY_NAME . " simply <a href=\"".NEWSLETTER_UNSUBSCRIBE_URL."\">unsubscribe</a><br><br>\n";
	$content .= "</div>\n";
	$htmlContent .= "</body>";
	$htmlContent .= "</html>";
	
	$newsletterFilename = UPLOAD_DIR_NEWSLETTER.$newsFile;
	$pointer = fopen($newsletterFilename, 'w');//open file/creates if doesn't exist!
	fwrite($pointer, $htmlContent);
	fclose($pointer);
	
	if($q['email_queue_recipients'] != "all") {
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
		
	} else {
		//we need to just go through the subscribers table and send it out that way to ensure they are being delivered!
		if($startfrom != 0) {
			$where = " WHERE subscriber_id > " . $startfrom;
		} else {
			$where = "";	
		}
		
		$recipResults = dbQuery("SELECT subscriber_email_address, subscriber_name FROM subscribers " . $where);
		set_time_limit(0);
		while($r=dbFetchArray($recipResults)) {
			$mail = new PHPMailer();
			$mail->From     = output($q['email_queue_from']);
			$mail->FromName = output($q['email_queue_from']);
			$mail->isMail 	= true;
			$mail->Body    	= output($content);
			$mail->AddAddress($r['subscriber_email_address']);
			$mail->Subject = output($q['email_queue_subject']);
			
			//check to see if there is an attachment
			if($q['email_queue_attachment'] != ""  ) {
				$mail->AddAttachment(UPLOAD_DIR_NEWSLETTER.$q['email_queue_attachment'], $q['email_queue_attachment']);
			}
			
			$mail->ContentType = 'text/html';
			$mail->Send();
			$mail->ClearAddresses();
			
		}
	}
	
	
	
	
	
	
	$row['client_id'] = 1;
	$row['email_queue_date_sent'] = time();
	$row['email_queue_subject'] = $q['email_queue_subject'];
	$row['email_queue_content'] = $q['email_queue_email_text'];
	$row['email_templates_id'] = $q['email_template_id'];
	$row['email_recipients'] = $q['email_queue_recipients'];
	$row['email_display_home'] = $q['email_display_home'];
	$row['email_log_file'] = $logFileName;

	dbPerform('email_queue_sent', $row, 'insert');
	dbQuery('DELETE FROM email_queue WHERE email_queue_id = ' . $queueID);
	
	
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