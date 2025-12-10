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
$name = $data['name'];
$category = $data['category'];
$upc = $data['upc'];

$sql1 = "SELECT * FROM products WHERE name = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $name);
$stmt1->execute();
$result1 = $stmt1->get_result();
$productByName = $result1->fetch_assoc();

if ($productByName) {
    echo json_encode(['success' => false, 'message' => 'Product name already exists!']);
    exit;
}

$sql2 = "SELECT * FROM products WHERE upc = ? AND category != ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("ss", $upc, $category);
$stmt2->execute();
$result2 = $stmt2->get_result();
$productByUPC = $result2->fetch_assoc();

if ($productByUPC) {
    echo json_encode(['success' => false, 'message' => 'UPC already exists for a different category!']);
    exit;
}


$sql3 = "INSERT INTO products (name, price, category, upc, company) VALUES (?, ?, ?, ?, ?)";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param(
    "sdsss", 
    $data['name'], 
    $data['price'], 
    $data['category'], 
    $data['upc'], 
    $data['company']
);
$stmt3->execute();

echo json_encode([
    'success' => true,
    'message' => 'Product added successfully!'
]);
?>
