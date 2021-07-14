<div class="row">
    <div id="timeline-chart" style="width:95%">
    </div>
</div>

<script>
$(function() {
  var options = {
    series: [],
    noData: {
        text: 'No Data'
    },
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
  getTimelineChartData = function(input_tag_mac){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '{{ route("reports.timeline")}}',
        type: "get",
        data: { 
          date_range: $("#datepicker").val(),
          tag_mac: input_tag_mac,
        },
        success:function(response){
            console.log(response)
            timelineChart.updateSeries(response['timeline']);
        },
        error:function(error){
            console.log(error)
        }
    })
  }

  $('#draw-btn').on('click', function (){
    var selected_data = $('#selUser').select2('data');
    console.log(selected_data);
    console.log(selected_data[0].tag_mac);

    if (selected_data.length > 0)
      getTimelineChartData(selected_data[0].tag_mac);
  });
});
</script>