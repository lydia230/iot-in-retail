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

$epc = $_POST['epc'] ?? '';

$sql1 = "SELECT * FROM inventory WHERE epc = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $epc);
$stmt1->execute();
$result1 = $stmt1->get_result();
$item = $result1->fetch_assoc();

if (!$item) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

$product_id = $item['product_id'];

$sqlDelete = "DELETE FROM inventory WHERE epc = ?";
$stmtDelete = $conn->prepare($sqlDelete);
$stmtDelete->bind_param("s", $epc);
$stmtDelete->execute();

$sqlCount = "SELECT COUNT(*) AS qty FROM inventory WHERE product_id = ?";
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->bind_param("i", $product_id);
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$rowCount = $resultCount->fetch_assoc();
$newQuantity = $rowCount['qty'];

$sqlUpdateQty = "UPDATE inventory SET quantity = ? WHERE product_id = ?";
$stmtUpdateQty = $conn->prepare($sqlUpdateQty);
$stmtUpdateQty->bind_param("ii", $newQuantity, $product_id);
$stmtUpdateQty->execute();

echo json_encode([
    'success' => true,
    'product' => $item,
    'quantity' => $newQuantity
]);
?>
