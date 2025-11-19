<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../nav.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Document</title>
    <script></script>
</head>

<body>
<?php 
$page_title = "Dashboard"; 
include "../nav.php"; 
?>

    <main>

        <div class="sensor">
            <h1>Fridge 1</h1>
            <div class="fridge1 fridge">
                <div class="temp1 data">
                    <h3>Temperature</h3>
                    <div id="chart-container-1"></div>
                    <div class="value">
                        <div>
                            <p>Current threshold:</p>
                        </div>
                        <div class="modify-threshold">
                            <p>Change threshold:</p>
                            <input type="text">
                            <button>Modify</button>
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
                    <h3>Temperature</h3>
                    <div id="chart-container-3"></div>
                    <div class="value">
                        <div>
                            <p>Current threshold:</p>
                        </div>
                        <div class="modify-threshold">
                            <p>Change threshold:</p>
                            <input type="text">
                            <button>Modify</button>
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
    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <script src="css/script.js"></script>
</body>

</html>