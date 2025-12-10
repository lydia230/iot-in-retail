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

$data = json_decode($_POST['dates'], true);
$startDate = $data['start'];
$endDate = $data['end'];
$new_clients = 0;
$old_clients = 0;

$client_sql = "SELECT * FROM clients";
$client_stmt = $conn->prepare($client_sql);
$client_stmt->execute();
$client_result = $client_stmt->get_result();
$clients = $client_result->fetch_all();

for ($i = 0; $i < $client_result->num_rows; $i++) {
    $count_sql = "SELECT * FROM receipts WHERE client_id = ? AND receipt_date BETWEEN ? AND ?";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("iss", $clients[$i][0], $startDate, $endDate);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $client_number = $count_result->num_rows;
    
    if ($client_number != 0) {
        if ($client_number > 1) {
            $old_clients += 1;
        } else {
            $new_clients += 1;
        }
    }
}

echo json_encode(["old_clients" => $old_clients, "new_clients" => $new_clients]);