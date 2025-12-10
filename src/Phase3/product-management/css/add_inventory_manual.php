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

$productName = $data['category'];  // The selected product name
$epc         = $data['epc'];

//----------------------------------------------------------
// STEP 1: Fetch product by name
//----------------------------------------------------------
$sql1 = "SELECT * FROM products WHERE name = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $productName);
$stmt1->execute();
$result1 = $stmt1->get_result();
$product = $result1->fetch_assoc();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

//----------------------------------------------------------
// STEP 2: Check if EPC already exists
//----------------------------------------------------------
$sql2 = "SELECT * FROM inventory WHERE epc = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $epc);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'EPC already used!']);
    exit;
}

//----------------------------------------------------------
// STEP 3: Insert new item into inventory
//----------------------------------------------------------
$sql3 = "INSERT INTO inventory (product_id, epc, quantity, update_time)
         VALUES (?, ?, 1, NOW())";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("is", $product['product_id'], $epc);
$stmt3->execute();

//----------------------------------------------------------
// STEP 4: Count how many inventory rows match this product
//----------------------------------------------------------
$sqlCount = "SELECT COUNT(*) AS qty FROM inventory WHERE product_id = ?";
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->bind_param("i", $product['product_id']);
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$rowCount = $resultCount->fetch_assoc();
$newQuantity = $rowCount['qty'];

//----------------------------------------------------------
// STEP 5: Update ALL inventory rows so `quantity` matches
//----------------------------------------------------------
$sqlUpdateQty = "UPDATE inventory SET quantity = ? WHERE product_id = ?";
$stmtUpdateQty = $conn->prepare($sqlUpdateQty);
$stmtUpdateQty->bind_param("ii", $newQuantity, $product['product_id']);
$stmtUpdateQty->execute();

//----------------------------------------------------------
// STEP 6: Return JSON response
//----------------------------------------------------------
echo json_encode([
    'success'  => true,
    'product'  => $product,
    'quantity' => $newQuantity
]);
?>
