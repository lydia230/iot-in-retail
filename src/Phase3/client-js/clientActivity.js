function calculateActivity() {
    const start = document.getElementById("receipt-date-start").value;
    const end = document.getElementById("receipt-date-end").value
    const table = document.getElementById("activity-body");
    table.innerHTML = '';

    if (start.length == 0 || end.length == 0) {
        return;
    }

    if (start > end) {
       return;
    }

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

    fetch('client-js/client_activity.php', {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "dates=" + encodeURIComponent(JSON.stringify(dates))
    })
        .then(response => response.json())
        .then(data => {

            let row = `
                <tr>
                    <td>${startFormatted} - ${endFormatted}</td>
                    <td>${parseInt(data.new_clients) + parseInt(data.old_clients)}</td>
                    <td>${data.new_clients}</td>
                    <td>${data.old_clients}</td>
                </tr>
            `;

            table.innerHTML += row;
        });
}

function parseLocalDate(str) {
    const [year, month, day] = str.split("-").map(Number);
    return new Date(year, month - 1, day); 
}