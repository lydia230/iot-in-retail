<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}
$data = json_decode($_POST['receiptInfo'], true);

$sql1 = "INSERT INTO receipts (customer_id, total_amount, points, receipt_date) VALUES(?, ?, ?, NOW())";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("idi", $data['customer_id'], $data['amount'], $data['points']);
$stmt1->execute();
$receipt_id = $conn->insert_id;

echo json_encode(['receipt_id' => $receipt_id]);
?>
