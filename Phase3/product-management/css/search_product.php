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

$name = $_POST['name'] ?? '';

$sql1 = "SELECT * FROM products WHERE name = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $name);
$stmt1->execute();
$result1 = $stmt1->get_result();
$product = $result1->fetch_assoc();

echo json_encode(['success' => true, 'product' => $product]);

?>
