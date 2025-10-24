
function createDashboards() {
    console.log('Testing connectivity');
    var tempGauge1 = pureknob.createKnob(300, 300);
    // Set Properties
    
    tempGauge1.setValue(20);
    tempGauge1.setProperty('angleStart', -0.5 * Math.PI);
    tempGauge1.setProperty('angleEnd', 0.5 * Math.PI);
    tempGauge1.setProperty('colorFG', '#a200ffff');
    tempGauge1.setProperty('colorBG', '#08043fff');
    tempGauge1.setProperty('valMin', 10);
    tempGauge1.setProperty('valMax', 30);
    // tempGauge1.setPeaks([50]);
    var appendTemp1 = tempGauge1.node();
    var temp1div = document.getElementById('temp1');
    temp1div.appendChild(appendTemp1);
}