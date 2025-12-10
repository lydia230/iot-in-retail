<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/checkout.css"> 
    <link rel="stylesheet" href="../nav.css">
    <title>Checkout</title>
</head>

<body>

<?php 
$page_title = "Checkout"; 
include "../nav.php"; 
if (!isset($_SESSION["client_id"])) {
        header("Location: ../login.html");
        exit();
      }
?>

    <main>
        <div class="cart">
            <input type="hidden" id="barcode-input" name="barcode" />

            <di class="products" id="products">
                <div class="details">
                    <div class="item-details">
                        <p>Item</p>
                    </div>
                    <div class="other-details">
                        <p>Quantity</p>
                        <p>Price</p>
                        <p>Delete</p>
                    </div>
                </div>
            </di>

            <div class="price">
                <div class="tax">
                    <p>Subtotal</p>
                    <p id="subtotal-num">$0.00</p>
                </div>
                <div class="tax">
                    <p>Tax</p>
                    <p id="tax-num">14.975%</p>
                </div>
                <div class="tax">
                    <p>Amount due</p>
                    <p style="font-size: 20px; font-weight: 500;" id="total-num">$0.00</p>
                </div>
            </div>
        </div>

        <div class="checkout">
            <div class="info">
                <div class="item">
                    <img id="img" src="item.png" />
                    <p>Search for item</p>
                </div>

                <div class="item">
                    <img id="img" src="points.png" />
                    <p>Search for item</p>
                </div>
            </div>

            <div class="payment" id="payment">
                <p>Payment</p>
            </div>
        </div>
    </main>

    <div id="popup" class="popup hidden">
        <div class="popup-content">
            <h2>Membership Card</h2>
            <p>Add your membership card and start earning reward points today!</p>

            <br>
            <label class="membership">Membership Code:</label>
            <input type="text" class="membership-code" id="membership-code">
            <input type="submit" class="membership-button" id="membership-button"><br>

            <p class="failure" id="failure"></p>
            <button id="proceed">Proceed</button>
        </div>
    </div>

    <div id="payment-popup" class="popup hidden">
        <div class="popup-content">
            <h2>Payment</h2>
            <p>Choose your payment method:</p>

            <div class="payment-options">
                <input type="radio" id="method-card" name="method">
                <label for="method-card">💳 Card</label><br>

                <input type="radio" id="method-cash" name="method">
                <label for="method-cash">💵 Cash</label>

                <p id="radio-error"></p>
            </div>

            <hr>

            <div class="payment-summary">
                <p id="amount-due"></p>
            </div>

            <div class="popup-buttons">
                <button id="confirm-payment">Confirm Payment</button>
                <button id="cancel-payment">Cancel</button>
            </div>
        </div>
    </div>

    <div id="last-popup" class="popup hidden">
        <div class="popup-content">
            <h2>Thank you for your purchase!</h2>
            <p>A receipt was sent to your personal email.</p>
            <button id="finish-process-button" class="finish-process-button">Cancel</button>
        </div>
    </div>

    <div id="receipt-popup" class="popup hidden">
        <div class="popup-content receipt">
            <h1>Market Receipt</h1>
            <p>Thank you for your purchase!</p>

            <div id="receipt-items" class="receipt-items">
                <div id="date-receipt" class="date-receipt">
                    <p id="date-title" class="date-title">Date</p>
                    <p id="date" class="date"></p>
                </div>

                <hr class="solid">

                <div id="date-receipt" class="date-receipt">
                    <p id="receipt-item-name" class="receipt-item-name">Item</p>
                    <div class="receipt-items-details">
                        <p class="receipt-qty">Qty</p>
                        <p class="receipt-price">Price</p>
                    </div>
                </div>

                <div class="receipt-items-content" id="receipt-items-content"></div>

                <hr class="dotted">

                <div id="date-receipt" class="date-receipt">
                    <p>Tax</p>
                    <p>14.975%</p>
                </div>

                <div id="date-receipt" class="date-receipt">
                    <p>Total</p>
                    <p id="receipt-total" class="receipt-total"></p>
                </div>

                <hr class="dotted">

                <div id="date-receipt" class="date-receipt">
                    <p>Points Accumulated</p>
                    <p id="receipt-points" class="receipt-points"></p>
                </div>
            </div>

            <button class="finish-process-button" onclick="window.location.reload()">Close</button>
        </div>
    </div>

    <script src="css/checkout.js"></script>
    <script src="../nav.js"></script>

    <script>
        const langBtn = document.getElementById("current-lang");
        const langMenu = document.getElementById("lang-menu");

        langBtn.addEventListener("click", () => {
            langMenu.classList.toggle("show");
        });

        const visibleText = {
            en: {
                table: { item: "Item", quantity: "Quantity", price: "Price", delete: "Delete" },
                priceSummary: { subtotal: "Subtotal", tax: "Tax", amountDue: "Amount due" },
                search: "Search for item",
                payment: "Payment"
            },
            fr: {
                table: { item: "Article", quantity: "Qté", price: "Prix", delete: "Supprimer" },
                priceSummary: { subtotal: "Sous-total", tax: "Taxe", amountDue: "Montant dû" },
                search: "Rechercher un article",
                payment: "Paiement"
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

                const headers = document.querySelectorAll(".cart .details .other-details p, .cart .details .item-details p");
                if (headers.length === 4) {
                    headers[0].textContent = t.table.item;
                    headers[1].textContent = t.table.quantity;
                    headers[2].textContent = t.table.price;
                    headers[3].textContent = t.table.delete;
                }

                document.querySelector(".cart .price .tax:nth-child(1) p:first-child").textContent = t.priceSummary.subtotal;
                document.querySelector(".cart .price .tax:nth-child(2) p:first-child").textContent = t.priceSummary.tax;
                document.querySelector(".cart .price .tax:nth-child(3) p:first-child").textContent = t.priceSummary.amountDue;

                document.querySelectorAll(".checkout .item p").forEach(p => p.textContent = t.search);

                const paymentEl = document.getElementById("payment");
                if (paymentEl) paymentEl.textContent = t.payment;

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

                const headers = document.querySelectorAll(".cart .details .other-details p, .cart .details .item-details p");
                if (headers.length === 4) {
                    headers[0].textContent = t.table.item;
                    headers[1].textContent = t.table.quantity;
                    headers[2].textContent = t.table.price;
                    headers[3].textContent = t.table.delete;
                }

                document.querySelector(".cart .price .tax:nth-child(1) p:first-child").textContent = t.priceSummary.subtotal;
                document.querySelector(".cart .price .tax:nth-child(2) p:first-child").textContent = t.priceSummary.tax;
                document.querySelector(".cart .price .tax:nth-child(3) p:first-child").textContent = t.priceSummary.amountDue;

                document.querySelectorAll(".checkout .item p").forEach(p => p.textContent = t.search);

                const paymentEl = document.getElementById("payment");
                if (paymentEl) paymentEl.textContent = t.payment;

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