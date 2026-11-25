<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = strip_tags(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($subject)) {
        http_response_code(400);
        echo "Please fill in all fields correctly.";
        exit;
    }

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;   // 0 = no output, 1 = errors only, 2 = full debug
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';                    // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'azsvcacc@gmail.com';              // Your Gmail address
        $mail->Password   = 'ksks zvjb rydr uaov';                 // App password (not your regular password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($email, $name);
        // $mail->addAddress('info@dform.co.in');                   // Your receiving email
        $mail->addAddress('azsvcacc@gmail.com');                   // Your receiving email

        // Content
        $mail->isHTML(false);
        $mail->Subject = "New contact from $name: $subject";
        $mail->Body    = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";

        $mail->send();
        http_response_code(200);
        // echo "Thank you! Your message has been sent.";

        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="refresh" content="3;url=contact.html">
            <title>Success</title>
        </head>
        <body style="font-family:Arial;text-align:center;margin-top:100px;">
            <h2>Thank you! Your message has been sent.</h2>
            <p>Redirecting to the contact page...</p>
        </body>
        </html>';
        exit;
        
    } catch (Exception $e) {
        http_response_code(500);
        echo "Message could not be sent. Error: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(403);
    echo "There was a problem with your submission.";
}
?>