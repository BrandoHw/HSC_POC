<style>
    @import url(https://fonts.googleapis.com/css?family=Roboto);

    body {
    font-family: Roboto, sans-serif;
    }

    #chart {
    max-width: 650px;
    margin: 35px auto;
    }
</style>

<div class="row">
    <div id="pie-chart" style="width:95%">
    </div>
    <a href="#" data-toggle="tooltip" data-placement="right" title="A tag is considered Clocked-In if it has appeared within range of a gateway in the current day" style="cursor: pointer; left-padding:0">
        <i class="ri-information-fill"></i>
    </a>
</div>

<script>
$(function() {

    var options = {
        title: {
            text: 'Currently Active Tags',
        },
        series: [],
        noData: {
            text: 'Loading...'
        },
        chart: {
          type: 'pie',
        },
        labels: ['Clocked-In Beacons', 'Idle Beacons'],
        legend: {
            show: true,
            position: 'bottom',
        },
        colors:['#0ABAB5',  '#00726F'],
        // Responsive options create graphical errors when updating with AJAX
        // responsive: [{
        //   breakpoint: 240,
        //   options: {
        //     chart: {
        //       width: 200
        //     },
        //     legend: {
        //       position: 'bottom'
        //     }
        //   }
        // }]
    };
    var pieChart = new ApexCharts(document.querySelector("#pie-chart"), options);
    pieChart.render();
    getPieChartData = function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("home.activeTags")}}',
            type: "get",
            success:function(response){
                console.log(response)
                var array = [response['active_tags_count'], response['tags_count']];
                pieChart.updateSeries(array);
            },
            error:function(error){
                console.log(error)
            }
        })
        .always(function () {
            setTimeout(getPieChartData, 60000);
        });
    }

    getPieChartData();
});
</script>