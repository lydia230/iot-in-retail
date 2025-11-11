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
  <header>
    <nav>
      <h1>Client List</h1>
      
      <div class="nav-right">
        <ul>
          <li><a href="clientAccount.php">Account</a></li>
          <li><a href="#">Dashboard</a></li>
          <li><a href="clients.php">Client</a></li>
          <li><a href="#">Checkout</a></li>
          <li><a href="#">Inventory</a></li>
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
      </div>
    </nav>
  </header>

  <main>
    <section class="history-card">
      <h2>Registered Clients</h2>

      <?php
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

    langBtn.addEventListener("click", () => langMenu.classList.toggle("show"));

    const text = {
      en: { clients: "Registered Clients" },
      fr: { clients: "Clients inscrits" },
    };

    document.querySelectorAll("#lang-menu button").forEach((b) =>
      b.addEventListener("click", () => {
        const l = b.dataset.lang;
        const t = text[l];
        document.querySelector(".history-card h2").textContent = t.clients;

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
