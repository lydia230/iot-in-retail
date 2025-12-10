<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../nav.css">


    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js"
      type="text/javascript"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Document</title>
    <script></script>
</head>

<body>
<?php 
$page_title = "Dashboard"; 
include "../nav.php"; 
?>
        <center><h3 id="alert"></h3></center>
    <main>
        
        <div class="sensor">
            <h1>Fridge 1</h1>
            <div class="fridge1 fridge">
                <div class="temp1 data">
                    <div class="warning hidden" id="warning1">
                        <img src="warning.png" alt="warning">
                    </div>
                    <h3>Temperature</h3>
                    <div id="chart-container-1"></div>
                    <div class="value">
                        <div class="display-threshold">
                            <p>Current threshold:</p>
                            <p id="current-threshold-1"></p>
                        </div>
                        <div class="modify-threshold">
                            <form method="POST">
                                <input type="hidden" name="fridge_num" value="1">
                                <div class="threshold-layout">
                                <p>Change threshold:</p>
                                <input type="text" name="maxTemp1">
                                <button type="submit" name="save1">Modify</button>
                        </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="hum1 data">
                    <h3>Humidity</h3>
                    <div id="chart-container-2"></div>
                </div>
            </div>
            <h1>Fridge 2</h1>
            <div class="fridge2 fridge">
                <div class="temp2 data">
                    <div class="warning hidden" id="warning2">
                        <img src="warning.png" alt="warning">
                    </div>
                    <h3>Temperature</h3>
                    <div id="chart-container-3"></div>
                    <div class="value">
                        <div class="display-threshold">
                            <p>Current threshold:</p>
                            <p id="current-threshold-2"></p>
                        </div>
                        <div class="modify-threshold">
                            <form method="POST">
                                <input type="hidden" name="fridge_num" value="2">
                                <div class="threshold-layout">
                                    <p>Change threshold:</p>
                                    <input type="text" name="maxTemp2">
                                    <button type="submit" name="save2">Modify</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="hum2 data">
                    <h3>Humidity</h3>
                    <div id="chart-container-4"></div>
                </div>
            </div>
        </div>

        <div class="fan">
            <h1>Fan Settings</h1>
            <div class="rotation data">
                <img id="img" src="fan.png" alt="" srcset="" /> 
                <div class="interaction">
                     <button onclick="myfunon()" class="fan-button-on">ON</button>
                    <button onclick="myfunoff()" class="fan-button-on">OFF</button>
                </div>
            </div>
        </div>

    </main>
    <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iotphase3";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection Failed: " . $conn->connect_error);
}

if (isset($_POST['save1']) || isset($_POST['save2'])) {
  $fridgeNum = intval($_POST['fridge_num']);

  // Determine input field
  $maxTemp = ($fridgeNum == 1) ? $_POST['maxTemp1'] : $_POST['maxTemp2'];

  if (!is_numeric($maxTemp) || $maxTemp <= 0 || $maxTemp > 100) {
    echo "<script>
      alert('Invalid temperature value. Must be between 0°C and 100°C.');
    </script>";
  } else {

    // Update DB
    $sql = "UPDATE temperature SET Temp_threshold = '$maxTemp' WHERE Temp_id = '$fridgeNum'";
    if ($conn->query($sql) === TRUE) {

      echo "<script>
        document.addEventListener('DOMContentLoaded', () => {
          
          const alertBox = document.getElementById('alert');
          alertBox.textContent =
            'New max threshold ($maxTemp °C) saved for Fridge $fridgeNum';
          

          // Update threshold UI
          const threshold = document.getElementById('current-threshold-$fridgeNum');
          if (threshold) threshold.textContent = '$maxTemp';

          // Fade-out effect after 4s
          setTimeout(() => {
            alertBox.style.transition = 'opacity 1s';
            alertBox.style.opacity = 0;
          }, 4000);
        });
      </script>";

    } else {
      echo "<script>
        alert('Error updating database: " . addslashes($conn->error) . "');
      </script>";
    }
  }
}

