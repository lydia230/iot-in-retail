<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iotphase3";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}
$receipt_id = $_POST['receipt_id'];

$receipt_products_sql = "SELECT rp.*, p.* FROM receipt_items rp JOIN products p ON rp.product_id = p.product_id WHERE receipt_id = ?";
$stmt1 = $conn->prepare($receipt_products_sql);
$stmt1->bind_param("i", $receipt_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$receipt_products = $result1->fetch_all();

echo json_encode(["receip_products" => $receipt_products]);