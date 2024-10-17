<script>
    "use strict"
    const drawChats = () => {
        axios.post('{{ route('disease.mini-dashboard.stats') }}').then(res => {
            casesByStatus(res);
        }).catch(err => {
            console.log(err)
        })
    }

    const casesByStatus = (res) => {
        let barChartCanvas = $("#casesByStatus")
            .get(0)
            .getContext("2d");

        const data = res.data.case_by_status
        const max_value = getMaxValue(data, "count")
        const step_size = calculateStepSize(data, "count")
        let barChart = new Chart(barChartCanvas, {
            type: "bar",
            data: {
                labels: getLabels(data, "status"),
                datasets: [
                    {
                        label: "Status",
                        data: getDataPoints(data, "count"),
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
                                labelString: "Status",
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
                                labelString: "Case Count",
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
    const initMap = () => {
        axios.post('{{ route('disease.mini-dashboard.disease_map_data') }}')
            .then(res => {

                const locations = res.data.locations
                const kenya = {lat: 0.0236, lng: 37.9062};
                const map = new google.maps.Map(document.getElementById("mapView"), {
                    zoom: 8,
                    center: kenya,
                });

                const infowindow = new google.maps.InfoWindow();
                const bounds = new google.maps.LatLngBounds();
                for (let location of locations) {
                    const markerLatlng = new google.maps.LatLng(parseFloat(location.latitude), parseFloat(location.longitude))
                    let marker = new google.maps.Marker({
                        position: markerLatlng,
                        map: map,
                    });
                    bounds.extend(marker.position);

                    google.maps.event.addListener(marker, 'click', (function (marker, location) {
                        return function () {
                            infowindow.setContent(location.latitude + " & " + location.longitude);
                            infowindow.open(map, marker);
                        }
                    })(marker, location));
                }
                map.fitBounds(bounds);

            }).catch(err => {
            console.log(err)
        })
    }
    window.initMap = initMap
    drawChats();
</script>
