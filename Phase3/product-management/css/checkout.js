let barcode = '';
let customer;
const pay = document.getElementById('payment');
const code = document.getElementById('membership-code');
const membershipButton = document.getElementById('membership-button');
const popup = document.getElementById('popup');
const proceed = document.getElementById('proceed');
const paymentPopup = document.getElementById('payment-popup');
const method = document.getElementById('method');
const amountDue = document.getElementById('amount-due');
const cancelPayment = document.getElementById('cancel-payment');
const confirmPayment = document.getElementById('confirm-payment');
const lastPopup = document.getElementById('last-popup');
const finalButton = document.getElementById('finish-process-button');
const receiptPopup = document.getElementById('receipt-popup');

function createProduct(name, quantity, price) {
    const subtotal = document.getElementById('subtotal-num');
    const total = document.getElementById('total-num');
    const products = document.getElementById("products");

    const productContainer = document.createElement('div');
    productContainer.classList.add('product');

    const item = document.createElement('div');
    item.classList.add('item-details');

    const productDetails = document.createElement('div');
    productDetails.classList.add('other-details');

    const itemName = document.createElement('p');
    itemName.textContent = `${name}`;

    const detailsQuantity = document.createElement('p');
    detailsQuantity.classList.add('product-indent');
    detailsQuantity.textContent = `${quantity}`;
    const detailsPrice = document.createElement('p');
    detailsPrice.classList.add('product-indent');
    detailsPrice.textContent = `$${price}`;


    const deleteButton = document.createElement('button');
    deleteButton.classList.add('delete-button');
    deleteButton.textContent = 'remove';


    productContainer.appendChild(item);
    productContainer.appendChild(productDetails);

    item.appendChild(itemName);
    productDetails.appendChild(detailsQuantity);
    productDetails.appendChild(detailsPrice);
    productDetails.appendChild(deleteButton);
    products.appendChild(productContainer);

    deleteButton.addEventListener('click', () => {
         let currentQuantity = parseInt(detailsQuantity.textContent);

        if (currentQuantity > 1) {
            // decrease quantity by 1
            currentQuantity -= 1;
            detailsQuantity.textContent = currentQuantity;

            // update subtotal & total
            const subtotalValue = parseFloat(subtotal.textContent.replace('$', '')) - price;
            subtotal.textContent = `$${subtotalValue.toFixed(2)}`;
            const totalValue = calculateTotal(subtotalValue);
            total.textContent = `$${totalValue.toFixed(2)}`;
        } else {
            // if quantity = 1 → remove the whole product
            productContainer.remove();

            const subtotalValue = parseFloat(subtotal.textContent.replace('$', '')) - price;
            subtotal.textContent = `$${subtotalValue.toFixed(2)}`;
            const totalValue = calculateTotal(subtotalValue);
            total.textContent = `$${totalValue.toFixed(2)}`;
        }
    });
}

function createReceipt(item, quantity, price) {
    const receiptContainer = document.getElementById('receipt-items-content');

    const receiptRow = document.createElement('div');
    receiptRow.classList.add('date-receipt');

    const receiptItemName = document.createElement('p');
    receiptItemName.textContent = item;

    const receiptItemDetails = document.createElement('div');
    receiptItemDetails.classList.add('receipt-items-details');

    const receiptQuantity = document.createElement('p');
    receiptQuantity.textContent = quantity;

    const receiptTotal = document.createElement('p');
    receiptTotal.textContent = parseFloat(quantity * price).toFixed(2);

    receiptItemDetails.appendChild(receiptQuantity);
    receiptItemDetails.appendChild(receiptTotal);

    receiptRow.appendChild(receiptItemName);
    receiptRow.appendChild(receiptItemDetails);
    receiptContainer.appendChild(receiptRow);
}

function calculateSubTotal(quantity, unitPrice) {
    return quantity * unitPrice;
}

function calculateTotal(subtotal) {
    const percentage = 0.14975;
    const tax = subtotal * percentage
    return subtotal + tax;
}


