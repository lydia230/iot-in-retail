<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "iotphase3";

    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
    }
    
    $sql = "SELECT Temp_id, Temp_threshold FROM temperature";
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