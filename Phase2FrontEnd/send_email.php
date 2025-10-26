<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';


$data = json_decode(file_get_contents("php://input"), true);
$temperature = $data['temperature'];


$mail = new PHPMailer(true);


try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'fridgeclient@gmail.com';       // your Gmail
    $mail->Password   = 'xqmc emhc brlu jxri';          // Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;


    //Recipients
    $mail->setFrom('fridgeclient@gmail.com', 'Raspberry Pi Alert');
    $mail->addAddress('fridgeclient@gmail.com');          // recipient


    // Content
    $mail->isHTML(false);
    $mail->Subject = 'High Temperature Alert!';
    $mail->Body    = "The current temperature is $temperature.Would you like to turn on the fan";


    $mail->send();
    echo 'Alert email sent.';
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
?>