document.addEventListener('keydown', function (event) {
    if (event.key.length === 1) {
        barcode += event.key;
    } else if (event.key === 'Enter') {
        fetch("css/product_existence.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "code=" + encodeURIComponent(barcode)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const subtotal = document.getElementById('subtotal-num');
                    const total = document.getElementById('total-num');
                    const rows = document.getElementsByClassName('item-details');
                    let found = false;

                    for (let i = 0; i < rows.length; i++) {
                        const nameElement = rows[i].getElementsByTagName('p')[0];
                        if (nameElement && nameElement.textContent === data.product.name) {

                            const parentContainer = rows[i].parentNode;
                            const itemDetails = parentContainer.getElementsByClassName('other-details')[0];
                            const details = itemDetails.getElementsByTagName('p');

                            details[0].textContent = parseInt(details[0].textContent) + 1;

                            subtotal.textContent = `$${calculateSubTotal(parseInt(details[0].textContent), data.product.price).toFixed(2)}`
                            const subtotalValue = parseFloat(subtotal.textContent.replace('$', ''));
                            const totalValue = calculateTotal(subtotalValue);
                            total.textContent = `$${totalValue.toFixed(2)}`;
                            found = true;
                            break;
                        }
                    }

                    if (!found) {
                        createProduct(data.product.name, 1, data.product.price);
                        subtotal.textContent = `$${calculateSubTotal(1, data.product.price).toFixed(2)}`
                        const subtotalValue = parseFloat(subtotal.textContent.replace('$', ''));
                        const totalValue = calculateTotal(subtotalValue);
                        total.textContent = `$${totalValue.toFixed(2)}`;
                    }

                }
            });
        barcode = '';
    }

});


// ----------------------- Final checkout process -----------------------
pay.addEventListener('click', () => {
    const itemRows = document.getElementsByClassName('product');
    if (itemRows.length > 0) {
        popup.classList.remove('hidden');
    }
});

membershipButton.addEventListener('click', () => {
    const invalidMembership = document.getElementById('failure');
    const membershipCode = code.value;

    if (membershipCode.length != 0) {
        fetch("css/membership_verification.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "membershipCode=" + encodeURIComponent(membershipCode)
        })
            .then(response => response.json())
            .then(data => {

                if (data.success) {
                    customer = data.customer;
                    invalidMembership.textContent = 'Valid membership card!';
                    invalidMembership.style.color = '#a7e87cff';
                } else {
                    invalidMembership.textContent = data.message;
                    invalidMembership.style.color = '#e87c7c';
                }
            });
    } else {
        invalidMembership.textContent = 'Enter membership code!';
        invalidMembership.style.color = '#e87c7c';
    }
});

proceed.addEventListener('click', () => {
    popup.classList.add('hidden');
    paymentPopup.classList.remove('hidden');
    const amount = document.getElementById('total-num');
    amountDue.textContent = `Amount Due: ${amount.textContent}`;
});

cancelPayment.addEventListener('click', () => {
    paymentPopup.classList.add('hidden');
});

