<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/inventory.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Checkout</title>
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
        <div class="notification-container" id="notification-container">
            <div class="notification-title">
                <h1 class="title"> Notification: </h1>
                <p id="alert-message"> Scan products to update the inventory.</p>
            </div>
        </div>

        <div class="main-content">
            <div class="inventory-add">
                <label for="product-code-input" class="product-code-label">Product: </label>
                <input type="text" class="product-code-input" name="product-code-input" id="product-code-input">
                <button onclick="addProduct(document.getElementById('product-code-input').value)" id="product-code-input" class="submit-product">
                    Add
                </button>

                <button id="inv-btn" class="submit-product popup-add">
                    Add to Inventory
                </button>

                <button id="add-manual-btn" class="submit-product popup-add">
                    Add Product
                </button>
            </div>
            <div class="inventory-table">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "rfid";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Database connection failed");
                }

                $sql = "SELECT i.product_id, i.quantity, p.name, p.category, p.price, p.upc, p.epc, p.company
                            FROM inventory i
                            INNER JOIN products p ON i.product_id = p.product_id";
                $result = $conn->query($sql);
                ?>
                <table class="table table-hover" id="table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Cateory</th>
                            <th scope="col">Price</th>
                            <th scope="col">UPC</th>
                            <th scope="col">EPC</th>
                            <th scope="col">Producer</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Modify</th>
                            <th scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($rows = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $rows['name'] ?></td>
                                    <td><?= $rows['category'] ?></td>
                                    <td><?= $rows['price'] ?></td>
                                    <td><?= $rows['upc'] ?></td>
                                    <td><?= $rows['epc'] ?></td>
                                    <td><?= $rows['company'] ?></td>
                                    <td><?= $rows['quantity'] ?></td>
                                    <td><button class="modify-button" id="modify-button">Modify</button></td>
                                    <td><button class="delete-button" id="delete-button">Delete</button></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </main>
    <div id="popup" class="popup hidden">
        <div class="popup-content">
            <h3>Modify Product</h3>

            <label for="popupName">Name:</label>
            <input type="text" id="popupName" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupCategory">Category:</label>
            <input type="text" id="popupCategory" style="width:100%; margin-bottom:12px;"><br>

            <label for="popupPrice">Price:</label>
            <input type="number" step="0.01" id="popupPrice" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupUPC">UPC:</label>
            <input type="text" id="popupUPC" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupEPC">EPC:</label>
            <input type="text" id="popupEPC" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupCompany">Company:</label>
            <input type="text" id="popupCompany" style="width:100%; margin-bottom:8px;"><br>
            <p id="invalid-update"></p>
            <div id="buttons">
                <button type="save-button" id="savePopupBtn" class="popupBtn" onclick="updateProcess()">Save</button>
                <button type="cancel-button" id="cancelPopupBtn" class="popupBtn" onclick="cancelProcess()">Cancel</button>
            </div>

        </div>
    </div>

    <div id="popup-add" class="popup hidden">
        <div class="popup-content">
            <h3>Add Product</h3>

            <label for="popupNameAdd">Name:</label>
            <input type="text" id="popupNameAdd" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupCategoryAdd">Category:</label>
            <input type="text" id="popupCategoryAdd" style="width:100%; margin-bottom:12px;"><br>

            <label for="popupPriceAdd">Price:</label>
            <input type="number" step="0.01" id="popupPriceAdd" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupUPCAdd">UPC:</label>
            <input type="text" id="popupUPCAdd" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupEPCAdd">EPC:</label>
            <input type="text" id="popupEPCAdd" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupCompanyAdd">Company:</label>
            <input type="text" id="popupCompanyAdd" style="width:100%; margin-bottom:8px;"><br>
            <p id="invalid-add"></p>
            <div id="buttons">
                <button type="save-button" id="savePopupBtnAdd" class="popupBtn" onclick="addProductManually()">Add</button>
                <button type="cancel-button" id="cancelPopupBtnAdd" class="popupBtn" onclick="cancelProcess()">Cancel</button>
            </div>

        </div>
    </div>

    <div id="popup-inventory" class="popup hidden">
            <div class="popup-content">
                <h3>Add to Inventory</h3>

                <label for="popupNameInv">Name:</label>
                <input type="text" id="popupNameInv" style="width:100%; margin-bottom:8px;"><br>

                <label for="popupCategoryInv">Category:</label>
                <input type="text" id="popupCategoryInv" style="width:100%; margin-bottom:12px;"><br>

                <label for="popupPriceAddInv">Price:</label>
                <input type="number" step="0.01" id="popupPriceInv" style="width:100%; margin-bottom:8px;"><br>

                <label for="popupUPCInv">UPC:</label>
                <input type="text" id="popupUPCInv" style="width:100%; margin-bottom:8px;"><br>

                <label for="popupEPCInv">EPC:</label>
                <input type="text" id="popupEPCInv" style="width:100%; margin-bottom:8px;"><br>

                <label for="popupCompanyInv">Company:</label>
                <input type="text" id="popupCompanyInv" style="width:100%; margin-bottom:8px;"><br>

                <label for="popupQuantityInv">Quantity:</label>
                <input type="number" step="1" min="1" id="popupQuantityInv" style="width:100%; margin-bottom:8px;"><br>
                <p id="invalid-Inv"></p>
                <div id="buttons">
                    <button type="save-button" id="savePopupBtnAddInv" class="popupBtn" onclick="addInventoryManually()">Add</button>
                    <button type="cancel-button" id="cancelPopupBtnAddInv" class="popupBtn" onclick="cancelProcess()">Cancel</button>
                </div>

            </div>
        </div>
    <script src="css/inventory.js"></script>
</body>

</html>