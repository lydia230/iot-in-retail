<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "iotphase3";

$language = "en";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo "<script>alert('Database connection failed');</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        echo "<script>alert('Both fields are required.'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT client_id, name, password FROM clients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "<script>alert('Email not found.'); window.history.back();</script>";
        exit();
    }

    $stmt->bind_result($client_id, $name, $stored_password);
    $stmt->fetch();

    if ($password !== $stored_password) {
        echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        exit();
    }

    session_start();
    $_SESSION["client_id"] = $client_id;
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;
    $_SESSION["language"] = $language;

    header("Location: clientAccount.php");
    exit();
}

$conn->close();
?>
