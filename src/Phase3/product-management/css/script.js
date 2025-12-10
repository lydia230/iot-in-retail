let thresholds = [];
let warn_image = '';
// TEMPERATURE
var dom1 = document.getElementById('chart-container-1');
var myChart1 = echarts.init(dom1, null, {
    renderer: 'canvas',
    useDirtyRect: false
});

var dom2 = document.getElementById('chart-container-2');
var myChart2 = echarts.init(dom2, null, {
    renderer: 'canvas',
    useDirtyRect: false
});

// HUMIDITY
var dom3 = document.getElementById('chart-container-3');
var myChart3 = echarts.init(dom3, null, {
    renderer: 'canvas',
    useDirtyRect: false
});

var dom4 = document.getElementById('chart-container-4');
var myChart4 = echarts.init(dom4, null, {
    renderer: 'canvas',
    useDirtyRect: false
});

var app = {};

var option1;
var option2;

option1 = {
    series: [
        {
            type: 'gauge',
            center: ['50%', '60%'],
            startAngle: 200,
            endAngle: -20,
            min: 0,
            max: 60,
            splitNumber: 12,
            itemStyle: {
                color: '#082785ff'
            },
            progress: {
                show: true,
                width: 30
            },
            pointer: {
                show: false
            },
            axisLine: {
                lineStyle: {
                    width: 30
                }
            },
            axisTick: {
                distance: -40,
                splitNumber: 5,
                lineStyle: {
                    width: 2,
                    color: '#000000ff'
                }
            },
            splitLine: {
                distance: -49,
                length: 14,
                lineStyle: {
                    width: 3,
                    color: 'rgba(0, 0, 0, 1)'
                }
            },
            axisLabel: {
                distance: 5,
                color: '#000000ff',
                fontSize: 8
            },
            anchor: {
                show: false
            },
            title: {
                show: false
            },
            detail: {
                valueAnimation: true,
                width: '60%',
                lineHeight: 40,
                borderRadius: 8,
                offsetCenter: [0, '-15%'],
                fontSize: 12,
                fontWeight: 'bolder',
                formatter: '{value} °C',
                color: 'auto'
            },
            data: [
                {
                    value: 0
                }
            ]
        },
        {
            type: 'gauge',
            center: ['50%', '60%'],
            startAngle: 200,
            endAngle: -20,
            min: 0,
            max: 60,
            itemStyle: {
                color: '#4790fdff'
            },
            progress: {
                show: true,
                width: 8
            },
            pointer: {
                show: false
            },
            axisLine: {
                show: false
            },
            axisTick: {
                show: false
            },
            splitLine: {
                show: false
            },
            axisLabel: {
                show: false
            },
            detail: {
                show: false
            },
            data: [
                {
                    value: 0
                }
            ]
        }
    ]
};

option2 = {
    series: [
        {
            type: 'gauge',
            center: ['50%', '60%'],
            startAngle: 200,
            endAngle: -20,
            min: 0,
            max: 60,
            splitNumber: 12,
            itemStyle: {
                color: '#082785ff'
            },
            progress: {
                show: true,
                width: 30
            },
            pointer: {
                show: false
            },
            axisLine: {
                lineStyle: {
                    width: 30
                }
            },
            axisTick: {
                distance: -40,
                splitNumber: 5,
                lineStyle: {
                    width: 2,
                    color: '#000000ff'
                }
            },
            splitLine: {
                distance: -49,
                length: 14,
                lineStyle: {
                    width: 3,
                    color: 'rgba(0, 0, 0, 1)'
                }
            },
            axisLabel: {
                distance: 5,
                color: '#000000ff',
                fontSize: 8
            },
            anchor: {
                show: false
            },
            title: {
                show: false
            },
            detail: {
                valueAnimation: true,
                width: '60%',
                lineHeight: 40,
                borderRadius: 8,
                offsetCenter: [0, '-15%'],
                fontSize: 12,
                fontWeight: 'bolder',
                formatter: '{value} %',
                color: 'auto'
            },
            data: [
                {
                    value: 0
                }
            ]
        },
        {
            type: 'gauge',
            center: ['50%', '60%'],
            startAngle: 200,
            endAngle: -20,
            min: 0,
            max: 60,
            itemStyle: {
                color: '#4790fdff'
            },
            progress: {
                show: true,
                width: 8
            },
            pointer: {
                show: false
            },
            axisLine: {
                show: false
            },
            axisTick: {
                show: false
            },
            splitLine: {
                show: false
            },
            axisLabel: {
                show: false
            },
            detail: {
                show: false
            },
            data: [
                {
                    value: 0
                }
            ]
        }
    ]
}; 


