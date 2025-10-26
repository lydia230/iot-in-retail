document.addEventListener("DOMContentLoaded", () => {
    let emailSent = false;
    const host = "localhost";
    const port = 9001;
    const clientId = "webClient_" + Math.floor(Math.random() * 10000);

    const client = new Paho.MQTT.Client(host, Number(port), clientId);

    client.onConnectionLost = onConnectionLost;
    client.onMessageArrived = onMessageArrived;

    client.connect({ onSuccess: onConnect });

    function onConnect() {
        console.log("onConnect");
      
        client.subscribe("sensor/fridge1");
        client.subscribe("sensor/fridge2");
    }

    function onConnectionLost(responseObject) {
        if (responseObject.errorCode !== 0) {
            console.log("onConnectionLost:" + responseObject.errorMessage);
        }
    }

    function onMessageArrived(message) {
        const data = JSON.parse(message.payloadString);
        const topic = message.destinationName;   

        const msgBox = document.getElementById("console");
        const msgEl = document.createElement("div");
        msgEl.className = "msg";

       
        msgEl.textContent = `[${topic}] Temperature: ${data.temperature} - Humidity: ${data.humidity}`;


        msgBox.appendChild(msgEl);
        msgBox.scrollTop = msgBox.scrollHeight;

        if (data.temperature > 20 && emailSent == false) {
            emailSent = true;

            fetch("send_email.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
                .then(res => res.text())
                .then(response => console.log(response))
                .catch(err => console.error(err));
        }
    }

    setInterval(() => {
        fetch("client_reply.php")
            .then(res => res.text())
            .then(text => {
                let reply;
                try {
                    reply = JSON.parse(text);
                    if (reply.status === "yes") {
                        fetch("fan.php")
                            .then(result => {
                                emailSent = false;
                                console.log("run fan i guess");
                            })
                            .catch(err => {
                                console.error("Error running motor script:", err);
                            });
                    } else if (reply.status === "no") {
                        emailSent = false;
                    }
                } catch (e) {
                    console.error("Failed to parse JSON:", e, text);
                }
            })
            .catch(err => {
                console.error("Error fetching client reply:", err);
            });
    }, 10000);
});
