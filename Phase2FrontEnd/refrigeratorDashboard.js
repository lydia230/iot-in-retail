let tempGauge1, humGauge1, tempGauge2, humGauge2;
let fanDesign;  // persistent fan gauge
let isFanON = false;


async function createDashboards() {
    if (!tempGauge1) {  // Only create if not already created
        // Temperature 1
        tempGauge1 = pureknob.createKnob(500, 300);
        tempGauge1.setProperty('angleStart', -0.5 * Math.PI);
        tempGauge1.setProperty('angleEnd', 0.5 * Math.PI);
        tempGauge1.setProperty('colorFG', '#00a6ffff');
        tempGauge1.setProperty('colorBG', '#08043fff');
        tempGauge1.setProperty('valMax', 30);
        tempGauge1.setProperty('label', 'Temperature 1');
        tempGauge1.setProperty('colorLabel', '#00a6ffff');
        tempGauge1.setProperty('readonly', true);
        document.getElementById('temp1').appendChild(tempGauge1.node());
    }

    if (!humGauge1) {
        // Humidity 1
        humGauge1 = pureknob.createKnob(500, 300);
        humGauge1.setProperty('angleStart', -0.5 * Math.PI);
        humGauge1.setProperty('angleEnd', 0.5 * Math.PI);
        humGauge1.setProperty('colorFG', '#ee00ffff');
        humGauge1.setProperty('colorBG', '#08043fff');
        humGauge1.setProperty('valMax', 100);
        humGauge1.setProperty('label', 'Humidity 1');
        humGauge1.setProperty('colorLabel', '#ee00ffff');
        humGauge1.setProperty('readonly', true);
        document.getElementById('hum1').appendChild(humGauge1.node());
    }

    if (!tempGauge2) {
        // Temperature 2
        tempGauge2 = pureknob.createKnob(500, 300);
        tempGauge2.setProperty('angleStart', -0.5 * Math.PI);
        tempGauge2.setProperty('angleEnd', 0.5 * Math.PI);
        tempGauge2.setProperty('colorFG', '#00ffb3ff');
        tempGauge2.setProperty('colorBG', '#08043fff');
        tempGauge2.setProperty('valMax', 30);
        tempGauge2.setProperty('label', 'Temperature 2');
        tempGauge2.setProperty('colorLabel', '#00ffb3ff');
        tempGauge2.setProperty('readonly', true);
        document.getElementById('temp2').appendChild(tempGauge2.node());
    }

    if (!humGauge2) {
        // Humidity 2
        humGauge2 = pureknob.createKnob(500, 300);
        humGauge2.setProperty('angleStart', -0.5 * Math.PI);
        humGauge2.setProperty('angleEnd', 0.5 * Math.PI);
        humGauge2.setProperty('colorFG', '#ff8800ff');
        humGauge2.setProperty('colorBG', '#08043fff');
        humGauge2.setProperty('valMax', 100);
        humGauge2.setProperty('label', 'Humidity 2');
        humGauge2.setProperty('colorLabel', '#ff8800ff');
        humGauge2.setProperty('readonly', true);
        document.getElementById('hum2').appendChild(humGauge2.node());
    }

    if (!fanDesign) {
        fanDesign = pureknob.createKnob(600, 300);
        fanDesign.setProperty('angleStart', -0.75 * Math.PI);
        fanDesign.setProperty('angleEnd', 0.75 * Math.PI);
        fanDesign.setValue(0);
        fanDesign.setProperty('valMax', 0);
        fanDesign.setProperty('colorFG', '#ff0000ff');
        fanDesign.setProperty('colorLabel', '#ff0000ff');
        fanDesign.setProperty('label', 'Fan Off');
        fanDesign.setProperty('readonly', true);
        document.getElementById('fanInfo').appendChild(fanDesign.node());
    }
}

function manuallyControlFan() {
    const button = document.getElementById('fanButton');
    const fanInfo = document.getElementById('fanInfo');
    fanInfo.innerHTML = '';

    const fan = pureknob.createKnob(600, 300);
    fan.setProperty('angleStart', -0.75 * Math.PI);
    fan.setProperty('angleEnd', 0.75 * Math.PI);
    fan.setProperty('readonly', true);

    if (button.textContent === 'Manually Turn On') {
        button.textContent = 'Manually Turn Off';
        fan.setValue(100);
        fan.setProperty('colorFG', '#22ff00ff');
        fan.setProperty('colorLabel', '#22ff00ff');
        fan.setProperty('label', 'Fan On');
        fetch("fan_on.php")
            .then(() => {})
            .catch(err => console.error("Error running fan:", err));
    } else {
        button.textContent = 'Manually Turn On';
        fan.setValue(0);
        fan.setProperty('colorFG', '#ff0000ff');
        fan.setProperty('colorLabel', '#ff0000ff');
        fan.setProperty('label', 'Fan Off');
        fetch("fan_off.php")
            .then(() => {})
            .catch(err => console.error("Error running fan:", err));
    }

    fanInfo.appendChild(fan.node());
}

function setFanGauge(on) {
    isFanON = on;
    if (on) {
        fanDesign.setValue(100);
        fanDesign.setProperty('colorFG', '#22ff00ff'); // green
        fanDesign.setProperty('colorLabel', '#22ff00ff');
        fanDesign.setProperty('label', 'Fan On');
    } else {
        fanDesign.setValue(0);
        fanDesign.setProperty('colorFG', '#ff0000ff'); // red
        fanDesign.setProperty('colorLabel', '#ff0000ff');
        fanDesign.setProperty('label', 'Fan Off');
    }
}

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

        if (topic === "sensor/fridge1") {
            tempGauge1.setValue(data.temperature);
            humGauge1.setValue(data.humidity);
        } else if (topic === "sensor/fridge2") {
            tempGauge2.setValue(data.temperature);
            humGauge2.setValue(data.humidity);
        }

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
                        // fan should run
                        fetch("fan.php")
                            .then(() => {
                                setFanGauge(true); // turn gauge green
                                console.log("Fan started");
                            })
                            .then(() => {
                                emailSent = false;
                                setFanGauge(false);
                            })
                            .catch(err => console.error("Error running fan:", err));
                    } else if (reply.status === "no") {
                        setFanGauge(false); // turn gauge red
                        console.log("Fan stopped");
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

