

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iotphase3";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection error']);
    exit;
}

$data = json_decode($_POST['cart_items'], true);

error_log("Received cart_items: " . print_r($data, true));

if (!is_array($data)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid cart_items']);
    exit;
}


foreach ($data as $epc) {
    $epc = trim($epc);  

    
    $sql = "SELECT p.product_id, p.name 
            FROM inventory i 
            JOIN products p ON i.product_id = p.product_id
            WHERE i.epc = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $epc);
    $stmt->execute();
    $result = $stmt->get_result();

    $itemData = $result->fetch_assoc();

    if (!$itemData) {
        
        error_log("Product with EPC '$epc' not found in inventory.");
        continue;
    }

    $product_id = $itemData['product_id'];
    $product_name = $itemData['name'];

    
    $deleteSql = "DELETE FROM inventory WHERE epc = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("s", $epc);
    $deleteStmt->execute();

    error_log("Deleted product with EPC: '$epc'");

    $reduceQuantitySql = "UPDATE inventory i
                          JOIN products p ON i.product_id = p.product_id
                          SET i.quantity = CASE
                                               WHEN i.quantity > 0 THEN i.quantity - 1
                                               ELSE 0
                                              END
                          WHERE p.name = ? AND i.product_id = ?";
    
    $reduceStmt = $conn->prepare($reduceQuantitySql);
    $reduceStmt->bind_param("si", $product_name, $product_id);
    $reduceStmt->execute();

    error_log("Reduced quantity by 1 for all products with the name: '$product_name'");
}


echo json_encode(['status' => 'success']);
?>

