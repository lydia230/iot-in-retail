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
$membership = $_POST['membershipCode'] ?? '';
$sql1 = "SELECT * FROM customer WHERE membership = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $membership);
$stmt1->execute();
$result1 = $stmt1->get_result();
$customer = $result1->fetch_assoc();
 
if ($customer) {
    echo json_encode(['success' => true, 'customer' => $customer]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid membership!']);
}

?>