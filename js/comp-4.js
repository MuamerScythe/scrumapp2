function draw_week(data) {
  var labels = [];
  var dataValues = [];
  Object.keys(data).map(e => labels.push(weekDay(data[e].day)));
  Object.keys(data).map(e => dataValues.push(data[e].late));
  labels.unshift('');
  labels.push('');
  dataValues.unshift(data[0].late);
  dataValues.push(data[data.length - 1].late);
  console.log(labels);
  let draw2 = Chart.controllers.line.prototype.draw;
  var shadowed4 = {
      beforeDatasetsDraw: function(chart, options) {
      chart.ctx.shadowColor = '#000000';
      chart.ctx.shadowOffsetY = 10;
      chart.ctx.shadowBlur = 25;
      chart.ctx.canvas.style.color = "red";
    },
    afterDatasetsDraw: function(chart, options) {
      chart.ctx.shadowColor = 'rgba(0, 0, 0, 0)';
      chart.ctx.shadowBlur = 0;
    }
  };

  Chart.Tooltip.positioners.custom = function(elements, eventPosition) {
      /** @type {Chart.Tooltip} */
      var tooltip = this;

      /* ... */
      if (!elements.length) {
        return false;
      }

      var i, len;
      var x = 0;
      var y = 0;
      var count = 0;

      for (i = 0, len = elements.length; i < len; ++i) {
        var el = elements[i];
        if (el && el.hasValue()) {
          var pos = el.tooltipPosition();
          x += pos.x;
          y += pos.y;
          ++count;
        }
      }

      return {
        x: Math.round(x / count),
        y: Math.round(y / count) - 10
      };
  }
  let ctx2 = document.querySelector("#myChart-2").getContext('2d');
  var gradientStroke = ctx2.createLinearGradient(500, 0, 100, 0);
  gradientStroke.addColorStop(0, "#1cc3e8");
  gradientStroke.addColorStop(1, "#1ee9b6");
  let myChart2 = new Chart(ctx2, {
      type: 'line',
      data: {
          labels: labels,
          datasets: [{
              data: dataValues,
              backgroundColor: 'rgba(0, 0, 0, 0.1)',
              borderColor: '#fff',
              pointHoverBackgroundColor: "#1de9b6",
              pointHoverBorderColor: "#1de9b6",
              pointHoverRadius: 7,
              fill: false,
              tension: 0.3,
              pointHitRadius:40,
              spanGaps: true
          }]
      },
      options: {
        layout: {
          padding: {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0
          }
          },
          responsive: false,
          elements: { point: { radius: 0 } },
          tooltips: {
            intersect: false,
            caretSize: 0,
            position: 'custom',
            yAlign: 'bottom',
            yPadding: 10,
            xPadding: 10,
            backgroundColor: '#1de9b6',
            titleFontSize: 16,
            titleFontColor: '#0066ff',
            bodyFontColor: '#5a5a5a',
            bodyFontSize: 14,
            displayColors: false,
              callbacks: {
                  label: function(e, d) {
                      return `${e.yLabel}`
                  },
                  title: function() {
                      return;
                  }
              }
          },
          legend: {
              display: false
          },
          legendCallback: function(chart) {
              var text = [];
              var total = 0;
              var danas = 0;
              for (var i=1; i<chart.data.datasets[0].data.length - 1; i++) {
                total += parseInt(chart.data.datasets[0].data[i]);
                if(i == chart.data.datasets[0].data.length - 2) {
                  danas = chart.data.datasets[0].data[i];
                }
                //console.log(chart.data.labels)
                //console.log(((chart.data.datasets[0].data[i]/chart.data.datasets[0]._meta[2].total) * 100).toFixed(1)); // see what's inside the obj.
              }
              text.push('<div class="values">'+total+'<div class="labels">Zadnjih 7 dana</div></div>');
              text.push('<div class="values">'+danas+'<div class="labels">Danas</div></div>');
            return text.join("");
          },
          scales: {
              yAxes: [{

                gridLines: {
                  drawBorder: false,
                  display: false,
                  zeroLineColor: "rgba(0, 0, 0, 0)"
                },
                ticks: {
                  beginAtZero:true,
                      fontColor: '#d1d2d4',
                      display: false,
                      suggestedMax:5,
                  }
              }],
              xAxes: [{
                gridLines: {
                  drawBorder: false,
                    display: false,
                    color: "rgba(0, 0, 0, 0)",
                },
                ticks: {
                      fontSize: 11,
                      fontColor: '#49505d',
                  }
              }]
          }
      },
      plugins: [shadowed4]
  });
  document.getElementById('reports').innerHTML = myChart2.generateLegend();
}
function weekDay(day) {
  var days = [];
  days[0] = 'Not defined';
  days[1] = "Ned";
  days[2] = "Pon";
  days[3] = "Uto";
  days[4] = "Sri";
  days[5] = "Čet";
  days[6] = "Pet";
  days[7] = "Sub";
  return days[day];
}
