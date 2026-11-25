<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Redirect if accessed directly
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: appointment.html");
    exit;
}

// Get form values
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$mobile  = trim($_POST['mobile'] ?? '');
$service = trim($_POST['service'] ?? '');
$date    = trim($_POST['date'] ?? '');
$time    = trim($_POST['time'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate
if (
    empty($name) ||
    empty($email) ||
    empty($message) ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    die("Please fill all required fields correctly.");
}

$mail = new PHPMailer(true);

try {

    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'azsvcacc@gmail.com';
    $mail->Password   = 'ksks zvjb rydr uaov';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender
    $mail->setFrom('azsvcacc@gmail.com', 'DFORM Website');

    // Reply-To
    $mail->addReplyTo($email, $name);

    // Receiver
    $mail->addAddress('azsvcacc@gmail.com');

    // Email
    $mail->isHTML(true);
    $mail->Subject = 'New Appointment Request';

    $mail->Body = '
    <h2>New Appointment Request</h2>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr>
            <td><strong>Name</strong></td>
            <td>' . htmlspecialchars($name) . '</td>
        </tr>

        <tr>
            <td><strong>Email</strong></td>
            <td>' . htmlspecialchars($email) . '</td>
        </tr>

        <tr>
            <td><strong>Mobile</strong></td>
            <td>' . htmlspecialchars($mobile) . '</td>
        </tr>

        <tr>
            <td><strong>Service</strong></td>
            <td>' . htmlspecialchars($service) . '</td>
        </tr>

        <tr>
            <td><strong>Date</strong></td>
            <td>' . htmlspecialchars($date) . '</td>
        </tr>

        <tr>
            <td><strong>Time</strong></td>
            <td>' . htmlspecialchars($time) . '</td>
        </tr>

        <tr>
            <td><strong>Message</strong></td>
            <td>' . nl2br(htmlspecialchars($message)) . '</td>
        </tr>
    </table>';

    $mail->send();

    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Appointment Submitted</title>
        <meta http-equiv="refresh" content="3;url=appointment.html">

        <style>
            body{
                margin:0;
                background:#f5f5f5;
                font-family:Arial,sans-serif;
                display:flex;
                justify-content:center;
                align-items:center;
                height:100vh;
            }

            .box{
                background:#fff;
                padding:40px;
                border-radius:10px;
                box-shadow:0 0 15px rgba(0,0,0,.15);
                text-align:center;
            }

            h2{
                color:green;
            }
        </style>

    </head>

    <body>

        <div class="box">
            <h2>✅ Appointment Submitted Successfully!</h2>

            <p>Thank you for contacting us.</p>

            <p>We will contact you shortly.</p>

            <p>Redirecting in 3 seconds...</p>

        </div>

    </body>
    </html>

    <?php

} catch (Exception $e) {

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Mail Error</title>
    </head>

    <body>

        <h2>Mail Error</h2>

        <p><?php echo htmlspecialchars($mail->ErrorInfo); ?></p>

    </body>
    </html>
    <?php

}
?>