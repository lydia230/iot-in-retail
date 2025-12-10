let loadRows = [];

window.addEventListener("DOMContentLoaded", () => {
    const table = document.getElementById("receipts");
    const rows = table.querySelectorAll("tr");
    rows.forEach(row => loadRows.push(row.cloneNode(true)));

    const cancelBtn = document.getElementById('cancelPopupBtn');
    cancelBtn.addEventListener('click', closeDetails);

    const receiptBtn = document.getElementById('receiptBtn');
    receiptBtn.addEventListener('click', searchReceiptDate);

    const itemBtn = document.getElementById('itemBtn');
    itemBtn.addEventListener('click', () => {
        const clientId = parseInt(itemBtn.dataset.client); 
        searchItem(clientId);
    });

    calculateTotal();
});

function searchReceiptDate() {
    const errorContainer = document.getElementById("errorSearch");
    const error = document.getElementById("errorSearchMessage"); 
    const start = new Date(document.getElementById("receipt-date-start").value);
    const end = new Date(document.getElementById("receipt-date-end").value);
    const table = document.getElementById("receipts");
    let result = [];

    if (isNaN(start) || isNaN(end)) {
        error.textContent = "Error: Please fill both date inputs!";
        errorContainer.style.backgroundColor = "#e87c7cb0";
        return;
    }
    if (start > end) {
        error.textContent = "Error: Please provide a valid date range!";
        errorContainer.style.backgroundColor = "#e87c7cb0";
        return;
    }

    error.textContent = "";
    errorContainer.style.backgroundColor = "transparent";

    loadRows.forEach(row => {
        const textDate = row.querySelector("td").textContent.split(" ")[0];
        const rowDate = new Date(textDate);
        if (rowDate >= start && rowDate <= end) result.push(row.cloneNode(true));
    });

    table.innerHTML = "";
    if (result.length > 0) {
        result.forEach(r => table.appendChild(r));
        calculateTotal();
    }
}

function calculateTotal() {
    const table = document.getElementById("receipts");
    const total = document.getElementById('total-amount');
    let sum = 0;

    const rows = table.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        const td = rows[i].getElementsByTagName('td')[3];
        if (td) sum += parseFloat(td.textContent.substring(1));
    }

    total.textContent = `$${sum.toFixed(2)}`;
}

function displayProducts(button) {
    const container = document.getElementById("productContainer");
    container.innerHTML = '';
    const row = button.closest("tr");
    const receipt_id = row.getElementsByTagName("td")[1].textContent.substring(1);

    fetch("client-js/receipt_history.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "receipt_id=" + encodeURIComponent(receipt_id)
    })
    .then(response => response.json())
    .then(data => {
        if (data.length !== 0) {
            data.receip_products.forEach(item => {
                const element = document.createElement('div');
                element.classList.add('element');

                const nameP = document.createElement('p');
                nameP.textContent = item[7];
                nameP.classList.add('separate');

                const quantityP = document.createElement('p');
                quantityP.textContent = `${item[3]} items`;
                quantityP.classList.add('separate');

                const unitP = document.createElement('p');
                unitP.textContent = `$${item[4]}`;
                unitP.classList.add('separate');

                const totalP = document.createElement('p');
                totalP.textContent = `$${item[5]}`;
                totalP.classList.add('separate');

                element.append(nameP, quantityP, unitP, totalP);
                container.appendChild(element);
            });
            document.getElementById('popup').classList.remove('hidden');
        }
    });
}

function closeDetails() {
    document.getElementById("productContainer").innerHTML = '';
    document.getElementById('popup').classList.add('hidden');
}

function searchItem(client) {
    const table = document.getElementById("search-content");
    const item = document.getElementById('item-input').value.trim();
    if (!item) return;

    const data = { client_id: client, item: item };

    fetch("client-js/search_item.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "data=" + encodeURIComponent(JSON.stringify(data))
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.length !== 0) {
            let details = data.items.map(el => `Date: ${el[1]} - Unit Price: $${el[6]}<br>`).join('');
            const row = `
                <tr>
                    <td>${data.items[0][8]}</td>
                    <td>${data.items.length} times</td>
                    <td>${details}</td>
                </tr>`;
            table.innerHTML = row;
        }
    });
}
