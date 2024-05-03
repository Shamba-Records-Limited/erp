<script>
    "use strict"

    const url = '{{ route("cooperative.dashboard") }}'
    const drawCharts = (url) => {

      const data = {
        'params': {
          'date': $('#date').val()
        }
      }
        axios.get(url,data).then(res => {
            const data = res.data
            const genderChart = [data.gender.male, data.gender.female, data.gender.other]
            drawDoughnutChart(genderChart);
            productsQuantityChart(data)
            salesAnalysisChart(data.sales);
        }).catch(err => {
            console.log(err)
        })

    }
    const drawDoughnutChart = (data) => {
        if ($("#UsersDoughnutChart").length) {
            let doughnutChartCanvas = $("#UsersDoughnutChart")
                .get(0)
                .getContext("2d");
            let doughnutPieData = {

                datasets: [
                    {
                        data: data,
                        backgroundColor: [
                            successColor,
                            primaryColor,
                            dangerColor
                        ],
                        borderColor: [
                            successColor,
                            primaryColor,
                            dangerColor
                        ]
                    }
                ],
                labels: ["Male", "Female", "Other"]
            };
            let doughnutPieOptions = {
                cutoutPercentage: 70,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false,
                responsive: true,
                maintainAspectRatio: true,
                showScale: true,
                legend: {
                    display: false
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                }
            };
            let doughnutChart = new Chart(doughnutChartCanvas, {
                type: "doughnut",
                data: doughnutPieData,
                options: doughnutPieOptions
            });
        }

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

    const salesAnalysisChart = (data) => {

        const max_value = getMaxValue(data,"amount")
        const step_size = calculateStepSize(data, "amount")
        let lineData = {
            labels:  getLabels(data, "period"),
            datasets: [
                {
                    data: getDataPoints(data, "amount"),
                    backgroundColor: successColor,
                    borderColor: successColor,
                    borderWidth: 3,
                    fill: true,
                    label: "Sales",
                    tension: 0.3
                }
            ]
        };
        let lineOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                filler: {
                    propagate: false
                }
            },
            interaction: {
                intersect: false,
            },
            scales: {
                xAxes: [
                    {
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: "Period",
                            fontSize: 10,
                            lineHeight: 2,
                            fontColor: chartFontcolor
                        },
                        ticks: {
                            fontColor: chartFontcolor,
                            stepSize: 50,
                            min: 0,
                            max: 150,
                            autoSkip: true,
                            autoSkipPadding: 15,
                            maxRotation: 0,
                            maxTicksLimit: 10
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false,
                            color: "transparent",
                            zeroLineColor: "#eeeeee"
                        }
                    }
                ],
                yAxes: [
                    {
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: "Sales in KES",
                            fontSize: 12,
                            lineHeight: 2,
                            fontColor: chartFontcolor
                        },
                        ticks: {
                            fontColor: chartFontcolor,
                            display: true,
                            autoSkip: false,
                            maxRotation: 0,
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
                line: {
                    tension: 0
                },
                point: {
                    radius: 0
                }
            }
        };
        let lineChartCanvas = $("#lineChart")
            .get(0)
            .getContext("2d");
        let lineChart = new Chart(lineChartCanvas, {
            type: "line",
            data: lineData,
            options: lineOptions
        });
        document.getElementById(
            "line-traffic-legend"
        ).innerHTML = lineChart.generateLegend();
        

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
