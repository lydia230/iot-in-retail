<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "iotphase3";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$clientId = $_SESSION['client_id'] ?? null;
$isAdmin = false;

if ($clientId) {
    $stmt = $conn->prepare("SELECT client_type FROM clients WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if ($row['client_type'] === 'admin') {
            $isAdmin = true;
        }
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Phase3/nav.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<header>
  <nav>
    <div class="nav-left">
      <h1 class="page-title"><?= ($_SESSION["language"] == 'fr') ? ('Mon Compte') : ('My Account');?></h1>
    </div>
    <div class="nav-right">
      <ul>
        <li><a href="/Phase3/clientAccount.php"><?= ($_SESSION["language"] == 'fr') ? ('Compte') : ('Account');?></a></li>
        <li><a href="/Phase3/product-management/index.php"><?= ($_SESSION["language"] == 'fr') ? ('Tableau de Bord') : ('Dashboard');?></a></li>
        <?php if ($isAdmin): ?>
            <li><a href="/Phase3/clients.php"><?= ($_SESSION["language"] == 'fr') ? ('Client') : ('Client');?></a></li>
        <?php endif; ?>
        <li><a href="/Phase3/product-management/checkout.php"><?= ($_SESSION["language"] == 'fr') ? ('Caisse') : ('Checkout');?></a></li>
        <?php if ($isAdmin): ?>
            <li><a href="/Phase3/product-management/inventory.php"><?= ($_SESSION["language"] == 'fr') ? ('Inventaire') : ('Inventory');?></a></li>
        <?php endif; ?>
        <li><a href="/Phase3/signout.php"><?= ($_SESSION["language"] == 'fr') ? ('Se Deconnecter') : ('Sign out');?></a></li>
      </ul>

      <div class="lang-dropdown">
        <button class="lang-btn" id="current-lang">
          <img src="https://flagcdn.com/w20/us.png" alt="US Flag" />
          <span>English</span>
        </button>

        <div class="lang-menu" id="lang-menu">
          <button data-lang="en">
            <img src="https://flagcdn.com/w20/us.png" alt="US Flag" />
            English
          </button>

          <button data-lang="fr">
            <img src="https://flagcdn.com/w20/fr.png" alt="France Flag" />
            Français
          </button>
        </div>
      </div>
      <button id="dark-mode-toggle" class="dark-mode-btn">🌙</button>
    </div>
  </nav>
</header>
