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

$customer_id = $_POST['customer_id'] ?? '';

$sql1 = "SELECT * FROM customer WHERE customer_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $customer_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$customer = $result1->fetch_assoc();

if ($customer) {
    $sql2 = "UPDATE customer SET points = points + 3 WHERE customer_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $customer_id);
    $stmt2->execute();

    // $stmt1->execute();
    // $result1 = $stmt1->get_result();
    // $customer = $result1->fetch_assoc();
    echo json_encode([
        'success' => true,
        'points'=> 3
    ]);

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid operation']);
}
?>
