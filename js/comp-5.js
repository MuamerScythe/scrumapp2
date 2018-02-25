function drawMe(data) {
  var labels = [];
  var dataValues = [];
  Object.keys(data).map(e => labels.push(e));
  Object.keys(data).map(e => dataValues.push(data[e]));
let ctx3 = document.querySelector("#myChart-3").getContext('2d');
let canvas1 = document.getElementById("myChart-3");
var gradientStroke5 = ctx3.createLinearGradient(300, 0, 0, 0);
gradientStroke5.addColorStop(0, "#5bedc9");
gradientStroke5.addColorStop(1, "#11c2e6");

var gradientStroke5_1 = ctx3.createLinearGradient(300, 0, 0, 0);
gradientStroke5_1.addColorStop(0, "#9f89d2");
gradientStroke5_1.addColorStop(1, "#8c9cd3");

var gradientStroke5_2 = ctx3.createLinearGradient(300, 0, 0, 0);
gradientStroke5_2.addColorStop(0, "#03a9f5");
gradientStroke5_2.addColorStop(1, "#9790d2");

var gradientStroke5_3 = ctx3.createLinearGradient(300, 0, 0, 0);
gradientStroke5_3.addColorStop(0, "#1ce7bc");
gradientStroke5_3.addColorStop(1, "#5a5a5a");

let myChart3 = new Chart(ctx3, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: dataValues,
            backgroundColor: [gradientStroke5, gradientStroke5_1, gradientStroke5_2],
            hoverBackgroundColor: gradientStroke5_3,
            fontColor: ['#49e3d1', '#9a8fd3', '#479ee5'],
            borderColor: '#fff',
            borderWidth: 5,
            hoverBorderWidth: 9,
            hoverBorderColor: gradientStroke5_3,
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
      tooltips: {
        enabled: false
      },
      layout: {
            padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: 100
            }
        },
      legend: {
        display:false
      },
      pieceLabel: {
        render: 'percentage',
        fontColor: ['white','white','white'],
        fontSize: 10,
        precision: 2
      },
      legendCallback: function(chart) {
          var text = [];
          text.push('<ul class="chart-data">');
          for (var i=0; i<chart.data.datasets[0].data.length; i++) {
            //console.log(chart.data.labels)
            //console.log(((chart.data.datasets[0].data[i]/chart.data.datasets[0]._meta[2].total) * 100).toFixed(1)); // see what's inside the obj.
            percent = ((chart.data.datasets[0].data[i]/chart.data.datasets[0]._meta[0].total) * 100).toFixed(1) + '%';
            text.push('<li><div class="ticks" style="background:' + chart.data.datasets[0].fontColor[i] + '"></div>');
            //text.push('<span style="background-color:' + chart.data.datasets[i].borderColor + '">' + chart.data.datasets[i].label + '</span>');
            text.push(chart.data.labels[i] + '<span style="float:right;color:#d2d2d2">' + percent + '</span>');
            text.push('</li>');
          }
          text.push('</ul>');
        return text.join("");
      },
      cutoutPercentage: 0,
      layout: {
        padding: {
          left: 0,
          right: 0,
          top: 70,
          bottom: 0
        }
        },
        responsive: false,
    }
});

document.getElementById('chart-legends').innerHTML = myChart3.generateLegend();
}
