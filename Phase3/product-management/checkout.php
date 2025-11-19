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

    <!-- Membership popup -->
    <div id="popup" class="popup hidden">
        <div class="popup-content">
            <h2>Membership Card</h2>
            <p>Add your membership card and start earning reward points today!</p>

            <br>
            <label class="membership">Membership Code:</label>
            <input type="number" step="1" min="0" class="membership-code" id="membership-code">
            <input type="submit" class="membership-button" id="membership-button"><br>

            <p class="failure" id="failure"></p>
            <button id="proceed">Proceed</button>
        </div>
    </div>

    <!-- Payment popup -->
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

    <!-- Final popup -->
    <div id="last-popup" class="popup hidden">
        <div class="popup-content">
            <h2>Thank you for your purchase!</h2>
            <p>A receipt was sent to your personal email.</p>
            <button id="finish-process-button" class="finish-process-button">Cancel</button>
        </div>
    </div>

    <!-- Receipt popup -->
    <div id="receipt-popup" class="popup hidden">
        <div class="popup-content receipt">
            <h1>Market Receipt</h1>
            <p>Thank you for your purchase</p>

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
                    <p>Subtotal</p>
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

    <!-- Language dropdown script -->
    <script>
        const langBtn = document.getElementById("current-lang");
        const langMenu = document.getElementById("lang-menu");

        langBtn.addEventListener("click", () => {
            langMenu.classList.toggle("show");
        });

        const translationsCheckout = {
            en: { title: "Checkout" },
            fr: { title: "Paiement" }
        };

        document.querySelectorAll("#lang-menu button").forEach((btn) => {
            btn.addEventListener("click", () => {
                const lang = btn.dataset.lang;

                document.querySelector("nav h1").textContent = translationsCheckout[lang].title;

                if (lang === "fr") {
                    langBtn.innerHTML = `<img src="https://flagcdn.com/w20/fr.png"><span>Français</span>`;
                } else {
                    langBtn.innerHTML = `<img src="https://flagcdn.com/w20/us.png"><span>English</span>`;
                }

                langMenu.classList.remove("show");
            });
        });

        window.addEventListener("click", (e) => {
            if (!langBtn.contains(e.target) && !langMenu.contains(e.target)) {
                langMenu.classList.remove("show");
            }
        });
    </script>

</body>
</html>
