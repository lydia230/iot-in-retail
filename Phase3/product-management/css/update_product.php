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
$data = json_decode($_POST['updateProduct'], true);
$name = $data['oldName'];

$sql1 = "SELECT * FROM products WHERE name = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $name);
$stmt1->execute();
$result1 = $stmt1->get_result();
$product = $result1->fetch_assoc();

if ($product) {
    
    $sql3 = "UPDATE products SET name = ?, category = ?, price = ?, upc = ?, epc = ?, company = ? WHERE product_id = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("ssdiisi", $data['newName'], $data['newCategory'], $data['newPrice'], $data['newUpc'], $data['newEpc'], $data['newCompany'], $product['product_id']);
    $stmt3->execute();

            
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $product = $result1->fetch_assoc();

    echo json_encode([
        'success' => true,
        'product' => $product
    ]);
    
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
}
?>
