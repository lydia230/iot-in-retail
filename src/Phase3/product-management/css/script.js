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
                    value: 20
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
                    value: 20
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
                    value: 20
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
                    value: 20
                }
            ]
        }
    ]
};

setInterval(function () {
    const random = +(Math.random() * 60).toFixed(2);
    myChart1.setOption({
        series: [
            {
                data: [
                    {
                        value: random
                    }
                ]
            },
            {
                data: [
                    {
                        value: random
                    }
                ]
            }
        ]
    });

    myChart2.setOption({
        series: [
            {
                data: [
                    {
                        value: random
                    }
                ]
            },
            {
                data: [
                    {
                        value: random
                    }
                ]
            }
        ]
    });

    myChart3.setOption({
        series: [
            {
                data: [
                    {
                        value: random
                    }
                ]
            },
            {
                data: [
                    {
                        value: random
                    }
                ]
            }
        ]
    });

    myChart4.setOption({
        series: [
            {
                data: [
                    {
                        value: random
                    }
                ]
            },
            {
                data: [
                    {
                        value: random
                    }
                ]
            }
        ]
    });
}, 2000);

if ((option1 && typeof option1 === 'object') &&
(option2 && typeof option2 === 'object')) {
    myChart1.setOption(option1);
    myChart2.setOption(option2);
    myChart3.setOption(option1);
    myChart4.setOption(option2);
}

let a = document.getElementById("img");
function myfunon() {
  a.style.animationDuration = 3 + "s";
}
function myfunoff() {
  a.style.animationDuration = 0 + "s";
}

