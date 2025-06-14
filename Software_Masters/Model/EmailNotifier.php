<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';  // adjust the path if needed

function sendEmail($to, $subject, $messageBody) {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bassantmoh200@gmail.com';         // your Gmail
        $mail->Password   = 'fdtbrhthsgwyolze';    // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email content
        $mail->setFrom('bassantmoh200@gmail.com', 'Event System');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $messageBody;

        $mail->send();
         echo "Email sent successfully to $to";
        return true;
    } catch (Exception $e) {
        echo "Email error: {$mail->ErrorInfo}";
        return false;
    }
}
