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
$data = json_decode($_POST['receiptItems'], true);


$sql1 = "INSERT INTO receipt_items (receipt_id, product_id, quantity, unit_price, line_total) VALUES(?, ?, ?, ?, ?)";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("iiidd", $data['receipt_id'], $data['product_id'], $data['quantity'], $data['price'], $data['total']);
$stmt1->execute();


echo json_encode(['receipt_id' => $data['recipt_id']]);
?>
