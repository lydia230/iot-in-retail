const saveButton = document.getElementById('savePopupBtn');
const cancelButton = document.getElementById('cancelPopupBtn');
const addManual = document.getElementById('add-manual-btn');
const addInventory = document.getElementById('inv-btn');
let downloadCurrentTable = {};

let saveEpc = '';


function addProductManually() {
    const displayUpc = document.getElementById('popupUPCAdd');
    const displayName = document.getElementById('popupNameAdd');
    const displayCategory = document.getElementById('popupCategoryAdd');
    const displayPrice = document.getElementById('popupPriceAdd');
    const displayCompany = document.getElementById('popupCompanyAdd');

    if (displayUpc.value.length != 13) {
        const invalid = document.getElementById('invalid-add');
        invalid.textContent = 'Invalid UPC!';
        invalid.style.backgroundColor = '#fcc4c4ff';
    } else if (displayName.value.length == 0 ||
        displayCategory.value.length == 0 ||
        displayCompany.value.length == 0 ||
        displayPrice.value.length == 0
    ) {
        const invalid = document.getElementById('invalid-add');
        invalid.textContent = 'Please fill everything!';
    } else if (isNaN(parseFloat(displayPrice.value))) {
        const invalid = document.getElementById('invalid-add');
        invalid.textContent = 'Enter a valid price!';
    }
    else {
        const addName = displayName.value;
        const addCategory = displayCategory.value;
        const addPrice = displayPrice.value;
        const addUpc = displayUpc.value;
        const addCompany = displayCompany.value;

        const data = {
            name: addName,
            price: parseFloat(addPrice),
            category: addCategory,
            upc: addUpc,
            company: addCompany
        }

        fetch("css/add_product.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "data=" + encodeURIComponent(JSON.stringify(data))
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const displayName = document.getElementById('popupNameAdd');
                    displayName.value = '';

                    const displayCategory = document.getElementById('popupCategoryAdd');
                    displayCategory.value = '';

                    const displayPrice = document.getElementById('popupPriceAdd');
                    displayPrice.value = '';

                    const displayUpc = document.getElementById('popupUPCAdd');
                    displayUpc.value = '';

                    const displayCompany = document.getElementById('popupCompanyAdd');
                    displayCompany.value = '';

                    const invalid = document.getElementById('invalid-add');
                    invalid.textContent = 'Product updated successfully!';
                    invalid.style.backgroundColor = '#c8e8d1ff';
                } else {
                    const invalid = document.getElementById('invalid-add');
                    invalid.textContent = 'Error occured!';
                    invalid.style.backgroundColor = '#fcc4c4ff';
                }
            });
    }
}

function addInventoryManually() {
    const displayEpc = document.getElementById('popupEPCInv');
    const displayCategory = document.getElementById('popupCategoryInv');

    if (displayEpc.value.length != 24) {
        const invalid = document.getElementById('invalid-Inv');
        invalid.textContent = 'Invalid EPC!';
        invalid.style.backgroundColor = '#fcc4c4ff';
    } else {
        const addCategory = displayCategory.value;
        const addEpc = displayEpc.value;

        const data = {
            category: addCategory,
            epc: addEpc,
        }

        fetch("css/add_inventory_manual.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "data=" + encodeURIComponent(JSON.stringify(data))
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.quantity);

                    displayEpc.value = '';

                    const invalid = document.getElementById('invalid-Inv');
                    invalid.textContent = 'Product added successfully!';
                    invalid.style.backgroundColor = '#c8e8d1ff';
                } else {
                    const invalid = document.getElementById('invalid-Inv');
                    invalid.textContent = 'Duplicate Item!';
                    invalid.style.backgroundColor = '#fcc4c4ff';
                }
            });
    }
}



