<div class="row">
    <div id="timeline-chart" style="width:95%">
    </div>
</div>

<script>
$(function() {
  var options = {
    series: [
    {
      data: [
        {
          x: 'Code',
          y: [
            new Date('2019-03-02 12:50:32').getTime(),
            new Date('2019-03-02 12:51:32').getTime()
          ]
        },
        {
          x: 'Test',
          y: [
            new Date('2019-03-02 12:54:32').getTime(),
            new Date('2019-03-02 12:55:32').getTime(),
          ]
        },
        {
          x: 'Validation',
          y: [
            new Date('2019-03-02 12:50:32').getTime(),
            new Date('2019-03-02 12:56:32').getTime(),
          ]
        },
        {
          x: 'Deployment',
          y: [
            new Date('2019-03-02 12:58:32').getTime(),
            new Date('2019-03-02 12:59:32').getTime(),
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
console.log( new Date('2019-03-02 12:50:32').getTime());
  getTimelineChartData = function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("reports.timeline")}}',
            type: "get",
            success:function(response){
                console.log(response)
                timelineChart.updateSeries(response['timeline']);
            },
            error:function(error){
                console.log(error)
            }
        })
        .always(function () {
            setTimeout(getTimelineChartData, 60000);
        });
    }

    getTimelineChartData();
});
</script>