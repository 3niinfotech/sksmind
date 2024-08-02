<?php	
	set_time_limit(4000);
	include("../database.php");
	$imapPath = '{mail.sksmdiamonds.in:143/novalidate-cert}INBOX';
	$username = 'bounceback@sksmdiamonds.in';
	$password = 'sksm@3ni';
	$inbox = imap_open($imapPath,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
	$emails = imap_search($inbox,'ALL');

	
	//echo '21';
	//print_r($emails);
	//exit;
	
	if($emails) {
		
		$output = '';
		//rsort($emails);
		$i=0;
		foreach($emails as $email_number) {
		$i++;
			$overview = imap_fetch_overview($inbox,$email_number,0);
			$message = imap_fetchbody($inbox,$email_number,1);
			
			/*	$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
				$output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
				$output.= '<span class="from">'.$overview[0]->from.'</span>';
				$output.= '<span class="date">on '.$overview[0]->date.'</span>';
				$output.= '<span class="date">on '.$overview[0]->date.'</span>';
				$output.= '</div>';
				$output.= '<div class="body">'.$message.'</div>';
		    */ 
			
		$matches = array();
		$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';	
		preg_match($pattern, $message, $matches);
		$email = $matches[0];
		//echo $email.$i."<br>";
		$email = trim($email);
		
		$email_track_col = mysql_query("select * from email where email = '$email'");
		$email_track =  mysql_fetch_assoc($email_track_col);
		$email_id  = $email_track['id'];
		
		if($email_id)
		{	
			$sql = "INSERT INTO bounce_email(email) VALUES ('$email_id')";
			if (mysql_query($sql)) {
				echo "success";
			}
		}	
		 
		imap_delete($inbox, $i);
	}
} 
	/*
		$check = imap_mailboxmsginfo($inbox);
		echo "Messages before delete: " . $check->Nmsgs . "<br />\n";
		$check = imap_mailboxmsginfo($inbox);
		echo "Messages after  delete: " . $check->Nmsgs . "<br />\n";
		imap_expunge($inbox);
		$check = imap_mailboxmsginfo($inbox);
		echo "Messages after expunge: " . $check->Nmsgs . "<br />\n";
	*/
imap_close($inbox);