confirmPayment.addEventListener('click', async () => {
    const selectedRadio = document.querySelector('input[name="method"]:checked');

    if (selectedRadio) {
        if (customer != undefined) {
            const customer_id = customer['customer_id'];

            let points = 0;
            let resPoints = await fetch("css/add_checkout.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "customer_id=" + encodeURIComponent(customer_id)
            });
            let pointsData = await resPoints.json();
            if (pointsData.success) points = pointsData.points;

            const amount = document.getElementById('total-num');
            const final_total = amount.textContent.replace('$', '');
            console.log(final_total);
            const receiptInfo = {
                customer_id: customer_id,
                points: points,
                amount: final_total
            };

            let resReceipt = await fetch("css/create_receipt.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "receiptInfo=" + encodeURIComponent(JSON.stringify(receiptInfo))
            });
            let receiptData = await resReceipt.json();
            const receipt_id = receiptData.receipt_id;
            console.log(receipt_id);

            const itemRows = document.getElementsByClassName('product');

            for (let i = 0; i < itemRows.length; i++) {
                const itemContainer = itemRows[i].getElementsByClassName('item-details')[0];
                const name = itemContainer.getElementsByTagName('p')[0].textContent;

                const detailsContainer = itemRows[i].getElementsByClassName('other-details')[0];
                const quantity = parseInt(detailsContainer.getElementsByTagName('p')[0].textContent);


                let resProduct = await fetch("css/search_product.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "name=" + encodeURIComponent(name)
                });
                let productData = await resProduct.json();
                const item_id = productData.product['product_id'];
                const price = parseFloat(productData.product['price']);

                const receiptItem = {
                    receipt_id: receipt_id,
                    product_id: item_id,
                    quantity: quantity,
                    price: price,
                    total: quantity * price
                };

                await fetch("css/receipt_items.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "receiptItems=" + encodeURIComponent(JSON.stringify(receiptItem))
                });

                let emailReceipt = {
                    total : amountDue.textContent.replace('Amount Due: $', ''),
                    points: 3,
                    email : customer['email'],
                    receipt_id : receipt_id,
                    items : []
                };
                
                const itemRows2 = document.getElementsByClassName('product');

                for (let i = 0; i < itemRows.length; i++) {
                    const itemContainer2 = itemRows2[i].getElementsByClassName('item-details')[0];
                    const name2 = itemContainer2.getElementsByTagName('p')[0].textContent;

                    const detailsContainer2 = itemRows2[i].getElementsByClassName('other-details')[0];
                    const quantity2 = parseInt(detailsContainer2.getElementsByTagName('p')[0].textContent);


                    let resProduct2 = await fetch("css/search_product.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "name=" + encodeURIComponent(name2)
                    });
                    let productData2 = await resProduct2.json();
                    const price2 = parseFloat(productData2.product['price']);

                   emailReceipt.items.push({name : name2, quantity : quantity2, price : price2});
                }
                console.log(emailReceipt.items);
                console.log(emailReceipt);
                let sentEmailResp = await fetch("css/send_receipt.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "emailReceipt=" + encodeURIComponent(JSON.stringify(emailReceipt))
                });

                let receiptEmail = await sentEmailResp.json();
                console.log(receiptEmail.message);

                paymentPopup.classList.add('hidden');
                lastPopup.classList.remove('hidden');
            }
        } else {
            const receiptPoints = document.getElementById('receipt-points');
            receiptPoints.textContent = 0;

            const date = document.getElementById('date');
            const now = new Date();
            const formatted = now.getFullYear() + '-' +
                String(now.getMonth() + 1).padStart(2, '0') + '-' +
                String(now.getDate()).padStart(2, '0') + ' ' +
                String(now.getHours()).padStart(2, '0') + ':' +
                String(now.getMinutes()).padStart(2, '0') + ':' +
                String(now.getSeconds()).padStart(2, '0');
            date.textContent = formatted;

            const receiptTotal = document.getElementById('receipt-total');
            receiptTotal.textContent = amountDue.textContent.replace('Amount Due: ', '');

            const itemRows = document.getElementsByClassName('product');

            for (let i = 0; i < itemRows.length; i++) {
                const itemContainer = itemRows[i].getElementsByClassName('item-details')[0];
                const name = itemContainer.getElementsByTagName('p')[0].textContent;

                const detailsContainer = itemRows[i].getElementsByClassName('other-details')[0];
                const quantity = parseInt(detailsContainer.getElementsByTagName('p')[0].textContent);


                let resProduct = await fetch("css/search_product.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "name=" + encodeURIComponent(name)
                });
                let productData = await resProduct.json();
                const price = parseFloat(productData.product['price']);

                createReceipt(name, quantity, price);

            }

            paymentPopup.classList.add('hidden');
            receiptPopup.classList.remove('hidden');
        }
    } else {
        const radioError = document.getElementById('radio-error');
        radioError.textContent = 'Select a payment method!';
        radioError.style.color = '#e87c7c';
    }


});


finalButton.addEventListener('click', () => {
    window.location.reload();
})
