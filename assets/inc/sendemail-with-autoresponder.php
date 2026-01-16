<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();
$autoresponder = new PHPMailer();

//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'homebysohny.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'contact@homebysohny.com';                 // SMTP username
$mail->Password = 'PASScode123@#';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable SSL encryption
$mail->Port = 465;                                    // TCP port to connect to

// Configure auto-responder with same SMTP settings
$autoresponder->isSMTP();
$autoresponder->Host = 'homebysohny.com';
$autoresponder->SMTPAuth = true;
$autoresponder->Username = 'contact@homebysohny.com';
$autoresponder->Password = 'PASScode123@#';
$autoresponder->SMTPSecure = 'ssl';
$autoresponder->Port = 465;


if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['contact-form-name'] != '' AND $_POST['contact-form-email'] != '' AND $_POST['contact-form-subject'] != '' ) {

        $name = $_POST['contact-form-name'];
        $email = $_POST['contact-form-email'];
        $subject = $_POST['contact-form-subject'];
        $phone = $_POST['contact-form-phone'];
        $message = $_POST['contact-form-message'];


		$subject = isset($subject) ? $subject : 'New Message From Contact Form';

		$botcheck = $_POST['contact-form-botcheck'];

        $toemail = 'contact@homebysohny.com'; // Your Email Address
        $toname = 'Home By Sohny'; // Your Name

		if( $botcheck == '' ) {

			$mail->SetFrom( $email , $name );
			$mail->AddReplyTo( $email , $name );
			$mail->AddAddress( $toemail , $toname );
			$mail->Subject = $subject;

			$autoresponder->SetFrom( $toemail , $toname );
			$autoresponder->AddReplyTo( $toemail , $toname );
			$autoresponder->AddAddress( $email , $name );
			$autoresponder->Subject = 'Thank you for contacting Home By Sohny';

			$name = isset($name) ? "Name: $name<br><br>" : '';
			$email = isset($email) ? "Email: $email<br><br>" : '';
			$phone = isset($phone) ? "Phone: $phone<br><br>" : '';
			$message = isset($message) ? "Message: $message<br><br>" : '';

			$referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

			$body = "$name $email $phone $message $referrer";

			$ar_body = "Thank you for contacting Home By Sohny. We have received your message and will get back to you as soon as possible.<br><br>Regards,<br>Home By Sohny";

			$autoresponder->MsgHTML( $ar_body );
			$mail->MsgHTML( $body );
			$sendEmail = $mail->Send();

			if( $sendEmail == true ):
				$send_arEmail = $autoresponder->Send();
				echo 'We have <strong>successfully</strong> received your Message and will get Back to you as soon as possible.';
			else:
				echo 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '';
			endif;
		} else {
			echo 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
		}
	} else {
		echo 'Please <strong>Fill up</strong> all the Fields and Try Again.';
	}
} else {
	echo 'An <strong>unexpected error</strong> occured. Please Try Again later.';
}

?>