<?php
    $servername = "localhost";
    $username = "admin";
    $password = "123";
    $dbname = "rfid";

    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
    }
    
    $sql = "SELECT Temp_id, Temp_threshold FROM Temperature";
    $result = $conn->query($sql);

    $thresholds = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $thresholds[$row['Temp_id']] = floatval($row['Temp_threshold']);
        }
    }

    echo json_encode($thresholds);
    $conn->close();
?>