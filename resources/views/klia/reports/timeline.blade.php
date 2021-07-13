<div class="row">
    <div id="timeline-chart" style="width:95%">
    </div>
</div>

<script>
        var options = {
          series: [
          {
            data: [
              {
                x: 'Code',
                y: [
                  new Date('2019-03-02').getTime(),
                  new Date('2019-03-04').getTime()
                ]
              },
              {
                x: 'Test',
                y: [
                  new Date('2019-03-04').getTime(),
                  new Date('2019-03-08').getTime()
                ]
              },
              {
                x: 'Validation',
                y: [
                  new Date('2019-03-08').getTime(),
                  new Date('2019-03-12').getTime()
                ]
              },
              {
                x: 'Deployment',
                y: [
                  new Date('2019-03-12').getTime(),
                  new Date('2019-03-18').getTime()
                ]
              }
            ]
          }
        ],
          chart: {
          height: 350,
          type: 'rangeBar'
        },
        plotOptions: {
          bar: {
            horizontal: true
          }
        },
        xaxis: {
          type: 'datetime'
        }
        };

        var timelineChart = new ApexCharts(document.querySelector("#timeline-chart"), options);
        timelineChart.render();

        console.log(new Date('2019-03-04').getTime());
        console.log(new Date('2019-03-08').getTime());
</script>