$conn->close();
?>

    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <script src="css/script.js"></script>
    <script src="../nav.js"></script>

    <script>
    const langBtn = document.getElementById("current-lang");
    const langMenu = document.getElementById("lang-menu");

    langBtn.addEventListener("click", () => {
        langMenu.classList.toggle("show");
    });

    const visibleText = {
        en: {
            fridge1: "Fridge 1",
            fridge2: "Fridge 2",
            temperature: "Temperature",
            humidity: "Humidity",
            currentThreshold: "Current threshold:",
            changeThreshold: "Change threshold:",
            fanSettings: "Fan Settings",
            fanOn: "ON",
            fanOff: "OFF",
        },
        fr: {
            fridge1: "Frigo 1",
            fridge2: "Frigo 2",
            temperature: "Température",
            humidity: "Humidité",
            currentThreshold: "Seuil actuel :",
            changeThreshold: "Changer le seuil :",
            fanSettings: "Paramètres du ventilateur",
            fanOn: "MARCHE",
            fanOff: "ARRÊT",
        }
    };

    document.querySelectorAll("#lang-menu button").forEach((btn) => {
        btn.addEventListener("click", () => {
            const lang = btn.dataset.lang;

            fetch('../post_language.php', {
                method: 'POST',
                credentials: "include",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    language: lang
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log("Server says:", data);
            })
            .catch(err => console.error(err));

            const t = visibleText[lang];

            document.querySelectorAll(".sensor h1")[0].textContent = t.fridge1;
            document.querySelectorAll(".sensor h1")[1].textContent = t.fridge2;

            document.querySelector(".temp1 h3").textContent = t.temperature;
            document.querySelector(".hum1 h3").textContent = t.humidity;

            document.querySelector(".temp2 h3").textContent = t.temperature;
            document.querySelector(".hum2 h3").textContent = t.humidity;

            document.querySelectorAll(".display-threshold p:first-child")
                .forEach(el => el.textContent = t.currentThreshold);
            document.querySelectorAll(".threshold-layout p:first-child")
                .forEach(el => el.textContent = t.changeThreshold);

            document.querySelector(".fan h1").textContent = t.fanSettings;
            document.querySelector(".fan-button-on:nth-child(1)").textContent = t.fanOn;
            document.querySelector(".fan-button-on:nth-child(2)").textContent = t.fanOff;

            if (lang === "fr") {
                langBtn.innerHTML = `<img src="https://flagcdn.com/w20/fr.png"><span>Français</span>`;
            } else {
                langBtn.innerHTML = `<img src="https://flagcdn.com/w20/us.png"><span>English</span>`;
            }

            langMenu.classList.remove("show");
            location.reload();
            console.log('Reloaded page');
        }); 
    });

    window.addEventListener("click", (e) => {
        if (!langBtn.contains(e.target) && !langMenu.contains(e.target)) {
            langMenu.classList.remove("show");
        }
    });

    {
        const lang = '<?php echo $_SESSION["language"];?>';
        const t = visibleText[lang];

        document.querySelectorAll(".sensor h1")[0].textContent = t.fridge1;
        document.querySelectorAll(".sensor h1")[1].textContent = t.fridge2;

        document.querySelector(".temp1 h3").textContent = t.temperature;
        document.querySelector(".hum1 h3").textContent = t.humidity;

        document.querySelector(".temp2 h3").textContent = t.temperature;
        document.querySelector(".hum2 h3").textContent = t.humidity;

        document.querySelectorAll(".display-threshold p:first-child")
            .forEach(el => el.textContent = t.currentThreshold);
        document.querySelectorAll(".threshold-layout p:first-child")
            .forEach(el => el.textContent = t.changeThreshold);

        document.querySelector(".fan h1").textContent = t.fanSettings;
        document.querySelector(".fan-button-on:nth-child(1)").textContent = t.fanOn;
        document.querySelector(".fan-button-on:nth-child(2)").textContent = t.fanOff;

        if (lang === "fr") {
            langBtn.innerHTML = `<img src="https://flagcdn.com/w20/fr.png"><span>Français</span>`;
        } else {
            langBtn.innerHTML = `<img src="https://flagcdn.com/w20/us.png"><span>English</span>`;
        }

        langMenu.classList.remove("show");
    }
    </script>
</body>

</html>
