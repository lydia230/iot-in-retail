<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="Refrigerator Dashboard"
      content="A dashboard for users who wish to monitor their sensors temperatures and humidities."
    />
    <title>Refrigerator Dashboard</title>
    <script src="pureknob.js"></script>
    <!-- Script for temperature/humidity gauge -->
    <!-- The link: https://www.cssscript.com/canvas-javascript-knob-dial-component/ -->
    <!-- Bootstrap Links -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js"
      type="text/javascript"
    ></script>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
    <!-- External CSS for layout and visuals -->
    <link rel="stylesheet" href="refrigeratorDashboard.css" />
  </head>
  <body>
<header>
  <div class="header-container">
    <div class="tabs">
      <a href="index.php" class="tab">Add Users</a>
      <a href="refrigeratorDashboard.php" class="tab active">Dashboards</a>
    </div>
    <h1>Refrigerator Dashboard</h1>
  </div>
  <hr />
</header>
    <main>
      <!-- Body for the rest of dashboard will go -->
      <div id="fridge1">
        <!-- Section for all content pertaining to refrigerator 1 -->
        <div id="temp1" class="sensor-box">
          <!-- Section for the temperature of refrigerator 1 -->
          <div id="temp1Gauge"></div>
          <!-- Gauge for temperature 1 -->
          <div id="temp1Modifiers" class="modifiers">
            <form method="POST" action="">
              <div class="max">
                <input type="text" placeholder="Max" name="maxTemp1" required />
              </div>
              <input type="hidden" name="fridge_num" value="1" />
              <button type="submit" class="customize-btn" name="save1">Customize</button>
            </form>
          </div>
        </div>
        <div id="hum1" class="sensor-box">
          <!-- Section for the humidity of refrigerator 1 -->
        </div>
      </div>
      <div id="fridge2">
        <!-- Section for all content pertaining to refrigerator 2 -->
        <div id="temp2" class="sensor-box">
          <!-- Section for the temperature of refrigerator 2 -->
          <div id="temp2Gauge"></div>
          <!-- Gauge for temperature 2 -->
          <div id="temp2Modifiers" class="modifiers">
            <form method="POST" action="">
              <div class="max">
                <input type="text" placeholder="Max" name="maxTemp2" required />
              </div>
              <input type="hidden" name="fridge_num" value="2" />
              <button type="submit" class="customize-btn" name="save2">Customize</button>
            </form>
          </div>
        </div>
        <div id="hum2" class="sensor-box">
          <!-- Section for the humidity of refrigerator 2 -->
        </div>
      </div>
      <div id="fan">
        <!-- Section for the fan - Displays the status of the fan based on the temperatures -->
        <div id="fanInfo"></div>
        <div id="fanButtonDiv">
          <button id="fanButton" onclick="manuallyControlFan()">
            Manually Turn On
          </button>
        </div>
      </div>
      <div id="test"></div>
      <!-- Placeholder for debugging/testing -->
    </main>
    <footer>
      <!-- Footer for the website -->
      <script
        src="refrigeratorDashboard.js"
        onload="createDashboards()"
      ></script>
      <!-- Import Refrigerator Dashboard controller script -->
    </footer>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfid";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection Failed: " . $conn->connect_error);
}

if (isset($_POST['save1']) || isset($_POST['save2'])) {
  $fridgeNum = intval($_POST['fridge_num']);

  if ($fridgeNum == 1) {
    $maxTemp = $_POST['maxTemp1'];
  } else {
    $maxTemp = $_POST['maxTemp2'];
  }

  if (!is_numeric($maxTemp) || $maxTemp <= 0 || $maxTemp > 100) {
    echo "<script>alert('Invalid temperature value. Must be between 0°C and 100°C.');</script>";
  } else {
    $sql = "UPDATE Temperature SET Temp_threshold = '$maxTemp', Fridge_num = '$fridgeNum' WHERE Temp_id = 1";
    if ($conn->query($sql) === TRUE) {
      echo "<script>alert('New max threshold $maxTemp saved for Fridge $fridgeNum!');</script>";
    } else {
      echo "<script>alert('Error updating database: " . addslashes($conn->error) . "');</script>";
    }
  }
}

$conn->close();
?>
  </body>
</html>
