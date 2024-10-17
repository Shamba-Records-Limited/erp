<script>
    "use strict"

    const url = '{{ route("cooperative.dashboard") }}'
    const drawCharts = (url) => {

        axios.get(url).then(res => {
            const data = res.data
            productsQuantityChart(data)
        }).catch(err => {
            console.log(err)
        })

    }

    const productsQuantityChart = (chartData) => {
        const data = chartData.collections
        const max_value = getMaxValue(data, "quantity")
        const step_size = calculateStepSize(data, "quantity")
        let stackedbarChartCanvas = $("#stackedbarChart")
            .get(0)
            .getContext("2d");
        let stackedbarChart = new Chart(stackedbarChartCanvas, {
            type: "bar",
            data: {
                labels: getLabels(data, "product"),
                datasets: [
                    {
                        label: "Collected",
                        backgroundColor: ChartColor[0],
                        borderColor: ChartColor[0],
                        borderWidth: 1,
                        data: getDataPoints(data, "quantity")
                    },
                    {
                        label: "Sold",
                        backgroundColor: ChartColor[1],
                        borderColor: ChartColor[1],
                        borderWidth: 1,
                        data: getDataPoints(data, "sold")
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                legend: false,
                categoryPercentage: 0.5,
                stacked: true,
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: "Products",
                                fontColor: chartFontcolor,
                                fontSize: 12,
                                lineHeight: 2
                            },
                            ticks: {
                                fontColor: chartFontcolor,
                                stepSize: 50,
                                min: 0,
                                max: 150,
                                autoSkip: false,
                                autoSkipPadding: 15,
                                maxRotation: 0,
                                maxTicksLimit: 10
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false,
                                color: chartGridLineColor,
                                zeroLineColor: chartGridLineColor
                            }
                        }
                    ],
                    yAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: "Total Collections",
                                fontColor: chartFontcolor,
                                fontSize: 12,
                                lineHeight: 2
                            },
                            ticks: {
                                fontColor: chartFontcolor,
                                stepSize: step_size,
                                min: 0,
                                max: max_value,
                                autoSkip: true,
                                autoSkipPadding: 15,
                                maxRotation: 0,
                                maxTicksLimit: 10
                            },
                            gridLines: {
                                drawBorder: false,
                                color: chartGridLineColor,
                                zeroLineColor: chartGridLineColor
                            }
                        }
                    ]
                },
                legend: {
                    display: false
                },
                legendCallback: function (chart) {
                    let text = [];
                    text.push('<div class="chartjs-legend"><ul>');
                    for (let i = 0; i < chart.data.datasets.length; i++) {
                        text.push("<li>");
                        text.push(
                            '<span style="background-color:' +
                            chart.data.datasets[i].backgroundColor +
                            '">' +
                            "</span>"
                        );
                        text.push(chart.data.datasets[i].label);
                        text.push("</li>");
                    }
                    text.push("</ul></div>");
                    return text.join("");
                },
                elements: {
                    point: {
                        radius: 0
                    }
                }
            }
        });
        document.getElementById(
            "stacked-bar-traffic-legend"
        ).innerHTML = stackedbarChart.generateLegend();

    }


    drawCharts(url);


    // function getDataSoldPoints(data, key1, key2) {
    //     let dataPoints = [];
    //     data.forEach(d => {
    //         dataPoints.push(Number(d[key1])-Number(d[key2]));
    //     })
    //     return dataPoints
    // }
</script>
