<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

// --- MASTER SMTP CONFIGURATION ---
$smtp_host = 'homebysohny.com'; 
$smtp_user = 'contact@homebysohny.com'; 
$smtp_pass = 'PASScode123@#';
$notification_recipients = [
    'contact@homebysohny.com' => 'Contact'
];

$message = "";
$status = "false";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( !empty($_POST['form_name']) AND !empty($_POST['form_email']) AND !empty($_POST['form_subject']) ) {

        $name = $_POST['form_name'];
        $email = $_POST['form_email'];
        $subject = $_POST['form_subject'];
        $phone = $_POST['form_phone'];
        $form_msg = $_POST['form_message'];

        $subject = isset($subject) ? $subject : 'New Message | Contact Form';
        $botcheck = $_POST['form_botcheck'];

        if( $botcheck == '' ) {
            
            // Initialize Mailer
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $smtp_user;
            $mail->Password = $smtp_pass;
            
            // SECURITY BYPASS: Many servers have invalid certificates for localhost/internal mail
            // This allows the connection to proceed even if the certificate is not verified.
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->Timeout = 40; // Increased timeout

            // Set From and Recipients
            $mail->SetFrom( $smtp_user , 'Home By Sohny' );
            $mail->AddReplyTo( $email , $name );
            foreach($notification_recipients as $addr => $receiver) {
                $mail->AddAddress( $addr , $receiver );
            }
            $mail->Subject = $subject;

            // Prepare Auto-responder
            $autoresponder = new PHPMailer();
            $autoresponder->isSMTP();
            $autoresponder->Host = $smtp_host;
            $autoresponder->SMTPAuth = true;
            $autoresponder->Username = $smtp_user;
            $autoresponder->Password = $smtp_pass;
            $autoresponder->SMTPOptions = $mail->SMTPOptions; // Same bypass
            $autoresponder->SMTPSecure = 'ssl';
            $autoresponder->Port = 465;
            
            $autoresponder->SetFrom( $smtp_user , 'Home By Sohny' );
            $autoresponder->AddReplyTo( $smtp_user , 'Home By Sohny' );
            $autoresponder->AddAddress( $email , $name );
            $autoresponder->Subject = 'Thank you for contacting Home By Sohny';

            // Display formatting
            $name_display = htmlspecialchars($name);
            $email_display = htmlspecialchars($email);
            $phone_display = htmlspecialchars($phone);
            $message_display = nl2br(htmlspecialchars($form_msg));
            $subject_display = htmlspecialchars($subject);
            $referrer = !empty($_SERVER['HTTP_REFERER']) ? '<br><br>This Form was submitted from: ' . htmlspecialchars($_SERVER['HTTP_REFERER']) : '';

            // --- HTML BODIES ---
            $body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>New Contact Form Message</title></head><body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;"><table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;"><tr><td align="center"><table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><tr><td style="background: linear-gradient(135deg, #bf202f 0%, #a01a27 100%); padding: 30px; text-align: center;"><h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">New Contact Form Message</h1></td></tr><tr><td style="padding: 40px 30px;"><p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">You have received a new message from your website contact form:</p><table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f9f9f9; border-radius: 6px; padding: 25px; margin: 20px 0;"><tr><td style="padding: 8px 0;"><strong style="color: #bf202f; font-size: 14px; display: inline-block; width: 120px;">Name:</strong><span style="color: #333333; font-size: 14px;">' . $name_display . '</span></td></tr><tr><td style="padding: 8px 0;"><strong style="color: #bf202f; font-size: 14px; display: inline-block; width: 120px;">Email:</strong><span style="color: #333333; font-size: 14px;"><a href="mailto:' . $email_display . '" style="color: #bf202f; text-decoration: none;">' . $email_display . '</a></span></td></tr>' . (!empty($phone_display) ? '<tr><td style="padding: 8px 0;"><strong style="color: #bf202f; font-size: 14px; display: inline-block; width: 120px;">Phone:</strong><span style="color: #333333; font-size: 14px;"><a href="tel:' . htmlspecialchars($phone_display) . '" style="color: #333333; text-decoration: none;">' . $phone_display . '</a></span></td></tr>' : '') . '<tr><td style="padding: 8px 0;"><strong style="color: #bf202f; font-size: 14px; display: inline-block; width: 120px;">Subject:</strong><span style="color: #333333; font-size: 14px;">' . $subject_display . '</span></td></tr></table><div style="background-color: #ffffff; border-left: 4px solid #bf202f; padding: 20px; margin: 20px 0;"><h3 style="color: #bf202f; margin: 0 0 15px 0; font-size: 18px;">Message:</h3><p style="color: #555555; font-size: 15px; line-height: 1.8; margin: 0;">' . $message_display . '</p></div>' . (!empty($referrer) ? '<p style="color: #888888; font-size: 12px; margin: 20px 0 0 0; padding-top: 20px; border-top: 1px solid #eeeeee;">' . $referrer . '</p>' : '') . '</td></tr><tr><td style="background-color: #f9f9f9; padding: 25px 30px; text-align: center; border-top: 1px solid #eeeeee;"><p style="color: #888888; font-size: 13px; margin: 0 0 10px 0;">This email was sent from your website contact form.</p><p style="color: #888888; font-size: 13px; margin: 0;">To reply, simply reply to this email or use the email address provided above.</p></td></tr></table></td></tr></table></body></html>';

            $ar_body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Thank You - Home By Sohny</title></head><body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;"><table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;"><tr><td align="center"><table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><tr><td style="background: linear-gradient(135deg, #bf202f 0%, #a01a27 100%); padding: 30px; text-align: center;"><h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">Thank You for Contacting Us!</h1></td></tr><tr><td style="padding: 40px 30px;"><p style="color: #333333; font-size: 18px; line-height: 1.6; margin: 0 0 20px 0; font-weight: bold;">Hello ' . $name_display . ',</p><p style="color: #555555; font-size: 16px; line-height: 1.8; margin: 0 0 20px 0;">Thank you for reaching out to <strong style="color: #bf202f;">Home By Sohny</strong>! We have Successfully Received Your Message and are Excited to Help You with Your Home Staging and Interior Decor Needs.</p><div style="background-color: #f9f9f9; border-left: 4px solid #bf202f; padding: 20px; margin: 25px 0;"><p style="color: #333333; font-size: 15px; line-height: 1.8; margin: 0;"><strong>Here is a copy of your message:</strong></p><p style="color: #555555; font-size: 14px; line-height: 1.6; margin: 10px 0 5px 0;"><strong>Name:</strong> ' . $name_display . '</p><p style="color: #555555; font-size: 14px; line-height: 1.6; margin: 0 0 5px 0;"><strong>Email:</strong> ' . $email_display . '</p><p style="color: #555555; font-size: 14px; line-height: 1.6; margin: 0 0 5px 0;"><strong>Phone:</strong> ' . $phone_display . '</p><p style="color: #555555; font-size: 14px; line-height: 1.6; margin: 0 0 5px 0;"><strong>Subject:</strong> ' . $subject_display . '</p><p style="color: #555555; font-size: 14px; line-height: 1.6; margin: 10px 0 0 0;"><strong>Message:</strong><br>' . $message_display . '</p></div><div style="background-color: #f9f9f9; border-left: 4px solid #bf202f; padding: 20px; margin: 25px 0;"><p style="color: #333333; font-size: 15px; line-height: 1.8; margin: 0;"><strong>What happens next?</strong></p><ul style="color: #555555; font-size: 15px; line-height: 1.8; margin: 10px 0 0 0; padding-left: 20px;"><li style="margin-bottom: 8px;">Our team will review your message within 24 hours</li><li style="margin-bottom: 8px;">We\'ll reach out to discuss your specific needs</li><li style="margin-bottom: 8px;">We\'ll provide a customized solution for your property</li></ul></div><p style="color: #555555; font-size: 16px; line-height: 1.8; margin: 25px 0 20px 0;">If you have any urgent questions, feel free to contact us directly:</p><table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f9f9f9; border-radius: 6px; padding: 20px; margin: 20px 0;"><tr><td style="padding: 8px 0;"><strong style="color: #bf202f; font-size: 14px;">Phone:</strong><span style="color: #333333; font-size: 14px; margin-left: 10px;"><a href="tel:9255239723" style="color: #333333; text-decoration: none;">(925)-523-9723</a></span></td></tr><tr><td style="padding: 8px 0;"><strong style="color: #bf202f; font-size: 14px;">Email:</strong><span style="color: #333333; font-size: 14px; margin-left: 10px;"><a href="mailto:sohny@homebysohny.com" style="color: #bf202f; text-decoration: none;">sohny@homebysohny.com</a></span></td></tr><tr><td style="padding: 8px 0;"><strong style="color: #bf202f; font-size: 14px;">Address:</strong><span style="color: #333333; font-size: 14px; margin-left: 10px;">the Bay Area</span></td></tr></table><p style="color: #555555; font-size: 16px; line-height: 1.8; margin: 25px 0 0 0;">We look forward to working with you!</p><p style="color: #555555; font-size: 16px; line-height: 1.8; margin: 20px 0 0 0;">Best regards,<br><strong style="color: #bf202f;">The Home By Sohny Team</strong></p></td></tr><tr><td style="background-color: #f9f9f9; padding: 25px 30px; text-align: center; border-top: 1px solid #eeeeee;"><p style="color: #888888; font-size: 13px; margin: 0 0 10px 0;"><strong style="color: #bf202f;">Home By Sohny</strong></p><p style="color: #888888; font-size: 12px; margin: 0 0 5px 0;">Professional Home Staging & Interior Decor Services</p><p style="color: #888888; font-size: 12px; margin: 0;">Serving the Bay Area</p></td></tr></table></td></tr></table></body></html>';

            $mail->MsgHTML($body);
            $autoresponder->MsgHTML($ar_body);

            // --- SEND LOGIC ---
            // Notice: Forcing SMTP here so we can see the REAL error if it fails
            $sendEmail = $mail->Send();
            $send_arEmail = $autoresponder->Send();

            if( $sendEmail == true ):
                $message = 'Your <strong>Message</strong> has been received successfully. We will get back to you as soon as possible.';
                $status = "true";
            else:
                // Show DETAILED error so we can fix it if it fails
                $message = 'Email <strong>could not</strong> be sent. <br /><br /><strong>Technical Reason:</strong><br />' . $mail->ErrorInfo . '';
                $status = "false";
            endif;
        } else {
            $message = 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
            $status = "false";
        }
    } else {
        $message = 'Please <strong>Fill up</strong> all the Fields and Try Again.';
        $status = "false";
    }
} else {
    $message = 'An <strong>unexpected error</strong> occured. Please Try Again later.';
    $status = "false";
}


$status_array = array( 'message' => $message, 'status' => $status);
echo json_encode($status_array);
?>