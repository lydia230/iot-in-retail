<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
    <script></script>
</head>

<body>
    <header>
        <nav>
            <h1>Dashboard</h1>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="add_client.php">Client</a></li>
                <li><a href="checkout.php">Checkout</a></li>
                <li><a href="inventory.php">Inventory</a></li>
            </ul>
        </nav>
    </header>

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