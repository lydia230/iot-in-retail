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
  WHERE r.client_id = $client_id
  GROUP BY r.receipt_id, r.receipt_date, r.total_amount, r.points
  ORDER BY r.receipt_date DESC;";
    $history_result = $conn->query($history_sql);
    ?>
    
    <section class="account-card">
      <h2><span class="account-title">Account Information</span></h2>
      <?php if ($client): ?>
        <div class="info">
          <p><strong><span class="label-name">Name:</span></strong> <?= htmlspecialchars($client['name']) ?></p>
          <p><strong><span class="label-email">Email:</span></strong> <?= htmlspecialchars($client['email']) ?></p>
          <p><strong><span class="label-member">Membership #:</span></strong> <?= htmlspecialchars($client['membership_number']) ?></p>
          <p><strong><span class="label-points">Total Points:</span></strong> <?= htmlspecialchars($client['total_points']) ?> pts</p>
        </div>
      <?php else: ?>
        <p class="text-muted"><span class="no-client">Client information not found.</span></p>
      <?php endif; ?>
    </section>

    <section class="history-card">
      <h2><span class="history-title">Purchase History</span></h2>
      <div class="errorSearch" id="errorSearch">
        <p id="errorSearchMessage"></p>
      </div>
      <div class="receipt-search">
        <label for="receipt-date"><span class="date-range-label">Date Range:</span></label>
        <input type="date" class="receipt-date" id="receipt-date-start">
        <p>-</p>
        <input type="date" class="receipt-date" id="receipt-date-end">
        <button id="receiptBtn"><span class="receipt-search-btn">Search</span></button>
      </div>
      <div class="table-containter">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th><span class="th-date">Date</span></th>
              <th><span class="th-receipt">Receipt #</span></th>
              <th><span class="th-items">Items</span></th>
              <th><span class="th-total">Total</span></th>
              <th><span class="th-points">Points</span></th>
              <th><span class="th-products">Products</span></th>
            </tr>
          </thead>
          <tbody id="receipts">
            <?php if ($history_result && $history_result->num_rows > 0): ?>
              <?php while ($row = $history_result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['receipt_date']) ?></td>
                  <td>#<?= htmlspecialchars($row['receipt_id']) ?></td>
                  <td><?= htmlspecialchars($row['item_count']) ?></td>
                  <td>$<?= number_format($row['total_amount'], 2) ?></td>
                  <td><?= htmlspecialchars($row['points']) ?></td>
                  <td><button class="details-button" id="details-button" onclick="displayProducts(this)"><span class="details-btn">Details</span></button></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted"><span class="noHistory">No purchase history available.</span></td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div id="total-section" class="total-section">
        <p><span class="total-label">Total Amount:</span></p>
        <p id="total-amount"></p>
      </div>
      
    </section>

    <div id="search-item" class="search-item">
      <h2><span class="search-title">Search for a specific item</span></h2>
      <div id="item-input-container" class="item-input-container">
          <label for="item-input"><span class="item-label">Item Name</span></label>
          <input type="text" id="item-input" class="item-input">
          <button id="itemBtn" class="itemBtn" data-client="<?php echo $client_id?>"><span class="search-btn">Search</span></button>
      </div>
      <div class="table-containter">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th><span class="th-item">Item</span></th>
              <th><span class="th-purchased">Purchased Amount</span></th>
              <th><span class="th-details">Details</span></th>
            </tr>
          </thead>
          <tbody id="search-content">
          </tbody>
        </table>
      </div>
    </div>
    <?php $conn->close(); ?>
  </main>

  <div id="popup" class="popup hidden">
    <div class="popup-content">
      <h3><span class="popup-title">Product Details</span></h3>
      <div class="products">
        <div class="productHeader">
          <p><span class="ph-name">Name</span></p>
          <p><span class="ph-quantity">Quantity</span></p>
          <p><span class="ph-unitPrice">Unity Price</span></p>
          <p><span class="ph-totalPrice">Total Price</span></p>
        </div>
        <div class="productContainer" id="productContainer"></div>
      </div>
      <button type="button" id="cancelPopupBtn" class="popupBtn"><span class="cancel-btn">Cancel</span></button>
    </div>
  </div>

  <script>
    const langBtn = document.getElementById("current-lang");
    const langMenu = document.getElementById("lang-menu");

    const text = {
      en: {
        account: "Account Information",
        purchase: "Purchase History",
        searchItemTitle: "Search for a specific item",
        dateRange: "Date Range:",
        searchBtn: "Search",
        name: "Name:",
        email: "Email:",
        member: "Membership #:",
        points: "Total Points:",
        tableHeaders: ["Date", "Receipt #", "Items", "Total", "Points", "Products"],
        tableItemHeaders: ["Item", "Purchased Amount", "Details"],
        productDetails: "Product Details",
        productTable: ["Name", "Quantity", "Unity Price", "Total Price"],
        cancel: "Cancel",
        noHistory: "No purchase history available."
      },
      fr: {
        account: "Informations du compte",
        purchase: "Historique des achats",
        searchItemTitle: "Rechercher un article spécifique",
        dateRange: "Plage de dates :",
        searchBtn: "Rechercher",
        name: "Nom :",
        email: "Courriel :",
        member: "Numéro d'adhésion :",
        points: "Points totaux :",
        tableHeaders: ["Date", "Reçu #", "Articles", "Total", "Points", "Produits"],
        tableItemHeaders: ["Article", "Quantité achetée", "Détails"],
        productDetails: "Détails du produit",
        productTable: ["Nom", "Quantité", "Prix unitaire", "Prix total"],
        cancel: "Annuler",
        noHistory: "Aucun historique d'achat disponible."
      }
    };

    function switchLanguage(lang) {
      const t = text[lang];
      if (lang == 'en'){
        fetch('post_language.php', {
        method: 'POST',
        credentials: "include",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            language: "en"
        })
        })
        .then(res => res.json())
        .then(data => {
            console.log("Server says:", data);
        })
        .catch(err => console.error(err));
      }
      if (lang == 'fr') {
        fetch('post_language.php', {
        method: 'POST',
        credentials: "include",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            language: "fr"
        })
        })
        .then(res => res.json())
        .then(data => {
            console.log("Server says:", data);
        })
        .catch(err => console.error(err));
      }

      document.querySelector(".account-title").textContent = t.account;
      document.querySelector(".label-name").textContent = t.name;
      document.querySelector(".label-email").textContent = t.email;
      document.querySelector(".label-member").textContent = t.member;
      document.querySelector(".label-points").textContent = t.points;

      document.querySelector(".history-title").textContent = t.purchase;
      document.querySelector(".date-range-label").textContent = t.dateRange;
      document.querySelector(".receipt-search-btn").textContent = t.searchBtn;
      document.querySelector(".search-title").textContent = t.searchItemTitle;
      document.querySelector(".item-label").textContent = t.tableItemHeaders[0];
      document.querySelector(".search-btn").textContent = t.searchBtn;

      const historyHeaders = document.querySelectorAll(".history-card table thead tr th");
      historyHeaders.forEach((th, i) => { th.querySelector("span").textContent = t.tableHeaders[i]; });

      const searchHeaders = document.querySelectorAll("#search-item table thead tr th");
      searchHeaders.forEach((th, i) => { th.querySelector("span").textContent = t.tableItemHeaders[i]; });

      document.querySelector(".popup-title").textContent = t.productDetails;
      const productHeaders = document.querySelectorAll(".productHeader p span");
      productHeaders.forEach((p, i) => { p.textContent = t.productTable[i]; });

      document.querySelector(".cancel-btn").textContent = t.cancel;

      const noHistoryRow = document.querySelector(".history-card tbody tr td.text-muted span");
      if (noHistoryRow) noHistoryRow.textContent = t.noHistory;

      langBtn.innerHTML = lang === "fr"
        ? `<img src="https://flagcdn.com/w20/fr.png" alt="France Flag"> <span>Français</span>`
        : `<img src="https://flagcdn.com/w20/us.png" alt="US Flag"> <span>English</span>`;
      langMenu.classList.remove("show");
    }
    document.querySelectorAll("#lang-menu button").forEach(b => {
      b.addEventListener("click", () => {
        switchLanguage(b.dataset.lang);
      location.reload();
      console.log('Reloaded page');
    });
    });

    langBtn.addEventListener("click", () => langMenu.classList.toggle("show"));
    window.addEventListener("click", (e) => {
      if (!langBtn.contains(e.target) && !langMenu.contains(e.target)) langMenu.classList.remove("show");
    });

    switchLanguage('<?php echo $_SESSION['language'];?>');
  </script>

  <script src="client-js/clientAccount.js"></script>
  <script src="nav.js"></script>
</body>

</html>
