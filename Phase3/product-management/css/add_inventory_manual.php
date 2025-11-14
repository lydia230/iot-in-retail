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

$data = json_decode($_POST['data'], true);
$epc = $data['epc'];

$sql1 = "SELECT * FROM products WHERE epc = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $epc);
$stmt1->execute();
$result1 = $stmt1->get_result();
$product = $result1->fetch_assoc();

if ($product) {

    $sql2 = "SELECT * FROM inventory WHERE product_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $product['product_id']);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $inventory = $result2->fetch_assoc();

    if ($inventory) {
        
        $sql3 = "UPDATE inventory SET quantity = quantity + " . $data['quantity']. ", update_time = NOW() WHERE product_id = ?";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("i", $product['product_id']);
        $stmt3->execute();

        
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $inventory = $result2->fetch_assoc();
    } else {
        $sql3 = "INSERT INTO inventory (product_id, quantity, update_time) VALUES(?, 1, NOW())";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("i", $product['product_id']);
        $stmt3->execute();

        
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $inventory = $result2->fetch_assoc();
    }

    echo json_encode([
        'success' => true,
        'product' => $product,
        'quantity' => $inventory['quantity'] ?? 1
    ]);

} else {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
}
?>
