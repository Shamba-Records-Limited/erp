<script>
  "use strict"
  const drawCharts = () => {
    const date = $('#date').val();
    const payload = {
      'date': date
    }
    axios.post('{{ route('cooperative.collections.reports.stats') }}', payload).then(res => {
      productCollections(res);
    }).catch(err => {
      console.log(err)
    })
  }

  const productCollections = (res) => {
    let barChartCanvas = $("#productSupplyTrend")
    .get(0)
    .getContext("2d");

    const data = res.data.collections
    const max_value = getMaxValue(data, "quantity")
    const step_size = calculateStepSize(data, "quantity")
    let barChart = new Chart(barChartCanvas, {
      type: "bar",
      data: {
        labels: getLabels(data, "product"),
        datasets: [
          {
            label: "Volume Supplied",
            data: getDataPoints(data, "quantity"),
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
                autoSkip: true,
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
                labelString: "Volume Supplies",
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
  drawCharts()
</script>
