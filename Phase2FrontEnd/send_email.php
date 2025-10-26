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
    $mail->Username   = 'temphum21@gmail.com';       // your Gmail
    $mail->Password   = 'gcmv rvsm hvxh ieuu';          // Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;


    //Recipients
    $mail->setFrom('temphum21@gmail.com', 'Raspberry Pi Alert');
    $mail->addAddress('temphum21@gmail.com');          // recipient


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