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
    <div id="column-chart" style="width:95%">
    </div>
    <a href="#" data-toggle="tooltip" data-placement="right" title="Attendance is only recorded if the Tag/Gateway have been assigned a User/Location" style="cursor: pointer; left-padding:0">
        <i class="ri-information-fill"></i>
    </a>
</div>

<script>
    var options = {
        chart: {
            type: 'bar'
        },
        series: [],
        title: {
            text: 'Attendance Last 7 Days',
        },
        noData: {
            text: 'Loading...'
        },
        colors:['#0ABAB5',  '#00726F'],
    }

    var columnChart = new ApexCharts(document.querySelector("#column-chart"), options);
    columnChart.render();

    getColumnChartData = function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("home.attendanceWeek")}}',
            type: "get",
            success:function(response){
                console.log(response)
                var options = {
                    chart: {
                        type: 'bar'
                    },
                    xaxis: {
                        categories: response['labels']
                    },
                }
                columnChart.updateOptions(options);
                columnChart.updateSeries([{
                    name: 'Attendance',
                    data: response['series'],
                }])
                
            },
            error:function(error){
                console.log(error)
            }
        })
        .always(function () {
            setTimeout(getColumnChartData, 60000);
        });
    }
    getColumnChartData();
</script>