<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}


$data = json_decode($_POST['emailReceipt'], true);

$sql1 = "SELECT * FROM receipts WHERE receipt_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $data['receipt_id']);
$stmt1->execute();
$result1 = $stmt1->get_result();
$receipt = $result1->fetch_assoc();

if ($receipt) {
    

$customerEmail = $data['email'];
$totalAmount = $data['total'];
$receiptDate = $receipt['receipt_date'];
$items = $data['items'];
$points = $data['points'];


$itemsHtml = "";
foreach ($items as $item) {
    $itemsHtml .= "
        <tr>
            <td>{$item['name']}</td>
            <td>{$item['quantity']}</td>
            <td>\${$item['price']}</td>
        </tr>
    ";
}

$receiptBody = "
<html>
<head>
<style>
body { font-family: Arial, sans-serif; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
</style>
</head>
<body>
    <h2>Thank you for your purchase!</h2>
    <p>Here’s your receipt for order placed on {$receiptDate}.</p>
    <p>You have received {$points} points on this purchase!</p>
    <table>
        <tr>
            <th>Item</th><th>Qty</th><th>Price per Unit</th>
        </tr>
        {$itemsHtml}
    </table>
    <h3>Total Amount: \${$totalAmount}</h3>
    <p>We appreciate your business!</p>
</body>
</html>
";


$mail = new PHPMailer(true);

try {
    
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'temphum21@gmail.com';     
    $mail->Password   = 'gcmv rvsm hvxh ieuu';     
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    
    $mail->setFrom('temphum21@gmail.com', 'Receipt System');
    $mail->addAddress($customerEmail);

    
    $mail->isHTML(true);
    $mail->Subject = "Your Receipt";
    $mail->Body    = $receiptBody;

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Receipt sent successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
}
} else {
    echo json_encode(['success' => false, 'message' => $data['receipt_id']]);
}
?>