if ((option1 && typeof option1 === 'object') &&
    (option2 && typeof option2 === 'object')) {
    myChart1.setOption(option1);
    myChart2.setOption(option2);
    myChart3.setOption(option1);
    myChart4.setOption(option2);
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

        fetch('css/phase2_functions/get_threshold.php')
            .then(res => res.text())
            .then(data => {
                thresholds = JSON.parse(data)
                const t1 = document.getElementById("current-threshold-1");
                const t2 = document.getElementById("current-threshold-2");
                t1.textContent = `${thresholds[1]} °C`;
                t2.textContent = `${thresholds[2]} °C`;
                console.log(t1.textContent);
                console.log(thresholds);
            })

            .catch(err => console.error('Could not fetch thresholds:', err));
            console.log(topic);
        if (topic === "sensor/fridge1") {

            // TEMP GAUGE for fridge 1
            myChart1.setOption({
                series: [
                    { data: [{ value: data.temperature }] },
                    { data: [{ value: data.temperature }] }
                ]
            });

            // HUMIDITY GAUGE for fridge 1
            myChart2.setOption({
                series: [
                    { data: [{ value: data.humidity }] },
                    { data: [{ value: data.humidity }] }
                ]
            });

        } else if (topic === "sensor/fridge2") {


            // TEMP GAUGE for fridge 2
            myChart3.setOption({
                series: [
                    { data: [{ value: data.temperature }] },
                    { data: [{ value: data.temperature }] }
                ]
            });

            // HUMIDITY GAUGE for fridge 2
            myChart4.setOption({
                series: [
                    { data: [{ value: data.humidity }] },
                    { data: [{ value: data.humidity }] }
                ]
            });

        }


        console.log(thresholds[1]);
        if (
            ((topic === "sensor/fridge1" && data.temperature > thresholds[1]) ||
                (topic === "sensor/fridge2" && data.temperature > thresholds[2])) &&
            emailSent === false
        ) {
            emailSent = true;
            
            if (topic === "sensor/fridge1" && data.temperature > thresholds[1]) {
                warn_image = 'warning1';
            } else {
                warn_image = 'warning2';
            }
            const warning_sign = document.getElementById(warn_image);
            warning_sign.classList.remove('hidden');

            fetch("css/phase2_functions/send_email.php", {
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

        console.log(emailSent);
        if (emailSent) {
            fetch("css/phase2_functions/client_reply.php")
                .then(res => res.text())
                .then(text => {
                    let reply;
                    try {
                        reply = JSON.parse(text);

                        if (reply.status === "yes") {
                            let a = document.getElementById("img");
                            a.style.animationDuration = 3 + "s";
                            console.log("Fan started");
                            fetch("css/phase2_functions/fan.php")
                                .then(() => {
                                    emailSent = false;
                                    let a = document.getElementById("img");
                                    a.style.animationDuration = 0 + "s";

                                    const warning_sign = document.getElementById(warn_image);
                                    warning_sign.classList.add('hidden');
                                    warn_image = '';
                                })
                                .catch(err => console.error("Error running fan:", err));
                        } else if (reply.status === "no") {
                            setFanGauge(false);
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
        }
    }, 1000);

});


let a = document.getElementById("img");
function myfunon() {
    a.style.animationDuration = 3 + "s";
    fetch("css/phase2_functions/fan_on.php")
        .then(() => { })
        .catch(err => console.error("Error running fan:", err));

}
function myfunoff() {
    a.style.animationDuration = 0 + "s";
    fetch("css/phase2_functions/fan_off.php")
        .then(() => { })
        .catch(err => console.error("Error running fan:", err));
}