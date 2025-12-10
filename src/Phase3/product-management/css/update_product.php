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
$data = json_decode($_POST['updateProduct'], true);
$epc = $data['oldEpc'];

$sql1 = "SELECT * FROM inventory WHERE epc = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $epc);
$stmt1->execute();
$result1 = $stmt1->get_result();
$product = $result1->fetch_assoc();

if ($product) {
    
    $sql3 = "UPDATE inventory SET epc = ? WHERE epc = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("ss", $data['newEpc'], $epc);
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
