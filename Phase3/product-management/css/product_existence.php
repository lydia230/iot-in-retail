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

$code = $_POST['code'] ?? '';

$sql1 = '';

if (strlen($code) == 24) {
    $sql1 = "SELECT * FROM products WHERE epc = ?";
} else if (strlen($code) == 13) {
    $sql1 = "SELECT * FROM products WHERE upc = ?";
}

$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $code);
$stmt1->execute();
$result1 = $stmt1->get_result();
$product = $result1->fetch_assoc();

if ($product) {
    echo json_encode([
        'success' => true,
        'product' => $product,
    ]);

} else {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
}
?>