document.addEventListener('DOMContentLoaded', () => {
    const alert = document.getElementById('alert-message');
    const alertNotification = document.getElementById("notification-container");
    const table = document.getElementById('table');

    table.addEventListener('click', (event) => {
        if (event.target.classList.contains('delete-button')) {
            const productRow = event.target.parentNode.parentNode;
            const productData = productRow.getElementsByTagName('td');

            fetch("css/delete_inventory.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "epc=" + encodeURIComponent(productData[4].textContent)
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data.quantity)
                    if (data.success) {
                        productRow.remove();
                        alertNotification.style.backgroundColor = '#c8e8d1ff';
                        alert.textContent = `Product has been removed from the inventory!`;

                    } else {
                        alertNotification.style.backgroundColor = '#fcc4c4ff';
                        alert.textContent = data.message;
                    }
                });

        } else if (event.target.classList.contains('modify-button')) {
            const editor = document.getElementById('popup');
            editor.classList.remove('hidden');

            const productRow = event.target.parentNode.parentNode;
            const productData = productRow.getElementsByTagName('td');

            const displayName = document.getElementById('popupName');
            displayName.value = productData[0].textContent;

            const displayCategory = document.getElementById('popupCategory');
            displayCategory.value = productData[1].textContent;

            const displayPrice = document.getElementById('popupPrice');
            displayPrice.value = productData[2].textContent;

            const displayUpc = document.getElementById('popupUPC');
            displayUpc.value = productData[3].textContent;

            saveEpc = productData[4].textContent;

            const displayEpc = document.getElementById('popupEPC');
            displayEpc.value = productData[4].textContent;

            const displayCompany = document.getElementById('popupCompany');
            displayCompany.value = productData[5].textContent;
        }
    });



});

function updateProcess() {
    if (document.getElementById('popupEPC').value.length == 24) {

        updateProduct = {
            oldEpc: saveEpc,
            newEpc: document.getElementById('popupEPC').value,
        };

        console.log(updateProduct);
        fetch("css/update_product.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "updateProduct=" + encodeURIComponent(JSON.stringify(updateProduct))
        })
            .then(response => response.json())
            .then(data => {
                if (data.success == true) {
                    const invalid = document.getElementById('invalid-update');
                    invalid.textContent = 'Product updated successfully!';
                    invalid.style.backgroundColor = '#c8e8d1ff';
                }
            });
    } else {
        const invalid = document.getElementById('invalid-update');
        invalid.textContent = 'Invalid EPC!';
        invalid.style.backgroundColor = '#fcc4c4ff';
    }
}

function cancelProcess() {
    window.location.reload();
}

addManual.addEventListener('click', () => {
    const addManual = document.getElementById('popup-add');
    addManual.classList.remove('hidden');
});

addInventory.addEventListener('click', () => {
    const addInv = document.getElementById('popup-inventory');
    addInv.classList.remove('hidden');
});


function viewSales() {
    const alert = document.getElementById('alert-message');
    const alertNotification = document.getElementById("notification-container");

    const revenuDisplay = document.getElementById("total-amount");
    const start = document.getElementById("receipt-date-start").value;
    const end = document.getElementById("receipt-date-end").value
    const table = document.getElementById("tbody-sales");
    table.innerHTML = '';

    if (start.length == 0 || end.length == 0) {
        alertNotification.style.backgroundColor = '#fcc4c4ff';
        alert.textContent = "Error - Please fill both date inputs!";
        return;
    }

    if (start > end) {
        alertNotification.style.backgroundColor = '#fcc4c4ff';
        alert.textContent = "Error - Please provide a valid date range!";
        return;
    }
    alertNotification.style.backgroundColor = '#1372e671';
    alert.textContent = "Scan products to update the inventory.";

    let startDate = parseLocalDate(start);
    let endDate = parseLocalDate(end);

    let startFormatted = startDate.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric"
    });

    let endFormatted = endDate.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric"
    });

    let dates = {
        start: `${start} 00:00:00`,
        end: `${end} 23:59:59`
    }

    fetch('css/sold_items.php', {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "dates=" + encodeURIComponent(JSON.stringify(dates))
    })
        .then(response => response.json())
        .then(data => {
            let revenu = 0;
            if (data.sales != null) {
                for (let key in data.sales) {
                    console.log(key);
                    let row = `
                    <tr>
                        <td>${key}</td>
                        <td>${data.sales[key]['quantity']}</td>
                        <td>${startFormatted} - ${endFormatted}</td>
                    </tr>
                    `;
                    revenu += data.sales[key]['revenu'];
                    table.innerHTML += row;
                }
                downloadCurrentTable = data.sales;

            }
            revenuDisplay.textContent = `$${revenu.toFixed(2)}`;
            console.log(data.sales);

        })
}

