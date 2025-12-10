<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Phase3/product-management/css/inventory.css">
    <link rel="stylesheet" href="../nav.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Checkout</title>
</head>

<body>
    <?php
    $page_title = "Inventory";
    include "../nav.php";
    if (!isset($_SESSION["client_id"])) {
        header("Location: ../login.html");
        exit();
      }
    ?>
    <main>
        <div class="notification-container" id="notification-container">
            <div class="notification-title">
                <h1 class="title"> Notification: </h1>
                <p id="alert-message"> Scan products to update the inventory.</p>
            </div>
        </div>

        <div class="main-content">

            <div class="inventory-table">
                <h2>Inventory</h2>
                <div class="inventory-add">
                    <button id="inv-btn" class="submit-product popup-add">
                        Add to Inventory
                    </button>
                </div>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "iotphase3";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Database connection failed");
                }

                $sql = "SELECT i.product_id, i.quantity, p.name, p.category, p.price, p.upc, i.epc, p.company
                            FROM inventory i
                            INNER JOIN products p ON i.product_id = p.product_id";
                $result = $conn->query($sql);

                ?>
                <div class="inventory-table-container">
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
        </div>

        <div class="product-table">

            <h2>Products</h2>

            <div class="product-search">
                <button id="add-manual-btn" class="submit-product popup-add">
                    Add Product
                </button>
            </div>
            <div class="products-table-container">
                <table class="table table-hover" id="table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Product ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Price</th>
                            <th scope="col">UPC</th>
                            <th scope="col">Company</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-products">

                    </tbody>
                </table>
            </div>

        </div>


        <div class="sales-table">
            <div class="header">
                <h2>Sales</h2>
                <a id="download-report" onclick="downloadReport()">Download sales report</a>
            </div>
            <div class="sales-search">
                <div class="sales-search-first">
                    <label for="receipt-date">Date Range:</label>
                    <input type="date" class="receipt-date" id="receipt-date-start">
                    <p>-</p>
                    <input type="date" class="receipt-date" id="receipt-date-end">
                    <input type="checkbox" id="desc" class="desc" name="desc" value="desc" onchange="onSortCheck()">
                    <label for="desc">Descending</label><br>
                </div>
                <div>
                    <button id="receiptBtn" onclick="viewSales()">Search</button>
                </div>
            </div>
            <div class="sales-table-container">
                <table class="table table-hover" id="table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Sold Quantity</th>
                            <th scope="col">Period</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-sales">

                    </tbody>
                </table>
            </div>
            <div id="total-section" class="total-section">
                <p>Total Amount:</p>
                <p id="total-amount">$0.00</p>
            </div>
        </div>


    </main>
    <div id="popup" class="popup hidden">
        <div class="popup-content">
            <h3>Modify Product</h3>

            <label for="popupName">Name:</label>
            <input type="text" id="popupName" style="width:100%; margin-bottom:8px;" disabled><br>

            <label for="popupCategory">Category:</label>
            <input type="text" id="popupCategory" style="width:100%; margin-bottom:12px;" disabled><br>

            <label for="popupPrice">Price:</label>
            <input type="number" step="0.01" id="popupPrice" style="width:100%; margin-bottom:8px;" disabled><br>

            <label for="popupUPC">UPC:</label>
            <input type="text" id="popupUPC" style="width:100%; margin-bottom:8px;" disabled><br>

            <label for="popupEPC">EPC:</label>
            <input type="text" id="popupEPC" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupCompany">Company:</label>
            <input type="text" id="popupCompany" style="width:100%; margin-bottom:8px;" disabled><br>
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
            <input type="text" id="popupCategoryAdd" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupPriceAdd">Price</label>
            <input type="number" step="0.01" id="popupPriceAdd" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupUPCAdd">UPC</label>
            <input type="text" id="popupUPCAdd" style="width:100%; margin-bottom:8px;"><br>

            <label for="popupCompanyAdd">Company</label>
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
            <label for="popupCategoryInv">Product Name</label><br>
            <select name="category" id="popupCategoryInv" class="popupCategoryInv" size="2">
            </select>
            <br>
            <label for="popupEPCInv">EPC</label>
            <input type="text" id="popupEPCInv" style="width:100%; margin-bottom:8px;"><br>

            <p id="invalid-Inv"></p>
            <div id="buttons">
                <button type="save-button" id="savePopupBtnAddInv" class="popupBtn" onclick="addInventoryManually()">Add</button>
                <button type="cancel-button" id="cancelPopupBtnAddInv" class="popupBtn" onclick="cancelProcess()">Cancel</button>
            </div>

        </div>
    </div>
    <script src="css/inventory.js"></script>
    <script src="../nav.js"></script>

    <script>
    const langBtn = document.getElementById("current-lang");
    const langMenu = document.getElementById("lang-menu");

    langBtn.addEventListener("click", () => {
        langMenu.classList.toggle("show");
    });

    const visibleText = {
        en: {
            inventoryTitle: "Inventory",
            addInventoryBtn: "Add to Inventory",
            productsTitle: "Products",
            addProductBtn: "Add Product",
            salesTitle: "Sales",
            downloadReport: "Download sales report",
            dateRange: "Date Range:",
            descending: "Descending",
            searchBtn: "Search",
            tableHeaders: {
                inventory: ["Name", "Category", "Price", "UPC", "EPC", "Producer", "Quantity", "Modify", "Delete"],
                products: ["Product ID", "Name", "Category", "Price", "UPC", "Company"],
                sales: ["Product", "Sold Quantity", "Period"]
            },
            totalAmount: "Total Amount:"
        },
        fr: {
            inventoryTitle: "Inventaire",
            addInventoryBtn: "Ajouter à l'inventaire",
            productsTitle: "Produits",
            addProductBtn: "Ajouter un produit",
            salesTitle: "Ventes",
            downloadReport: "Télécharger le rapport de ventes",
            dateRange: "Plage de dates :",
            descending: "Décroissant",
            searchBtn: "Rechercher",
            tableHeaders: {
                inventory: ["Nom", "Catégorie", "Prix", "UPC", "EPC", "Producteur", "Quantité", "Modifier", "Supprimer"],
                products: ["ID Produit", "Nom", "Catégorie", "Prix", "UPC", "Entreprise"],
                sales: ["Produit", "Quantité vendue", "Période"]
            },
            totalAmount: "Montant total :"
        }
    };

    document.querySelectorAll("#lang-menu button").forEach((btn) => {
        btn.addEventListener("click", () => {
            const lang = btn.dataset.lang;
            if (lang == 'en'){
                fetch('../post_language.php', {
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
                fetch('../post_language.php', {
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
            const t = visibleText[lang];

            document.querySelector(".inventory-table h2").textContent = t.inventoryTitle;
            document.querySelector("#inv-btn").textContent = t.addInventoryBtn;

            document.querySelector(".product-table h2").textContent = t.productsTitle;
            document.querySelector("#add-manual-btn").textContent = t.addProductBtn;

            document.querySelector(".sales-table .header h2").textContent = t.salesTitle;
            document.querySelector("#download-report").textContent = t.downloadReport;

            document.querySelector(".sales-search-first label[for='receipt-date']").textContent = t.dateRange;
            document.querySelector(".sales-search-first label[for='desc']").textContent = t.descending;
            document.querySelector("#receiptBtn").textContent = t.searchBtn;

            const inventoryHeaders = document.querySelectorAll(".inventory-table table thead tr th");
            inventoryHeaders.forEach((th, idx) => th.textContent = t.tableHeaders.inventory[idx]);

            const productsHeaders = document.querySelectorAll(".product-table table thead tr th");
            productsHeaders.forEach((th, idx) => th.textContent = t.tableHeaders.products[idx]);

            const salesHeaders = document.querySelectorAll(".sales-table table thead tr th");
            salesHeaders.forEach((th, idx) => th.textContent = t.tableHeaders.sales[idx]);

            const totalSection = document.querySelector("#total-section p:first-child");
            if (totalSection) totalSection.textContent = t.totalAmount;

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

            document.querySelector(".inventory-table h2").textContent = t.inventoryTitle;
            document.querySelector("#inv-btn").textContent = t.addInventoryBtn;

            document.querySelector(".product-table h2").textContent = t.productsTitle;
            document.querySelector("#add-manual-btn").textContent = t.addProductBtn;

            document.querySelector(".sales-table .header h2").textContent = t.salesTitle;
            document.querySelector("#download-report").textContent = t.downloadReport;

            document.querySelector(".sales-search-first label[for='receipt-date']").textContent = t.dateRange;
            document.querySelector(".sales-search-first label[for='desc']").textContent = t.descending;
            document.querySelector("#receiptBtn").textContent = t.searchBtn;

            const inventoryHeaders = document.querySelectorAll(".inventory-table table thead tr th");
            inventoryHeaders.forEach((th, idx) => th.textContent = t.tableHeaders.inventory[idx]);

            const productsHeaders = document.querySelectorAll(".product-table table thead tr th");
            productsHeaders.forEach((th, idx) => th.textContent = t.tableHeaders.products[idx]);

            const salesHeaders = document.querySelectorAll(".sales-table table thead tr th");
            salesHeaders.forEach((th, idx) => th.textContent = t.tableHeaders.sales[idx]);

            const totalSection = document.querySelector("#total-section p:first-child");
            if (totalSection) totalSection.textContent = t.totalAmount;

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