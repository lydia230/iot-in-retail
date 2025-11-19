<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="clientAccount.css">
</head>

<body>

<?php 
$page_title = "My Account"; 
include "nav.php"; 
?>

  <main>
    <?php

if (!isset($_SESSION["client_id"])) {
    header("Location: login.html");
    exit();
}

$client_id = $_SESSION["client_id"]; 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iotphase3";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("<p style='color:red;text-align:center;'>Database connection failed.</p>");
}

$client_sql = "SELECT name, email, membership_number, total_points 
               FROM clients 
               WHERE client_id = $client_id";
$client_result = $conn->query($client_sql);
$client = $client_result->fetch_assoc();

$history_sql = "SELECT r.receipt_date, r.receipt_id, COUNT(ri.receipt_items_id) AS item_count, r.total_amount, r.points
 FROM receipts r
  LEFT JOIN receipt_items ri
  ON r.receipt_id = ri.receipt_id
  WHERE r.client_id = client_id
  GROUP BY r.receipt_id, r.receipt_date, r.total_amount, r.points
  ORDER BY r.receipt_date DESC;";
$history_result = $conn->query($history_sql);
?>
    <section class="account-card">
      <h2>Account Information</h2>
      <?php if ($client): ?>
        <div class="info">
          <p><strong>Name:</strong> <?= htmlspecialchars($client['name']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></p>
          <p><strong>Membership #:</strong> <?= htmlspecialchars($client['membership_number']) ?></p>
          <p><strong>Total Points:</strong> <?= htmlspecialchars($client['total_points']) ?> pts</p>
        </div>
      <?php else: ?>
        <p class="text-muted">Client information not found.</p>
      <?php endif; ?>
    </section>

    <section class="history-card">
      <h2>Purchase History</h2>

      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Receipt #</th>
            <th>Items</th>
            <th>Total</th>
            <th>Points</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($history_result && $history_result->num_rows > 0): ?>
            <?php while ($row = $history_result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['receipt_date']) ?></td>
                <td>#<?= htmlspecialchars($row['receipt_id']) ?></td>
                <td><?= htmlspecialchars($row['item_count']) ?></td>
                <td>$<?= number_format($row['total_amount'], 2) ?></td>
                <td><?= htmlspecialchars($row['points']) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted">No purchase history available.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <?php $conn->close(); ?>
    </section>
  </main>

  <script>
    const langBtn = document.getElementById("current-lang");
    const langMenu = document.getElementById("lang-menu");

    langBtn.addEventListener("click", () => langMenu.classList.toggle("show"));

    const text = {
      en: {
        account: "Account Information",
        purchase: "Purchase History",
        name: "Name:",
        email: "Email:",
        member: "Membership #:",
        points: "Total Points:",
      },
      fr: {
        account: "Informations du compte",
        purchase: "Historique des achats",
        name: "Nom :",
        email: "Courriel :",
        member: "Numéro d'adhésion :",
        points: "Points totaux :",
      },
    };

    document.querySelectorAll("#lang-menu button").forEach((b) =>
      b.addEventListener("click", () => {
        const l = b.dataset.lang;
        const t = text[l];
        document.querySelector(".account-card h2").textContent = t.account;
        document.querySelector(".history-card h2").textContent = t.purchase;

        langBtn.innerHTML =
          l === "fr"
            ? `<img src="https://flagcdn.com/w20/fr.png" alt="France Flag"> <span>Français</span>`
            : `<img src="https://flagcdn.com/w20/us.png" alt="US Flag"> <span>English</span>`;

        langMenu.classList.remove("show");
      })
    );

    window.addEventListener("click", (e) => {
      if (!langBtn.contains(e.target) && !langMenu.contains(e.target))
        langMenu.classList.remove("show");
    });
  </script>
</body>
</html>
