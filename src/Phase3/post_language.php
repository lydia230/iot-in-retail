<?php
session_start();
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['language'])) {
    $_SESSION['language'] = $input['language'];

    echo json_encode([
        "success" => true,
        "message" => "Session updated",
        "language" => $_SESSION['language']
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No language provided"
    ]);
}
