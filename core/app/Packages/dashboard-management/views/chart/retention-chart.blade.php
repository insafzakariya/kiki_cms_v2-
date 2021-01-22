@extends('layouts.back.master') @section('current_title','WELLCOME tO SAMBOLE ADMIN WEB PORTAL')
@section('css')
<link href="{{asset('assets/back/monthpicker/monthpicker.css')}}" rel="stylesheet" type="text/css">

<style type="text/css">
.clr-yellow div {
    background-color: #f8ac59;
    color: #ffffff;
}
.clr-blue div {
    background-color: #1c84c6;
    color: #FFFFFF;
}
.clr-red div {
    background-color: #ed5565;
    color: #ffffff;
}
.clr-green div {
    background-color: #1ab394;
    color: #ffffff;
}
.clr-dark-green div{
    background-color: #0b705b;
    color: #ffffff;
}
.clr-dark-blue div{
    background-color: #0f4d75;
    color: #ffffff;
}
.clr-ibox
{
    background-color: #595959;
}
#alertmod_table_list_2 {
    top: 900px!important;
}
.wrapper-content {
    padding-top: 0;
}
.no-margins {
    /*text-align: center;*/
    font-weight: bold;
}
.margins {
    margin-top: 20px;
}
.label{
    background-color: transparent;
    margin-top: -8px!important;
}


</style>
@stop
@section('current_path')
@stop
@section('page_header')


@stop
@section('content')
<div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">
                <div class="panel-body">
                <div class="modal"><!-- Place at bottom of page --></div>

                    <div class="col-sm-10">
                    <input id="startDate" type="text" />
                  
                    <button onclick="findData();">Search</button>
                    <div align ="right">
                        <div>
                            <label>Users: </label>
                            <label id="user_count">-</label>
                        </div>
                        <div>
                            <label>Retention:</label>
                            <label id="retention">-</label>
                        </div>
                    
                    
                    </div>
                   
                    <canvas id="dailytransaction_chart" width="200" height="100"></canvas>
                    </div>
                
                </div>
            </div>
        </div>
</div>

@stop
@section('js')

<script src="{{asset('assets/back/chartjs/Chart.min.js')}}"></script>
<script src="{{asset('assets/back/chartjs/utils.js')}}"></script>
<script src="{{asset('assets/back/monthpicker/monthpicker.min.js')}}"></script>




<script type="text/javascript">
     var d = new Date();
    d.setMonth(d.getMonth()+1);
    
    $('#startDate').val(d.getMonth()+'/'+d.getFullYear());
    $('#startDate').Monthpicker({
        // format: 'yyyy-mm',
        monthLabels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "July", "Aug", "Sep", "Oct", "Nov", "Dec"],
        // onSelect: function () {
        //     $('#endDate').Monthpicker('option', { minValue: $('#startDate').val() });
        // }
    });
    
    

    var ctx = document.getElementById('dailytransaction_chart');
    var dailytransaction_chart = new Chart(ctx, {
        type: 'bar',
        data:[],
        options: {
            "hover": {
              "animationDuration": 0
            },
            "legendCallback": function() {
                charValueOnTop();
            },
            "animation": {
                "duration": 1,
                "onComplete": function() {
                    charValueOnTop();
                }
           
            },
            legend: {
            "display": true,
            "position": 'right' 
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        drawOnChartArea: false
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    },
                    gridLines: {
                        drawOnChartArea: false
                    }
                }]
            },
            title: {
                display: true,
                text: 'Retention'
            }
            
        }
    });

    // //Date Format
    // function formatDate(date) {
    //     var d = new Date(date),
    //         month = '' + (d.getMonth() + 1),
    //         day = '' + d.getDate(),
    //         year = d.getFullYear();

    //     if (month.length < 2) 
    //         month = '0' + month;
    //     if (day.length < 2) 
    //         day = '0' + day;

    //     return [year, month, day].join('-');
    // }
    

    load_dailytransaction_chart(dailytransaction_chart);
    //Find Data
    function findData(){
        load_dailytransaction_chart(dailytransaction_chart);
    }
    //Load Data to Subscribe Chart
    function load_dailytransaction_chart(dailytransaction_chart){
        var start_date=$('#startDate').val();
        $.ajax({
            method: "GET",
            url: '{{url('dashboard/data/retention')}}',
            data: {start_date},
        }).done(function( finel_data ) {
            dailytransaction_chart.data= finel_data.chart_data;
            dailytransaction_chart.update(); 
            // charValueOnTop();
            $('#user_count').text(finel_data.total_retention_user_counts);
            $('#retention').text(finel_data.retention+'%');
            console.log(finel_data.chart_data);
            
        });
       

    }

    function charValueOnTop(){
        var chartInstance = dailytransaction_chart,
        ctx = chartInstance.ctx;

        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';

        chartInstance.data.datasets.forEach(function(dataset, i) {
        var meta = chartInstance.controller.getDatasetMeta(i);
        console.log(meta.hidden);
            if(meta.hidden !=true){
                meta.data.forEach(function(bar, index) {
                    var data = dataset.data[index];
                
                    console.log(dataset.hidden);
                    if(!dataset.hidden){
                        ctx.fillText(data, bar._model.x, bar._model.y - 5);
                    }
            
                });
            }
        
        });
    }
    
    
</script>

@stop