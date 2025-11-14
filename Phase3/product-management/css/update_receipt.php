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

$data = json_decode($_POST['update'], true);

$sql1 = "UPDATE receipts SET total_amount = ? WHERE receipt_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ii", $data['amount'], $data['id']);
$stmt1->execute();
?>
