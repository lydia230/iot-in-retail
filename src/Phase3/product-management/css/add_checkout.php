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

$client_id = $_POST['client_id'] ?? '';

$sql1 = "SELECT * FROM clients WHERE client_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $client_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$customer = $result1->fetch_assoc();

if ($customer) {
    $sql2 = "UPDATE clients SET total_points = total_points + 3 WHERE client_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $client_id);
    $stmt2->execute();

    echo json_encode([
        'success' => true,
        'points'=> 3
    ]);

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid operation']);
}
?>
