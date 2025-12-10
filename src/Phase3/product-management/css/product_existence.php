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

$code = $_POST['code'] ?? '';

if (strlen($code) !== 24) {
    echo json_encode(['success' => false, 'message' => 'Invalid EPC']);
    exit;
}


$sql = "SELECT i.product_id, p.*
        FROM inventory i
        INNER JOIN products p ON i.product_id = p.product_id
        WHERE i.epc = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    echo json_encode([
        'success' => true,
        'product' => $product
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Product not found']);
?>
