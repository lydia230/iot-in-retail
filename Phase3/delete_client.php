<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iotphase3";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['client_id'])) {
  $client_id = intval($_POST['client_id']);

  $conn->query("DELETE FROM purchase_history WHERE client_id = $client_id");

  $sql = "DELETE FROM clients WHERE client_id = $client_id";
  if ($conn->query($sql) === TRUE) {
    header("Location: clients.php?deleted=1");
    exit();
  } else {
    echo "<p style='color:red;text-align:center;'>Error deleting client: " . $conn->error . "</p>";
  }
}

$conn->close();
?>
