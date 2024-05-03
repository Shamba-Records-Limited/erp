<script>

    "use strict";

    axios.post( '{{ route('farmer.wallet.dashboard.barchart') }}').then(res =>{
        const {data} = res
        const barchartAndDonutData = data.barchartAndDonut
        const loansVsIncomeData = data.loansVsIncome
        barChartAndDonut(barchartAndDonutData)
        pieChart(loansVsIncomeData)

    }).catch(err => {
        console.log(err)
    })

    const barChartAndDonut = (data) => {
        const max_value = getMaxValue(data, "income")
        const step_size = calculateStepSize(data, "income")
        if ($("#mixed-chart").length) {
            var chartData = {
                labels:  getLabels(data, "month"),
                datasets: [
                    {
                        type: "line",
                        label: "Income",
                        data: getDataPoints(data, "income"),
                        backgroundColor: ChartColor[1],
                        borderColor: ChartColor[1],
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        type: "bar",
                        label: "Transactions",
                        data: getDataPoints(data, "transactions"),
                        backgroundColor: ChartColor[0],
                        borderColor: ChartColor[0],
                        borderWidth: 1
                    }
                ]
            };
            var MixedChartCanvas = document
                .getElementById("mixed-chart")
                .getContext("2d");
            const lineChart = new Chart(MixedChartCanvas, {
                type: "bar",
                data: chartData,
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: "Annual Transactions and Income",
                        fontColor: chartFontcolor
                    },
                    scales: {
                        xAxes: [
                            {
                                display: true,
                                ticks: {
                                    fontColor: chartFontcolor,
                                    stepSize: 20,
                                    min: 0,
                                    max: 100,
                                    autoSkip: true,
                                    autoSkipPadding: 10,
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
                                    labelString: "Number of Transactions and Total Income(KES)( X100)",
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
                    legendCallback: function(chart) {
                        var text = [];
                        text.push(
                            '<div class="chartjs-legend d-flex justify-content-center mt-4"><ul>'
                        );

                        // console.log(chart.data.datasets)
                        for (var i = 0; i < chart.data.datasets.length; i++) {
                            // console.log(chart.data.datasets[i]); // see what's inside the obj.
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
                    }
                }
            });
            document.getElementById(
                "mixed-chart-legend"
            ).innerHTML = lineChart.generateLegend();
        }
        if ($("#doughnutChart").length) {
            var doughnutChartCanvas = $("#doughnutChart")
                .get(0)
                .getContext("2d");
            var doughnutPieData = {
                datasets: [
                    {
                        data: getDataPoints(data, 'income'),
                        backgroundColor: ChartColor[2],
                        borderColor: ChartColor[2],
                    }
                ],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels:  data.months,
            };
            var doughnutPieOptions = {
                cutoutPercentage: 40,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false,
                responsive: true,
                maintainAspectRatio: true,
                showScale: true,
                legend: false,
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                }
            };
            var doughnutChart = new Chart(doughnutChartCanvas, {
                type: "doughnut",
                data: doughnutPieData,
                options: doughnutPieOptions
            });
        }
    }

    const pieChart = (data) => {
        if ($("#pieChart").length) {
            var pieChartCanvas = $("#pieChart")
                .get(0)
                .getContext("2d");
            var pieChart = new Chart(pieChartCanvas, {
                type: "pie",
                data: {
                    datasets: [
                        {
                            data: data.data,
                            backgroundColor: [
                                ChartColor[1],
                                ChartColor[1],
                            ],
                            borderColor: [
                                ChartColor[1],
                                ChartColor[1],
                            ]
                        }
                    ],
                    labels: ["Loans", "Income",]
                },
                options: {
                    responsive: true,
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    },
                    legend: {
                        display: false
                    },
                    legendCallback: function(chart) {
                        var text = [];
                        text.push('<div class="chartjs-legend"><ul>');
                        for (
                            var i = 0;
                            i < chart.data.datasets[0].data.length;
                            i++
                        ) {
                            text.push(
                                '<li><span style="background-color:' +
                                chart.data.datasets[0].backgroundColor[i] +
                                '">'
                            );
                            text.push("</span>");
                            if (chart.data.labels[i]) {
                                text.push(chart.data.labels[i]);
                            }
                            text.push("</li>");
                        }
                        text.push("</div></ul>");
                        return text.join("");
                    }
                }
            });
            // document.getElementById(
            //     "pie-chart-legend"
            // ).innerHTML = pieChart.generateLegend();
        }
    }


</script>
