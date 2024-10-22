<script>
    "use strict"
    const drawCharts = () => {
        axios.post('{{ route('cooperative.farm.mini-dashboard.stats') }}').then(res =>{
            const data = res.data
            breedChart(data.herds_grouping);
            cropVarietyChart(data.crops_by_breed);
            cropCalendarData(data.calendar_data)
        }).catch(err =>{
            console.log(err)
        })

    }

    const breedChart = (data) => {
        const max_value = getMaxValue(data, "count")
        const step_size = calculateStepSize(data, "count")
        let barChartCanvas = $("#breedDistributionChart")
            .get(0)
            .getContext("2d");
        let barChart = new Chart(barChartCanvas, {
            type: "bar",
            data: {
                labels: getLabels(data, "breed"),
                datasets: [
                    {
                        label: "Breeds",
                        data: getDataPoints(data, "count"),
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
                                labelString: "Breeds",
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
                                labelString: "Count",
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
            "chart-legend"
        ).innerHTML = barChart.generateLegend();
    }

    const cropCalendarData = (data) =>{
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialDate: new Date(),
            initialView: 'listWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            editable: true,
            selectable: true,
            businessHours: true,
            dayMaxEvents: true,
            weekNumbers: true,
            weekNumberCalculation: 'ISO',
            events: data
        });
        calendar.render();
    }

    const cropVarietyChart = (data) => {
        const max_value = getMaxValue(data, "count")
        const step_size = calculateStepSize(data, "count")
        let barChartCanvas = $("#cropVarietyChart")
            .get(0)
            .getContext("2d");
        let barChart = new Chart(barChartCanvas, {
            type: "bar",
            data: {
                labels: getLabels(data, "variety"),
                datasets: [
                    {
                        label: "Crop Variety",
                        data: getDataPoints(data, "count"),
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
                                labelString: "Crop Varieties",
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
                                labelString: "Count",
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
            "chart-legend-2"
        ).innerHTML = barChart.generateLegend();
    }
    drawCharts();
</script>
