@extends('layouts.back.master') @section('current_title','WELLCOME tO SAMBOLE ADMIN WEB PORTAL')
@section('css')
<link href="{{asset('assets/back/monthpicker/monthpicker.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{asset('assets/back/flatpicker/flatpickr.min.css')}}" />
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
                    <input type="date" id="start_date" name="start_date" class="">
                    <button onclick="findData();">Search</button>
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
<script src="{{asset('assets/back/flatpicker/flatpicker')}}"></script>



<script type="text/javascript">
    var current_date = new Date();
    var end_date=formatDate(current_date);
    var start_date=formatDate(current_date.setDate(current_date.getDate() - 7));
     $("#start_date").flatpickr(
            {
                enableTime: false,
                dateFormat: "Y-m-d",
                mode: "range",
                defaultDate: [start_date, end_date],
                onChange: function(dates) {
                    const dateArr = dates.map(date => this.formatDate(date, "Y-m-d"));
                    if (dateArr.length == 2) {
                        start_date = dateArr[0];
                        end_date = dateArr[1];
                        console.log(start_date);

                        // interact with selected dates here
                    }
                }
            }
        );

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
                text: 'Transaction'
            }
            
        }
    });

    //Date Format
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year, month, day].join('-');
    }
    

    load_dailytransaction_chart(dailytransaction_chart);
    //Find Data
    function findData(){
        load_dailytransaction_chart(dailytransaction_chart);
    }
    //Load Data to Subscribe Chart
    function load_dailytransaction_chart(dailytransaction_chart){
        console.log(start_date);
        $.ajax({
            method: "GET",
            url: '{{url('dashboard/data/dailytransaction')}}',
            data: {start_date,end_date},
        }).done(function( chart_data ) {
            dailytransaction_chart.data= chart_data;
            dailytransaction_chart.update(); 
            // charValueOnTop();
            console.log(chart_data);
            
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