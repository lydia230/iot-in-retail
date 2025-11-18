<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "iotphase3";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirm_password = trim($_POST["confirm_password"] ?? "");

    if ($name === "" || $email === "" || $password === "" || $confirm_password === "") {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    if (strlen($password) < 8) {
        die("Password must be at least 8 characters.");
    }

    $check = $conn->prepare("SELECT client_id FROM clients WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        die("Email already registered.");
    }
    $check->close();

    $nextMembership = "M1001"; 
    $res = $conn->query("SELECT membership_number FROM clients ORDER BY client_id DESC LIMIT 1");
    if ($res && $row = $res->fetch_assoc()) {
        $last = $row['membership_number'] ?? "";
        if (preg_match('/(\d+)/', $last, $m)) {
            $num = intval($m[1]) + 1;
            $nextMembership = 'M' . $num;
        } else {
            $nextMembership = "M1001";
        }
    }
    if ($res) $res->free();

    $stmt = $conn->prepare("INSERT INTO clients (name, email, password, membership_number, total_points) VALUES (?, ?, ?, ?, ?)");
    $points = 0;
    $stmt->bind_param("ssssi", $name, $email, $password, $nextMembership, $points);

    if ($stmt->execute()) {
        header("Location: clientAccount.php");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>
