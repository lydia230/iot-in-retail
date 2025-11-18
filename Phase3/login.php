<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "iotphase3";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        die("Both fields are required.");
    }

    $stmt = $conn->prepare("SELECT client_id, name, password FROM clients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        die("Email not found.");
    }

    $stmt->bind_result($client_id, $name, $stored_password);
    $stmt->fetch();

    if ($password !== $stored_password) {
        die("Incorrect password.");
    }

    session_start();
    $_SESSION["client_id"] = $client_id;
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;

    header("Location: clientAccount.php");
    exit();
}

$conn->close();
?>
