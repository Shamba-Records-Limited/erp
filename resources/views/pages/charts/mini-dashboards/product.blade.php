<script>
    "use strict"
    const drawCharts = () => {

      const products = $('#products').val();
      const date = $('#date').val();
      const payload = {
        'products': products,
        'date': date
      }
        axios.post('{{ route('cooperative.product-mini-dashboard.stats') }}', payload).then(res => {
            productSupplyData(res);
            productsQuantityChart(res)
        }).catch(err => {
            console.log(err)
        })
    }

    const productSupplyData = (res) => {
        let barChartCanvas = $("#productSupplyTrend")
            .get(0)
            .getContext("2d");

        const data = res.data.product_supply_trend
        const max_value = getMaxValue(data, "total")
        const step_size = calculateStepSize(data, "total")
        let barChart = new Chart(barChartCanvas, {
            type: "bar",
            data: {
                labels: getLabels(data, "name"),
                datasets: [
                    {
                        label: "Products",
                        data: getDataPoints(data, "total"),
                        backgroundColor: ChartColor[2],
                        borderColor: ChartColor[2],
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
                                labelString: "Total Supplies",
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

    const productsQuantityChart = (res) => {
        const data = res.data.products
        const max_value = getMaxValue(data, "quantity")
        const step_size = calculateStepSize(data, "quantity")
        let stackedbarChartCanvas = $("#productBpSpAnalysis")
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
                        label: "Available",
                        backgroundColor: ChartColor[1],
                        borderColor: ChartColor[1],
                        borderWidth: 1,
                        data: getDataPoints(data, "available")
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                categoryPercentage: 0.5,
                stacked: true,
                legend:false,
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
    drawCharts()
</script>