function onSortCheck() {
    const isChecked = document.getElementById("desc").checked;
    sortTableByQuantityCheckbox(1, isChecked);
}

function sortTableByQuantityCheckbox(quantityColIndex, ascending) {
    const tbody = document.getElementById("tbody-sales");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    rows.sort((a, b) => {
        const valA = parseFloat(a.children[quantityColIndex].innerText) || 0;
        const valB = parseFloat(b.children[quantityColIndex].innerText) || 0;

        return ascending ? valA - valB : valB - valA;
    });

    rows.forEach(row => tbody.appendChild(row));
}

function downloadReport() {
    const alert = document.getElementById('alert-message');
    const alertNotification = document.getElementById("notification-container");
    const start = document.getElementById("receipt-date-start").value;
    const end = document.getElementById("receipt-date-end").value
    console.log(isNaN(end));
    console.log(start == undefined);
    console.log(start);
    if (start.length == 0 || end.length == 0) {
        alertNotification.style.backgroundColor = '#fcc4c4ff';
        alert.textContent = "Error - Please fill both date inputs!";
        return;
    }

    if (start > end) {
        alertNotification.style.backgroundColor = '#fcc4c4ff';
        alert.textContent = "Error - Please provide a valid date range!";
        return;
    }
    alertNotification.style.backgroundColor = '#1372e671';
    alert.textContent = "Scan products to update the inventory.";


    const data = [
        ['PurchaseID', 'ClientID', 'Total', 'ProductID', 'Date', 'Name', 'Quantity', 'Revenue', 'TotalQuantity', 'TotalRevenue'] // header
    ];

    for (let product in downloadCurrentTable) {
        console.log(product);
        const productData = downloadCurrentTable[product];

        const totalQuantity = productData.quantity || 0;
        const totalRevenue = productData.revenu.toFixed(2) || 0;

        for (let key in productData) {
            if (!['quantity', 'revenu'].includes(key)) { // include all purchase rows
                const row = [...productData[key], totalQuantity, totalRevenue];
                data.push(row);
            }
        }
    }

    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(data);
    ws['!cols'] = [
        { wch: 10 },
        { wch: 10 },
        { wch: 10 },
        { wch: 12 },
        { wch: 20 },
        { wch: 15 },
        { wch: 10 },
        { wch: 12 },
        { wch: 14 },
        { wch: 14 }
    ];
    XLSX.utils.book_append_sheet(wb, ws, "Sheet 1");

    XLSX.writeFile(wb, `sales_report_${start}_${end}.xlsx`);
}


function parseLocalDate(str) {
    const [year, month, day] = str.split("-").map(Number);
    return new Date(year, month - 1, day);
}

function displayInventory() {
    const drop = document.getElementById("popupCategoryInv");

    fetch("css/display_products.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
    })
        .then(response => response.json())
        .then(data => {

            let option = '';
            console.log(data.products);

            for (let i = 0; i < data.products.length; i++) {
                option += `
                <option value="${data.products[i][1]}">
                    ${data.products[i][1]}
                </option>
            `;
            }

            console.log(option);
            drop.innerHTML = option;
        });
}


displayInventory();

function displayProducts() {
    const table = document.getElementById('tbody-products');

    fetch('css/display_products.php', {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
    })
        .then(response => response.json())
        .then(data => {
            let row = '';
            for (let i = 0; i < data.products.length; i++) {
                row += `
                <tr>
                    <td>${data.products[i][0]}</td>
                    <td>${data.products[i][1]}</td>
                    <td>${data.products[i][2]}</td>
                    <td>${data.products[i][3]}</td>
                    <td>${data.products[i][4]}</td>
                    <td>${data.products[i][5]}</td>
                </tr>
            `;
            }

            table.innerHTML = row;
        });
}

displayProducts();

