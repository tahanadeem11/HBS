<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();
$autoresponder = new PHPMailer();

//$mail->SMTPDebug = 3; // Enable verbose debug output
$mail->isSMTP(); // Set mailer to use SMTP
$mail->Host = 'homebysohny.com'; // Specify main and backup SMTP servers
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username = 'contact@homebysohny.com'; // SMTP username
$mail->Password = 'PASScode123@#'; // SMTP password
$mail->SMTPSecure = 'ssl'; // Enable SSL encryption
$mail->Port = 465; // TCP port to connect to

// Configure auto-responder with same SMTP settings
$autoresponder->isSMTP();
$autoresponder->Host = 'homebysohny.com';
$autoresponder->SMTPAuth = true;
$autoresponder->Username = 'contact@homebysohny.com';
$autoresponder->Password = 'PASScode123@#';
$autoresponder->SMTPSecure = 'ssl';
$autoresponder->Port = 465;

$message = "";
$status = "false";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
 if( $_POST['form_name'] != '' AND $_POST['form_email'] != '' AND $_POST['form_subject'] != '' ) {

 $name = $_POST['form_name'];
 $email = $_POST['form_email'];
 $subject = $_POST['form_subject'];
 $phone = $_POST['form_phone'];
 $message = $_POST['form_message'];

 $subject = isset($subject) ? $subject : 'New Message | Contact Form';

 $botcheck = $_POST['form_botcheck'];

 $toemail = 'contact@homebysohny.com'; // Your Email Address
 $toname = 'Home By Sohny'; // Your Name

 if( $botcheck == '' ) {

 $mail->SetFrom( 'contact@homebysohny.com' , 'Home By Sohny' );
 $mail->AddReplyTo( $email , $name );
 $mail->AddAddress( $toemail , $toname );
 $mail->AddAddress( 'thomas@dmworxllc.com' , 'Thomas' );
 $mail->Subject = $subject;

 $autoresponder->SetFrom( 'contact@homebysohny.com' , 'Home By Sohny' );
 $autoresponder->AddReplyTo( 'contact@homebysohny.com' , 'Home By Sohny' );
 $autoresponder->AddAddress( $email , $name );
 $autoresponder->Subject = 'Thank you for contacting Home By Sohny';

 $name_display = isset($name) ? htmlspecialchars($name) : '';
 $email_display = isset($email) ? htmlspecialchars($email) : '';
 $phone_display = isset($phone) ? htmlspecialchars($phone) : '';
 $message_display = isset($message) ? nl2br(htmlspecialchars($message)) : '';
 $subject_display = isset($subject) ? htmlspecialchars($subject) : 'New Message | Contact Form';

 $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br>This Form was submitted from: ' . htmlspecialchars($_SERVER['HTTP_REFERER']) : '';

 // Beautiful HTML template for business notification email
 $body = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Message</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #bf202f 0%, #a01a27 100%); padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">New Contact Form Message</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">You have received a new message from your website contact form:</p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f9f9f9; border-radius: 6px; padding: 25px; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #bf202f; font-size: 14px; display: inline-block; width: 120px;">Name:</strong>
                                        <span style="color: #333333; font-size: 14px;">' . $name_display . '</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #bf202f; font-size: 14px; display: inline-block; width: 120px;">Email:</strong>
                                        <span style="color: #333333; font-size: 14px;"><a href="mailto:' . $email_display . '" style="color: #bf202f; text-decoration: none;">' . $email_display . '</a></span>
                                    </td>
                                </tr>
                                ' . (!empty($phone_display) ? '<tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #bf202f; font-size: 14px; display: inline-block; width: 120px;">Phone:</strong>
                                        <span style="color: #333333; font-size: 14px;"><a href="tel:' . htmlspecialchars($phone_display) . '" style="color: #333333; text-decoration: none;">' . $phone_display . '</a></span>
                                    </td>
                                </tr>' : '') . '
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #bf202f; font-size: 14px; display: inline-block; width: 120px;">Subject:</strong>
                                        <span style="color: #333333; font-size: 14px;">' . $subject_display . '</span>
                                    </td>
                                </tr>
                            </table>
                            
                            <div style="background-color: #ffffff; border-left: 4px solid #bf202f; padding: 20px; margin: 20px 0;">
                                <h3 style="color: #bf202f; margin: 0 0 15px 0; font-size: 18px;">Message:</h3>
                                <p style="color: #555555; font-size: 15px; line-height: 1.8; margin: 0;">' . $message_display . '</p>
                            </div>
                            
                            ' . (!empty($referrer) ? '<p style="color: #888888; font-size: 12px; margin: 20px 0 0 0; padding-top: 20px; border-top: 1px solid #eeeeee;">' . $referrer . '</p>' : '') . '
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9f9f9; padding: 25px 30px; text-align: center; border-top: 1px solid #eeeeee;">
                            <p style="color: #888888; font-size: 13px; margin: 0 0 10px 0;">This email was sent from your website contact form.</p>
                            <p style="color: #888888; font-size: 13px; margin: 0;">To reply, simply reply to this email or use the email address provided above.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';

 // Beautiful HTML template for auto-responder email
 $ar_body = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Home By Sohny</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #bf202f 0%, #a01a27 100%); padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">Thank You for Contacting Us!</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #333333; font-size: 18px; line-height: 1.6; margin: 0 0 20px 0; font-weight: bold;">Hello ' . $name_display . ',</p>
                            
                            <p style="color: #555555; font-size: 16px; line-height: 1.8; margin: 0 0 20px 0;">Thank you for reaching out to <strong style="color: #bf202f;">Home By Sohny</strong>! We have Successfully Received Your Message and are Excited to Help You with Your Home Staging and Interior Decor Needs.</p>
                            
                            <div style="background-color: #f9f9f9; border-left: 4px solid #bf202f; padding: 20px; margin: 25px 0;">
                                <p style="color: #333333; font-size: 15px; line-height: 1.8; margin: 0;"><strong>What happens next?</strong></p>
                                <ul style="color: #555555; font-size: 15px; line-height: 1.8; margin: 10px 0 0 0; padding-left: 20px;">
                                    <li style="margin-bottom: 8px;">Our team will review your message within 24 hours</li>
                                    <li style="margin-bottom: 8px;">We\'ll reach out to discuss your specific needs</li>
                                    <li style="margin-bottom: 8px;">We\'ll provide a customized solution for your property</li>
                                </ul>
                            </div>
                            
                            <p style="color: #555555; font-size: 16px; line-height: 1.8; margin: 25px 0 20px 0;">If you have any urgent questions, feel free to contact us directly:</p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f9f9f9; border-radius: 6px; padding: 20px; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #bf202f; font-size: 14px;">Phone:</strong>
                                        <span style="color: #333333; font-size: 14px; margin-left: 10px;"><a href="tel:9255239723" style="color: #333333; text-decoration: none;">(925)-523-9723</a></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #bf202f; font-size: 14px;">Email:</strong>
                                        <span style="color: #333333; font-size: 14px; margin-left: 10px;"><a href="mailto:contact@homebysohny.com" style="color: #bf202f; text-decoration: none;">contact@homebysohny.com</a></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #bf202f; font-size: 14px;">Address:</strong>
                                        <span style="color: #333333; font-size: 14px; margin-left: 10px;">the Bay Area</span>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="color: #555555; font-size: 16px; line-height: 1.8; margin: 25px 0 0 0;">We look forward to working with you!</p>
                            
                            <p style="color: #555555; font-size: 16px; line-height: 1.8; margin: 20px 0 0 0;">Best regards,<br><strong style="color: #bf202f;">The Home By Sohny Team</strong></p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9f9f9; padding: 25px 30px; text-align: center; border-top: 1px solid #eeeeee;">
                            <p style="color: #888888; font-size: 13px; margin: 0 0 10px 0;"><strong style="color: #bf202f;">Home By Sohny</strong></p>
                            <p style="color: #888888; font-size: 12px; margin: 0 0 5px 0;">Professional Home Staging & Interior Decor Services</p>
                            <p style="color: #888888; font-size: 12px; margin: 0;">Serving the Bay Area</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';

 $mail->MsgHTML( $body );
 $autoresponder->MsgHTML( $ar_body );
 
 // Always send email to the user who filled the form
 $send_arEmail = $autoresponder->Send();
 
 // Send email to business
 $sendEmail = $mail->Send();

 if( $sendEmail == true ):
 $message = 'Your <strong>Message</strong> Has Been Received Successfully. We Will Get Back to You as Soon as Possible.';
 $status = "true";
 else:
 $message = 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '';
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