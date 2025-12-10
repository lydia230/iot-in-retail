<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Client List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="clientAccount.css">
  <style>
    .fade-out {
      opacity: 0;
      transition: opacity 0.5s ease;
    }
  </style>
</head>

<body>  
  <?php
  $page_title = "Clients";
  include "nav.php";
  ?>

  <main>
    <section class="history-card">
      <h2>Registered Clients</h2>

      <?php
      if (!isset($_SESSION["client_id"])) {
        header("Location: login.html");
        exit();
      }


      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "iotphase3";


      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
        die("<p style='color:red;text-align:center;'>Database connection failed.</p>");
      }

      if (isset($_GET['deleted'])) {
        echo "<div id='deleteAlert' class='alert alert-success text-center'>Client deleted successfully!</div>";
      }

      $sql = "SELECT client_id, name, email, membership_number, total_points 
                FROM clients ORDER BY client_id ASC";
      $result = $conn->query($sql);
      ?>

      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Client ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Membership #</th>
            <th>Total Points</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['client_id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['membership_number']) ?></td>
                <td><?= htmlspecialchars($row['total_points']) ?></td>
                <td>
                  <form method="POST" action="delete_client.php" onsubmit="return confirm('Are you sure you want to delete this client?');">
                    <input type="hidden" name="client_id" value="<?= $row['client_id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">
                      Delete
                    </button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center text-muted">No clients found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <?php $conn->close(); ?>
    </section>

    <section class="activity-card">
      <h2>Customer Activity</h2>
      <div class="sales-search-first">
        <label for="receipt-date">Date Range:</label>
        <input type="date" class="receipt-date" id="receipt-date-start">
        <p>-</p>
        <input type="date" class="receipt-date" id="receipt-date-end">
        <button id="receiptBtn" onclick="calculateActivity()">Search</button>
      </div>
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Date Range</th>
            <th>Total Customers</th>
            <th>New Customers</th>
            <th>Returning Customers #</th>
          </tr>
        </thead>
        <tbody id="activity-body">

        </tbody>
      </table>


    </section>
  </main>

  <script>
    const alertBox = document.getElementById('deleteAlert');
    if (alertBox) {
      setTimeout(() => {
        alertBox.classList.add('fade-out');
        setTimeout(() => alertBox.remove(), 500);
      }, 3000);
    }

const langBtn = document.getElementById("current-lang");
const langMenu = document.getElementById("lang-menu");

const text = {
  en: {
    clientsHeading: "Registered Clients",
    activityHeading: "Customer Activity",
    dateRange: "Date Range:",
    searchBtn: "Search",
    tableHeaders: {
      clientId: "Client ID",
      name: "Name",
      email: "Email",
      membership: "Membership #",
      totalPoints: "Total Points",
      delete: "Delete",
      activityDate: "Date Range",
      totalCustomers: "Total Customers",
      newCustomers: "New Customers",
      returningCustomers: "Returning Customers #"
    },
    deleteConfirm: "Are you sure you want to delete this client?"
  },
  fr: {
    clientsHeading: "Clients inscrits",
    activityHeading: "Activité des clients",
    dateRange: "Plage de dates :",
    searchBtn: "Rechercher",
    tableHeaders: {
      clientId: "ID Client",
      name: "Nom",
      email: "Email",
      membership: "Numéro d'adhésion",
      totalPoints: "Points Totals",
      delete: "Supprimer",
      activityDate: "Plage de dates",
      totalCustomers: "Clients Totals",
      newCustomers: "Nouveaux Clients",
      returningCustomers: "Clients Récurrents #"
    },
    deleteConfirm: "Êtes-vous sûr de vouloir supprimer ce client ?"
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

  document.querySelector(".history-card h2").textContent = t.clientsHeading;
  document.querySelector(".activity-card h2").textContent = t.activityHeading;

  const clientTableHeaders = document.querySelectorAll(".history-card table thead tr th");
  clientTableHeaders[0].textContent = t.tableHeaders.clientId;
  clientTableHeaders[1].textContent = t.tableHeaders.name;
  clientTableHeaders[2].textContent = t.tableHeaders.email;
  clientTableHeaders[3].textContent = t.tableHeaders.membership;
  clientTableHeaders[4].textContent = t.tableHeaders.totalPoints;
  clientTableHeaders[5].textContent = t.tableHeaders.delete;

  const activityTableHeaders = document.querySelectorAll(".activity-card table thead tr th");
  activityTableHeaders[0].textContent = t.tableHeaders.activityDate;
  activityTableHeaders[1].textContent = t.tableHeaders.totalCustomers;
  activityTableHeaders[2].textContent = t.tableHeaders.newCustomers;
  activityTableHeaders[3].textContent = t.tableHeaders.returningCustomers;

  document.querySelector(".sales-search-first label").textContent = t.dateRange;
  document.querySelector("#receiptBtn").textContent = t.searchBtn;

  document.querySelectorAll(".history-card form").forEach(form => {
    form.onsubmit = function() {
      return confirm(t.deleteConfirm);
    };
  });

  langBtn.innerHTML =
    lang === "fr"
      ? `<img src="https://flagcdn.com/w20/fr.png" alt="France Flag"> <span>Français</span>`
      : `<img src="https://flagcdn.com/w20/us.png" alt="US Flag"> <span>English</span>`;

  langMenu.classList.remove("show");
}

langBtn.addEventListener("click", () => langMenu.classList.toggle("show"));

document.querySelectorAll("#lang-menu button").forEach((b) => {
  b.addEventListener("click", () => {
    const lang = b.dataset.lang;
    switchLanguage(lang);
          location.reload();
          console.log('Reloaded page');
  });
});

window.addEventListener("click", (e) => {
  if (!langBtn.contains(e.target) && !langMenu.contains(e.target))
    langMenu.classList.remove("show");
});

switchLanguage('<?php echo $_SESSION['language'];?>');

</script>
  <script src="client-js/clientActivity.js"></script>
  <script src="nav.js"></script>
</body>

</html>