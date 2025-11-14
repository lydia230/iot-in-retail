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

if (!$product) {

    $sql2 = "INSERT INTO products (name,price, category, upc, epc, company) VALUES(?, ?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("sdsiss", $data['name'], $data['price'], $data['category'], $data['upc'], $data['epc'], $data['company']);
    $stmt2->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Product already exists!'
    ]);

} else {
    echo json_encode(['success' => false, 'message' => 'Product already exists!']);
}
?>
