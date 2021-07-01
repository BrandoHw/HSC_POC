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


<div id="column-chart">
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
                        categories: response['dates']
                    }
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