const saveButton = document.getElementById('savePopupBtn');
const cancelButton = document.getElementById('cancelPopupBtn');
const addManual = document.getElementById('add-manual-btn');
const addInventory = document.getElementById('inv-btn');

let saveName = '';

function addProduct(code) {
    const alert = document.getElementById('alert-message');
    const alertNotification = document.getElementById("notification-container");
    fetch("css/add_inventory.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "code=" + encodeURIComponent(code)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.quantity);
                if (data.quantity == 1) {
                    const table = document.getElementById('tbody');
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${data.product.name}</td>
                        <td>${data.product.category}</td>
                        <td>${data.product.price}</td>
                        <td>${data.product.upc}</td>
                        <td>${data.product.epc}</td>
                        <td>${data.product.company}</td>
                        <td>${data.quantity}</td>
                        <td><button class="modify-button" id="modify-button">Modify</button></td>
                        <td><button class="delete-button" id="delete-button">Delete</button></td>
                    `;
                    table.appendChild(row);
                    alertNotification.style.backgroundColor = '#c8e8d1ff';
                    alert.textContent = 'Product added succesfully!';
                } else {
                    const rows = document.getElementsByTagName('tr');

                    for (let i = 1; i < rows.length; i++) {
                        const cells = rows[i].getElementsByTagName('td');

                        if (cells[0].textContent == data.product.name) {
                            cells[6].textContent = data.quantity;
                        }
                    }
                }
            } else {
                alertNotification.style.backgroundColor = '#fcc4c4ff';
                alert.textContent = data.message;
            }
        });
}

function addProductManually() {
    const displayUpc = document.getElementById('popupUPCAdd');
    const displayEpc = document.getElementById('popupEPCAdd');
    const displayName = document.getElementById('popupNameAdd');
    const displayCategory = document.getElementById('popupCategoryAdd');
    const displayPrice = document.getElementById('popupPriceAdd');
    const displayCompany = document.getElementById('popupCompanyAdd');

    if (displayUpc.value.length != 13 || displayEpc.value.length != 24) {
        const invalid = document.getElementById('invalid-add');
        invalid.textContent = 'Invalid UPC or EPC!';
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
        const addEpc = displayEpc.value;
        const addCompany = displayCompany.value;

        const data = {
            name : addName,
            price: parseFloat(addPrice),
            category : addCategory,
            upc : addUpc,
            epc: addEpc,
            company: addCompany
        }
        console.log(data);

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

                    const displayEpc = document.getElementById('popupEPCAdd');
                    displayEpc.value = '';

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
    console.log('in');
    const displayUpc = document.getElementById('popupUPCInv');
    const displayEpc = document.getElementById('popupEPCInv');
    const displayName = document.getElementById('popupNameInv');
    const displayCategory = document.getElementById('popupCategoryInv');
    const displayPrice = document.getElementById('popupPriceInv');
    const displayCompany = document.getElementById('popupCompanyInv');
    const displayQuantity = document.getElementById('popupQuantityInv');

    if (displayUpc.value.length != 13 || displayEpc.value.length != 24) {
        const invalid = document.getElementById('invalid-Inv');
        invalid.textContent = 'Invalid UPC or EPC!';
        invalid.style.backgroundColor = '#fcc4c4ff';
    } else if (displayName.value.length == 0 ||
        displayCategory.value.length == 0 ||
        displayCompany.value.length == 0 ||
        displayPrice.value.length == 0 ||
        displayQuantity.value.length == 0
    ) {
        const invalid = document.getElementById('invalid-Inv');
        invalid.textContent = 'Please fill everything!';
        invalid.style.backgroundColor = '#fcc4c4ff';
    } else if (isNaN(parseFloat(displayPrice.value))) {
        const invalid = document.getElementById('invalid-Inv');
        invalid.textContent = 'Enter a valid price!';
        invalid.style.backgroundColor = '#fcc4c4ff';
    }
     else {
        const addName = displayName.value;
        const addCategory = displayCategory.value;
        const addPrice = displayPrice.value;
        const addUpc = displayUpc.value;
        const addEpc = displayEpc.value;
        const addCompany = displayCompany.value;
        const addQuantity = displayQuantity.value

        const data = {
            name : addName,
            price: parseFloat(addPrice),
            category : addCategory,
            upc : addUpc,
            epc: addEpc,
            company: addCompany,
            quantity: addQuantity
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
                    const displayName = document.getElementById('popupNameAdd');
                    displayName.value = '';

                    const displayCategory = document.getElementById('popupCategoryAdd');
                    displayCategory.value = '';

                    const displayPrice = document.getElementById('popupPriceAdd');
                    displayPrice.value = '';

                    const displayUpc = document.getElementById('popupUPCAdd');
                    displayUpc.value = '';

                    const displayEpc = document.getElementById('popupEPCAdd');
                    displayEpc.value = '';

                    const displayCompany = document.getElementById('popupCompanyAdd');
                    displayCompany.value = '';

                    const invalid = document.getElementById('invalid-Inv');
                    invalid.textContent = 'Product added successfully!';
                    invalid.style.backgroundColor = '#c8e8d1ff';
            } else {
                    const invalid = document.getElementById('invalid-Inv');
                    invalid.textContent = 'Error occured';
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
                        if (data.quantity == 0) {
                            productRow.remove();
                            alertNotification.style.backgroundColor = '#9fc2ce28';
                            alert.textContent = `Product '${data.product.name}' has been removed from the inventory!`;
                        } else {
                            const rows = document.getElementsByTagName('tr');

                            for (let i = 1; i < rows.length; i++) {
                                const cells = rows[i].getElementsByTagName('td');

                                if (cells[0].textContent == data.product.name) {
                                    cells[6].textContent = data.quantity;
                                    alertNotification.style.backgroundColor = '#c8e8d1ff';
                                    alert.textContent = 'Product deleted succesfully!';
                                }
                            }
                        }
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

            saveName = productData[0].textContent;

            const displayName = document.getElementById('popupName');
            displayName.value = productData[0].textContent;

            const displayCategory = document.getElementById('popupCategory');
            displayCategory.value = productData[1].textContent;

            const displayPrice = document.getElementById('popupPrice');
            displayPrice.value = productData[2].textContent;

            const displayUpc = document.getElementById('popupUPC');
            displayUpc.value = productData[3].textContent;

            const displayEpc = document.getElementById('popupEPC');
            displayEpc.value = productData[4].textContent;

            const displayCompany = document.getElementById('popupCompany');
            displayCompany.value = productData[5].textContent;
        } 
    });



});

function updateProcess() {
    if (document.getElementById('popupUPC').value.length == 13
        && document.getElementById('popupEPC').value.length == 24) {
        
        updateProduct = {
            oldName: saveName,
            newName: document.getElementById('popupName').value,
            newCategory: document.getElementById('popupCategory').value,
            newPrice: document.getElementById('popupPrice').value,
            newUpc: document.getElementById('popupUPC').value,
            newEpc: document.getElementById('popupEPC').value,
            newCompany: document.getElementById('popupCompany').value
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
        invalid.textContent = 'Invalid UPC or EPC!';
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