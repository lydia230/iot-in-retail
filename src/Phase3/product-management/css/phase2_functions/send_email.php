<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require __DIR__ . '/../../vendor/autoload.php';


$data = json_decode(file_get_contents("php://input"), true);
$temperature = $data['temperature'];


$mail = new PHPMailer(true);


try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'temphum21@gmail.com';    
    $mail->Password   = 'gcmv rvsm hvxh ieuu';    
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;


    $mail->setFrom('temphum21@gmail.com', 'Raspberry Pi Alert');
    $mail->addAddress('temphum21@gmail.com');         

    $mail->isHTML(false);
    $mail->Subject = 'High Temperature Alert!';
    $mail->Body    = "The current temperature is $temperature °C.Would you like to turn on the fan";


    $mail->send();
    echo 'Alert email sent.';
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
?>