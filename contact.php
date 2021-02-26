<?php
  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

if($_POST) {
    $mainid = "sumana.mondal@houseofmusa.com";
	$password = "sumanashreemondal7865";
    $visitor_name = "";
    $visitor_email = "";
    $email_title = "";
    $concerned_department = "";
    $visitor_message = "";
    $email_body = "<div>";
	$url = "index";
      
    if(isset($_POST['visitor_name'])) {
        $visitor_name = filter_var($_POST['visitor_name'], FILTER_SANITIZE_STRING);
        $email_body .= "<div>
                           <label><b>Visitor Name:</b></label>&nbsp;<span>".$visitor_name."</span>
                        </div>";
    }
 
    if(isset($_POST['visitor_email'])) {
        $visitor_email = str_replace(array("\r", "\n", "%0a", "%0d"), '', $_POST['visitor_email']);
        $visitor_email = filter_var($visitor_email, FILTER_VALIDATE_EMAIL);
        $email_body .= "<div>
                           <label><b>Visitor Email:</b></label>&nbsp;<span>".$visitor_email."</span>
                        </div>";
    }
        
    if(isset($_POST['visitor_message'])) {
        $visitor_message = htmlspecialchars($_POST['visitor_message']);
        $email_body .= "<div>
                           <label><b>Visitor Message:</b></label>
                           <div>".$visitor_message."</div>
                        </div>";
    }
      
    $email_title = "New message from ".$visitor_name;
    $email_body .= "</div>";
	
	if(isset($_POST['url'])) {
        $url = filter_var($_POST['url'], FILTER_SANITIZE_STRING);
    }
 	
	try {
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = 587;

		$mail->Username = $mainid;
		$mail->Password = $password;

		// Sender and recipient settings
		$mail->setFrom($mainid, 'Contact us mail');
		$mail->addAddress($mainid, 'Admin');
		$mail->addReplyTo($visitor_email, $visitor_name); // to set the reply to

		// Setting the email content
		$mail->IsHTML(true);
		$mail->Subject = $email_title;
		$mail->Body = $email_body;
		$mail->AltBody = html_entity_decode($email_body);

		$mail->send();
		header("Location: https://site.url/".$url.".html?m=thankyou");
		die();
	} catch (Exception $e) {
		header("Location: https://site.url/".$url.".html?m=failed");
		die();
	}
      
} else {
    echo 'Something went wrong';
}
?>