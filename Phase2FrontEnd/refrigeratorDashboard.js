async function createDashboards() {
    // Create Temperature 1 Gauge
    var tempGauge1 = pureknob.createKnob(500, 300);
    // Set Properties
    tempGauge1.setValue(20);
    tempGauge1.setProperty('angleStart', -0.5 * Math.PI);
    tempGauge1.setProperty('angleEnd', 0.5 * Math.PI);
    tempGauge1.setProperty('colorFG', '#00a6ffff');
    tempGauge1.setProperty('colorBG', '#08043fff');
    tempGauge1.setProperty('valMax', 30);
    tempGauge1.setProperty('label', 'Temperature 1');
    tempGauge1.setProperty('colorLabel', '#00a6ffff');
    tempGauge1.setProperty('readonly', true);
    // Append to temp1 div
    var appendTemp1 = tempGauge1.node();
    var temp1div = document.getElementById('temp1');
    temp1div.appendChild(appendTemp1);

    // Create Humidity Gauge 1
    var humGauge1 = pureknob.createKnob(500, 300);
    // Set Properties
    humGauge1.setValue(40);
    humGauge1.setProperty('angleStart', -0.5 * Math.PI);
    humGauge1.setProperty('angleEnd', 0.5 * Math.PI);
    humGauge1.setProperty('colorFG', '#ee00ffff');
    humGauge1.setProperty('colorBG', '#08043fff');
    humGauge1.setProperty('valMax', 100);
    humGauge1.setProperty('label', 'Humidity 1');
    humGauge1.setProperty('colorLabel', '#ee00ffff');
    humGauge1.setProperty('readonly', true);
    // Append to hum1 div
    var appendHum1 = humGauge1.node();
    var hum1div = document.getElementById('hum1');
    hum1div.appendChild(appendHum1);

    // Create Temperature Gauge 2
    var tempGauge2 = pureknob.createKnob(500, 300);
    // Set Properties
    tempGauge2.setValue(20);
    tempGauge2.setProperty('angleStart', -0.5 * Math.PI);
    tempGauge2.setProperty('angleEnd', 0.5 * Math.PI);
    tempGauge2.setProperty('colorFG', '#00ffb3ff');
    tempGauge2.setProperty('colorBG', '#08043fff');
    tempGauge2.setProperty('valMax', 30);
    tempGauge2.setProperty('label', 'Temperature 2');
    tempGauge2.setProperty('colorLabel', '#00ffb3ff');
    tempGauge2.setProperty('readonly', true);
    // Append to temp2 div
    var appendTemp2 = tempGauge2.node();
    var temp2div = document.getElementById('temp2');
    temp2div.appendChild(appendTemp2);

    // Create Humidity Gauge 2
    var humGauge2 = pureknob.createKnob(500, 300);
    // Set Properties
    humGauge2.setValue(40);
    humGauge2.setProperty('angleStart', -0.5 * Math.PI);
    humGauge2.setProperty('angleEnd', 0.5 * Math.PI);
    humGauge2.setProperty('colorFG', '#ff8800ff');
    humGauge2.setProperty('colorBG', '#08043fff');
    humGauge2.setProperty('valMax', 100);
    humGauge2.setProperty('label', 'Humidity 2');
    humGauge2.setProperty('colorLabel', '#ff8800ff');
    humGauge2.setProperty('readonly', true);
    // Append to hum2 div
    var appendHum2 = humGauge2.node();
    var hum2div = document.getElementById('hum2');
    hum2div.appendChild(appendHum2);

    // Create Fan Gauge/Design
    var fanDesign = pureknob.createKnob(600, 300);
    var onOff = false; // modify value to turn on/off the fan
    // Set Properties
    fanDesign.setProperty('angleStart', -0.75 * Math.PI);
    fanDesign.setProperty('angleEnd', 0.75 * Math.PI);
    if (onOff == false) {
        fanDesign.setValue(0);
        fanDesign.setProperty('valMax', 0);
        fanDesign.setProperty('colorFG', '#ff0000ff');
        fanDesign.setProperty('colorLabel', '#ff0000ff');
        fanDesign.setProperty('label', 'Fan Off');
    }
        fanDesign.setProperty('readonly', true);

    // Append to fan div
    var appendFan = fanDesign.node();
    var fandiv = document.getElementById('fanInfo');
    fandiv.appendChild(appendFan);
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
    } else {
        button.textContent = 'Manually Turn On';
        fan.setValue(0);
        fan.setProperty('colorFG', '#ff0000ff');
        fan.setProperty('colorLabel', '#ff0000ff');
        fan.setProperty('label', 'Fan Off');
    }

    fanInfo.appendChild(fan.node());
}
