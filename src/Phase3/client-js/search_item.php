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
$data = json_decode($_POST['data'], true);
$client_id = $data['client_id'];
$item = $data['item'];

$items_sql = "SELECT r.receipt_id, r.receipt_date, i.*, p.name 
            FROM receipts r 
            JOIN receipt_items i 
            ON r.receipt_id = i.receipt_id
            JOIN products p
            ON i.product_id = p.product_id
            WHERE r.client_id LIKE ?
            AND p.name = ?";
$stmt1 = $conn->prepare($items_sql);
$stmt1->bind_param("is", $client_id, $item);
$stmt1->execute();
$result1 = $stmt1->get_result();
$items = $result1->fetch_all();

echo json_encode(["items" => $items]);
?>