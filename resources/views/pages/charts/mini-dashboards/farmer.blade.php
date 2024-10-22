<script>
    "use strict"
    const drawCharts = () => {
        axios.post('{{ route('cooperative.farmer.mini-dashboard.stats') }}').then(res => {
            farmersPerRoute(res)
            volumeOfCollectionPerRoute(res)
        }).catch(err => {
            console.log(err)
        })
    }

    const farmersPerRoute = (res) => {
        let barChartCanvas = $("#farmersPerRoute")
            .get(0)
            .getContext("2d");

        const data = res.data.farmer_routes
        const max_value = getMaxValue(data, "farmers")
        const step_size = calculateStepSize(data, "farmers")
        let barChart = new Chart(barChartCanvas, {
            type: "bar",
            data: {
                labels: getLabels(data, "route"),
                datasets: [
                    {
                        label: "Routes",
                        data: getDataPoints(data, "farmers"),
                        backgroundColor: ChartColor[0],
                        borderColor: ChartColor[0],
                        borderWidth: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
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
                                labelString: "Routes",
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
                                labelString: "Number of Farmers",
                                fontColor: chartFontcolor,
                                fontSize: 12,
                                lineHeight: 2
                            },
                            ticks: {
                                display: true,
                                autoSkip: false,
                                maxRotation: 0,
                                fontColor: chartFontcolor,
                                stepSize: step_size,
                                min: 0,
                                max: max_value
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
                    text.push("<li>");
                    for (let i = 0; i < chart.data.datasets.length; i++) {
                        text.push("<li>");
                        text.push(
                            '<span style="background-color:' +
                            chart.data.datasets[i].borderColor +
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
            "bar-traffic-legend"
        ).innerHTML = barChart.generateLegend();

    }
    const volumeOfCollectionPerRoute = (res) => {
        let barChartCanvas = $("#volumeOfCollectionPerRoute")
            .get(0)
            .getContext("2d");

        const data = res.data.collections_per_route
        const max_value = getMaxValue(data, "collections")
        const step_size = calculateStepSize(data, "collections")
        let barChart = new Chart(barChartCanvas, {
            type: "bar",
            data: {
                labels: getLabels(data, "route"),
                datasets: [
                    {
                        label: "Routes",
                        data: getDataPoints(data, "collections"),
                        backgroundColor: ChartColor[1],
                        borderColor: ChartColor[1],
                        borderWidth: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
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
                                labelString: "Routes",
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
                                labelString: "Volume Collected",
                                fontColor: chartFontcolor,
                                fontSize: 12,
                                lineHeight: 2
                            },
                            ticks: {
                                display: true,
                                autoSkip: false,
                                maxRotation: 0,
                                fontColor: chartFontcolor,
                                stepSize: step_size,
                                min: 0,
                                max: max_value
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
                    text.push("<li>");
                    for (let i = 0; i < chart.data.datasets.length; i++) {
                        text.push("<li>");
                        text.push(
                            '<span style="background-color:' +
                            chart.data.datasets[i].borderColor +
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
            "bar-traffic-legend-2"
        ).innerHTML = barChart.generateLegend();

    }
    drawCharts()
</script>
