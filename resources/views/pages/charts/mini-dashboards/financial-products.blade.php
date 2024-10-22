<script>

    let data = null;
    $('#filterBtn').click( () => {
        data = $('#dates').val()
    })
    axios.post('{{ route('financial_products.dashboard.stats') }}', {dates: data}).then(res => {
        const data = res.data;
        loansPieChart(data);
        savingByType(data);
        loansVsrepaymentChart(data);

    });

    const loansPieChart = (data) => {
        const obj = data.loan_grouped_by_status
        let pieKeys = []
        let pieValues = []
        Object.keys(obj).map(e => {
            pieKeys.push(e.replace('_', ' '))
            pieValues.push(obj[e])
        });

        if ($("#loansByStatus").length) {
            var pieChartCanvas = $("#loansByStatus")
                .get(0)
                .getContext("2d");
            var pieChart = new Chart(pieChartCanvas, {
                type: "pie",
                data: {
                    datasets: [
                        {
                            data: pieValues,
                            backgroundColor: getPieChartColors(pieKeys),
                            borderColor: getPieChartColors(pieKeys)
                        }
                    ],
                    labels: pieKeys
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
                    legendCallback: function (chart) {
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
            document.getElementById(
                "loansByStatusLegend"
            ).innerHTML = pieChart.generateLegend();
        }
    }

    const savingByType = (res) => {
        let barChartCanvas = $("#savingChart")
            .get(0)
            .getContext("2d");

        const data = res.savings
        const max_value = getMaxValue(data, 'amount')
        const step_size = calculateStepSize(data, 'amount')
        let barChart = new Chart(barChartCanvas, {
            type: "bar",
            data: {
                labels: getLabels(data, 'type'),
                datasets: [
                    {
                        label: 'Savings',
                        data: getDataPoints(data, 'amount'),
                        backgroundColor: ChartColor[7],
                        borderColor: ChartColor[7],
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
                                labelString: 'Savings',
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
                                labelString: 'Total Savings',
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
            "savingChart"
        ).innerHTML = barChart.generateLegend();

    }

    const loansVsrepaymentChart = (chartData) => {
        const data = chartData.loans_by_type;
        const max_value_key = getTypeWithMaxValue(data) ? 'loan' : 'repayment';
        const max_value = getMaxValue(data, max_value_key)
        const step_size = calculateStepSize(data, max_value_key)
        let stackedbarChartCanvas = $("#stackedbarChart")
            .get(0)
            .getContext("2d");
        let stackedbarChart = new Chart(stackedbarChartCanvas, {
            type: "bar",
            data: {
                labels: getLabels(data, "type"),
                datasets: [
                    {
                        label: "Loans",
                        backgroundColor: ChartColor[0],
                        borderColor: ChartColor[0],
                        borderWidth: 1,
                        data: getDataPoints(data, "loan")
                    },
                    {
                        label: "Repayments",
                        backgroundColor: ChartColor[1],
                        borderColor: ChartColor[1],
                        borderWidth: 1,
                        data: getDataPoints(data, "repayment")
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
                                labelString: "Loan Types",
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
                                labelString: "Amount (KSH)",
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

    const getTypeWithMaxValue = (data) => {
        let maxInLoans = 0;
        let maxInRepayments = 0;
        data.forEach(d => {
            if (d.repayment >= d.loan) {
                maxInRepayments = d.repayment >= maxInRepayments ? d.repayment : maxInRepayments
            } else {
                maxInLoans = d.loan >= maxInLoans ? d.loan : maxInLoans
            }
        })
        return maxInLoans >= maxInRepayments
    }

</script>
