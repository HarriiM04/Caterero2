<?php
@session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email)
{
    $otp = rand(100000, 999999);
    $_SESSION['sesFogetOtp'] = $otp;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jeminvn349@gmail.com';
        $mail->Password = 'oitngjcpwzmrnygq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        //Recipients
        $mail->setFrom('jeminvn349@gmail.com', 'OTP Verification');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'OTP Verification Royal Photography';
        $mail->Body = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body>
        <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
            <div style="margin:50px auto;width:70%;padding:20px 0">
                <div style="border-bottom:1px solid #eee">
                    <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Royal Photography</a>
                </div>
                <p style="font-size:1.1em">Hi,</p>
                <p>This OTP is for your Register In Maharaja Catering. Use the following OTP to complete your procedures. OTP is valid for 5 minutes.</p>
                <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">' . $otp . '</h2>
                <p style="font-size:0.9em;">Regards,<br />Make My Journey</p>
                <hr style="border:none;border-top:1px solid #eee" />
                <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
                    <p>Travelix</p>
                    <img src="cid:image_cid" alt="Jinal" height="50" width="50">
                </div>
            </div>
        </div>
        </body>
        </html>';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
    }
}
?>
