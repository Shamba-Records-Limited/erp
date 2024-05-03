<script>

    "use strict";
    const HEXADECIMAL_CHART_COLORS = ["#5D62B4", "#54C3BE", "#EF726F", "#F9C446",
        "#f20812","#0f412a","#1a0b81","#bcbc08",
        "#6d3c2c","#2ac48c","#6b5557","#de6329"]


    {{--axios.post( '{{ route('cooperative.wallet.dashboard.barchart') }}').then(res =>{--}}
    {{--    const {data} = res--}}
    {{--    if ($("#mixed-chart").length) {--}}
    {{--        var chartData = {--}}
    {{--            labels:  data.months,--}}
    {{--            datasets: [--}}
    {{--                {--}}
    {{--                    type: "line",--}}
    {{--                    label: "Income",--}}
    {{--                    data: data.income,--}}
    {{--                    backgroundColor: ChartColor[1],--}}
    {{--                    borderColor: ChartColor[1],--}}
    {{--                    borderWidth: 2,--}}
    {{--                    fill: true--}}
    {{--                },--}}
    {{--                {--}}
    {{--                    type: "bar",--}}
    {{--                    label: "Transactions",--}}
    {{--                    data: data.transactions,--}}
    {{--                    backgroundColor: ChartColor[0],--}}
    {{--                    borderColor: ChartColor[0],--}}
    {{--                    borderWidth: 1--}}
    {{--                }--}}
    {{--            ]--}}
    {{--        };--}}
    {{--        var MixedChartCanvas = document--}}
    {{--            .getElementById("mixed-chart")--}}
    {{--            .getContext("2d");--}}
    {{--        const lineChart = new Chart(MixedChartCanvas, {--}}
    {{--            type: "bar",--}}
    {{--            data: chartData,--}}
    {{--            options: {--}}
    {{--                responsive: true,--}}
    {{--                title: {--}}
    {{--                    display: true,--}}
    {{--                    text: "Annual Transactions and Income",--}}
    {{--                    fontColor: chartFontcolor--}}
    {{--                },--}}
    {{--                scales: {--}}
    {{--                    xAxes: [--}}
    {{--                        {--}}
    {{--                            display: true,--}}
    {{--                            ticks: {--}}
    {{--                                fontColor: chartFontcolor,--}}
    {{--                                stepSize: 20,--}}
    {{--                                min: 0,--}}
    {{--                                max: 100,--}}
    {{--                                autoSkip: true,--}}
    {{--                                autoSkipPadding: 10,--}}
    {{--                                maxRotation: 0,--}}
    {{--                                maxTicksLimit: 10--}}
    {{--                            },--}}
    {{--                            gridLines: {--}}
    {{--                                display: false,--}}
    {{--                                drawBorder: false,--}}
    {{--                                color: chartGridLineColor,--}}
    {{--                                zeroLineColor: chartGridLineColor--}}
    {{--                            }--}}
    {{--                        }--}}
    {{--                    ],--}}
    {{--                    yAxes: [--}}
    {{--                        {--}}
    {{--                            display: true,--}}
    {{--                            scaleLabel: {--}}
    {{--                                display: true,--}}
    {{--                                labelString: "Number of Transactions and Total Income(KES)( X100)",--}}
    {{--                                fontSize: 12,--}}
    {{--                                lineHeight: 2,--}}
    {{--                                fontColor: chartFontcolor--}}
    {{--                            },--}}
    {{--                            ticks: {--}}
    {{--                                fontColor: chartFontcolor,--}}
    {{--                                display: true,--}}
    {{--                                autoSkip: false,--}}
    {{--                                maxRotation: 0,--}}
    {{--                                stepSize: 10,--}}
    {{--                                min: 0,--}}
    {{--                                max: data.max_value--}}
    {{--                            },--}}
    {{--                            gridLines: {--}}
    {{--                                drawBorder: false,--}}
    {{--                                color: chartGridLineColor,--}}
    {{--                                zeroLineColor: chartGridLineColor--}}
    {{--                            }--}}
    {{--                        }--}}
    {{--                    ]--}}
    {{--                },--}}
    {{--                legend: {--}}
    {{--                    display: false--}}
    {{--                },--}}
    {{--                legendCallback: function(chart) {--}}
    {{--                    var text = [];--}}
    {{--                    text.push(--}}
    {{--                        '<div class="chartjs-legend d-flex justify-content-center mt-4"><ul>'--}}
    {{--                    );--}}

    {{--                    // console.log(chart.data.datasets)--}}
    {{--                    for (var i = 0; i < chart.data.datasets.length; i++) {--}}
    {{--                        // console.log(chart.data.datasets[i]); // see what's inside the obj.--}}
    {{--                        text.push("<li>");--}}
    {{--                        text.push(--}}
    {{--                            '<span style="background-color:' +--}}
    {{--                            chart.data.datasets[i].borderColor +--}}
    {{--                            '">' +--}}
    {{--                            "</span>"--}}
    {{--                        );--}}
    {{--                        text.push(chart.data.datasets[i].label);--}}
    {{--                        text.push("</li>");--}}
    {{--                    }--}}
    {{--                    text.push("</ul></div>");--}}
    {{--                    return text.join("");--}}
    {{--                }--}}
    {{--            }--}}
    {{--        });--}}
    {{--        document.getElementById(--}}
    {{--            "mixed-chart-legend"--}}
    {{--        ).innerHTML = lineChart.generateLegend();--}}
    {{--    }--}}
    {{--    //doughnutChart--}}
    {{--    if ($("#doughnutChart").length) {--}}
    {{--        var doughnutChartCanvas = $("#doughnutChart")--}}
    {{--            .get(0)--}}
    {{--            .getContext("2d");--}}
    {{--        var doughnutPieData = {--}}
    {{--            datasets: [--}}
    {{--                {--}}
    {{--                    data: data.doughnut_chart_income,--}}
    {{--                    backgroundColor: chartColors(data.doughnut_chart_income.length),--}}
    {{--                    borderColor: chartColors(data.doughnut_chart_income.length),--}}
    {{--                }--}}
    {{--            ],--}}

    {{--            // These labels appear in the legend and in the tooltips when hovering different arcs--}}
    {{--            labels:  data.months,--}}
    {{--        };--}}
    {{--        var doughnutPieOptions = {--}}
    {{--            cutoutPercentage: 40,--}}
    {{--            animationEasing: "easeOutBounce",--}}
    {{--            animateRotate: true,--}}
    {{--            animateScale: false,--}}
    {{--            responsive: true,--}}
    {{--            maintainAspectRatio: true,--}}
    {{--            showScale: true,--}}
    {{--            legend: false,--}}
    {{--            layout: {--}}
    {{--                padding: {--}}
    {{--                    left: 0,--}}
    {{--                    right: 0,--}}
    {{--                    top: 0,--}}
    {{--                    bottom: 0--}}
    {{--                }--}}
    {{--            }--}}
    {{--        };--}}
    {{--        var doughnutChart = new Chart(doughnutChartCanvas, {--}}
    {{--            type: "doughnut",--}}
    {{--            data: doughnutPieData,--}}
    {{--            options: doughnutPieOptions--}}
    {{--        });--}}
    {{--    }--}}
    {{--}).catch(err => {--}}
    {{--    console.log(err)--}}
    {{--})--}}


    {{--axios.post('{{ route('cooperative.wallet.dashboard.pie-chart') }}').then(res =>{--}}

    {{--    const {data} = res--}}
    {{--    //pie chart--}}
    {{--    if ($("#pieChart").length) {--}}
    {{--        var pieChartCanvas = $("#pieChart")--}}
    {{--            .get(0)--}}
    {{--            .getContext("2d");--}}
    {{--        var pieChart = new Chart(pieChartCanvas, {--}}
    {{--            type: "pie",--}}
    {{--            data: {--}}
    {{--                datasets: [--}}
    {{--                    {--}}
    {{--                        data: data.data,--}}
    {{--                        backgroundColor: [--}}
    {{--                            HEXADECIMAL_CHART_COLORS[4],--}}
    {{--                            HEXADECIMAL_CHART_COLORS[6],--}}
    {{--                        ],--}}
    {{--                        borderColor: [--}}
    {{--                            HEXADECIMAL_CHART_COLORS[4],--}}
    {{--                            HEXADECIMAL_CHART_COLORS[6],--}}
    {{--                        ]--}}
    {{--                    }--}}
    {{--                ],--}}
    {{--                labels: ["Loans", "Income",]--}}
    {{--            },--}}
    {{--            options: {--}}
    {{--                responsive: true,--}}
    {{--                animation: {--}}
    {{--                    animateScale: true,--}}
    {{--                    animateRotate: true--}}
    {{--                },--}}
    {{--                legend: {--}}
    {{--                    display: false--}}
    {{--                },--}}
    {{--                legendCallback: function(chart) {--}}
    {{--                    var text = [];--}}
    {{--                    text.push('<div class="chartjs-legend"><ul>');--}}
    {{--                    for (--}}
    {{--                        var i = 0;--}}
    {{--                        i < chart.data.datasets[0].data.length;--}}
    {{--                        i++--}}
    {{--                    ) {--}}
    {{--                        text.push(--}}
    {{--                            '<li><span style="background-color:' +--}}
    {{--                            chart.data.datasets[0].backgroundColor[i] +--}}
    {{--                            '">'--}}
    {{--                        );--}}
    {{--                        text.push("</span>");--}}
    {{--                        if (chart.data.labels[i]) {--}}
    {{--                            text.push(chart.data.labels[i]);--}}
    {{--                        }--}}
    {{--                        text.push("</li>");--}}
    {{--                    }--}}
    {{--                    text.push("</div></ul>");--}}
    {{--                    return text.join("");--}}
    {{--                }--}}
    {{--            }--}}
    {{--        });--}}
    {{--        // document.getElementById(--}}
    {{--        //     "pie-chart-legend"--}}
    {{--        // ).innerHTML = pieChart.generateLegend();--}}
    {{--    }--}}

    {{--}).catch(err =>{--}}
    {{--    console.log(err)--}}
    {{--})--}}




    {{--function getRandomIntInclusive(min, max, arrayLength) {--}}
    {{--    let numbers = []--}}
    {{--    min = Math.ceil(min);--}}
    {{--    max = Math.floor(max);--}}
    {{--    for(var i = 0; i < arrayLength; i++)--}}
    {{--    {--}}
    {{--        numbers.push(Math.floor(Math.random() * (max - min + 1)) + min)--}}
    {{--    }--}}
    {{--    // max & min both included--}}
    {{--    return numbers;--}}
    {{--}--}}

    {{--function chartColors(size)--}}
    {{--{--}}
    {{--    let colors = [];--}}
    {{--    for(let i = 0; i < size; i++)--}}
    {{--    {--}}
    {{--        colors.push(HEXADECIMAL_CHART_COLORS[i]);--}}
    {{--    }--}}
    {{--    return colors;--}}
    {{--}--}}

</script>