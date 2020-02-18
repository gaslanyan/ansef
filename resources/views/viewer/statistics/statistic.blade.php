@extends('layouts.master')


@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">

    <div class="container">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
            </div><br/>
        @endif
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-12">
                 <div class="card" >
                    <div class="card-header">Statistics</div>
                    <div class="card-body" style="overflow:auto;">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a id="single-tab" class="nav-link active" role="tab" aria-controls="single" aria-selected="true" data-toggle="pill" href="#single" onclick="clearchart()">Single</a>
                            </li>
                            <li class="nav-item">
                                <a id="multiple-tab" class="nav-link" role="tab" aria-controls="multiple" aria-selected="false" data-toggle="pill" href="#multiple" onclick="clearchart()">Multiple</a>
                            </li>
                        </ul>

                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="tab-content">
                                        <div id="single" class="tab-pane fade in show active" role="tabpanel">
                                            <div class="">
                                                <select class="form-control -align-center statistic" >
                                                    <option value="none">Competition </option>
                                                    @foreach($result as $r)
                                                    <option value = {{$r->id}}>{{$r->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="">
                                                <select class="form-control -align-center statistic_x" style="min-height:300px;" multiple>
                                                </select>
                                            </div>
                                            <div class="">
                                                <select class="form-control -align-center statistic_y">
                                                    <option value="none">Choose Y axis Value</option>
                                                    <option value = "proposalcount">Count of proposals</option>
                                                    <option value="avg_score">Average overall score</option>
                                                    <option value = "max_overall_score">Max overall score</option>
                                                    <option value = "min_overall_score">Min overall score</option>
                                                    <option value="participant_avg_age">Average participant age</option>
                                                    <option value="participant_max_age">Max participant age</option>
                                                    <option value="participant_min_age">Min participant age</option>
                                                    <option value="average_participant_sex">Average participant sex</option>
                                                    <option value="pi_avg_age">Average PI age</option>
                                                    <option value="pi_max_age">Max PI age</option>
                                                    <option value="pi_min_age">Min PI age</option>
                                                    <option value="average_pi_sex">Average PI sex</option>
                                                    <option value="pi_publication_avg">Average PI ANSEF publication count</option>
                                                    <option value="pi_publication_year_avg">Average publication year</option>
                                                    <option value="total_amounts_of_funds">Total amount of funds</option>
                                                    <option value="avg_salaries">Average salaries</option>
                                                    <option value="avg_travel">Average travels</option>
                                                    <option value="avg_equipment">Average equipment and supplies</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="multiple" class="tab-pane fade in" role="tabpanel">
                                            <div class="">
                                                <select class="form-control -align-center statistic_m" style="min-height:150px;" multiple>
                                                    @foreach($result as $r)
                                                        <option value = {{$r->id}}>{{$r->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="">
                                                <select class="form-control -align-center statistic_mx" style="min-height:100px;" multiple>
                                                </select>
                                            </div>
                                            <div class="">
                                                <select class="form-control -align-center statistic_prop_state" style="min-height:100px;" multiple>
                                                    <option value="in-progress">In-progress</option>
                                                    <option value="submitted">Submitted</option>
                                                    <option value="in-review">In Review</option>
                                                    <option value="complete">Complete</option>
                                                    <option value="awarded">Awarded</option>
                                                    <option value="unsuccessfull">Unsuccessfull</option>
                                                    <option value="approved 1">Approved 1</option>
                                                    <option value="approved 2">Approved 2</option>
                                                    <option value="disqualified">Disqualified</option>
                                                    <option value="finalist">Finalist</option>
                                                </select>
                                            </div>
                                            <div class="">
                                                <select class="form-control -align-center statistic_my">
                                                    <option value="none">Choose Y axis Value</option>
                                                    <option value = "proposalcount">Count of proposals</option>
                                                    <option value="avg_score">Average overall score</option>
                                                    <option value = "max_overall_score">Max overall score</option>
                                                    <option value = "min_overall_score">Min overall score</option>
                                                    <option value="participant_avg_age">Average participant age</option>
                                                    <option value="participant_max_age">Max participant age</option>
                                                    <option value="participant_min_age">Min participant age</option>
                                                    <option value="average_participant_sex">Average participant sex</option>
                                                    <option value="pi_avg_age">Average PI age</option>
                                                    <option value="pi_max_age">Max PI age</option>
                                                    <option value="pi_min_age">Min PI age</option>
                                                    <option value="average_pi_sex">Average PI sex</option>
                                                    <option value="pi_publication_avg">Average PI ANSEF publication count</option>
                                                    <option value="pi_publication_year_avg">Average publication year</option>
                                                    <option value="total_amounts_of_funds">Total amount of funds</option>
                                                    <option value="avg_salaries">Average salaries</option>
                                                    <option value="avg_travel">Average travels</option>
                                                    <option value="avg_equipment">Average equipment and supplies</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <canvas id="canvas" height="280" width="600"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js" charset="utf-8"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js" charset="utf-8"></script>
                            <script>
                                var url = "{{url('viewer/statistics/chart')}}";
                                var competitionsname = new Array();
                                var Labels = new Array();
                                var proposals = new Array();
                                var chartObject;

                                function clearchart() {
                                    $('.statistic').val($(".statistic option:first").val());
                                    $('.statistic_y').val($(".statistic_y option:first").val());
                                    $('.statistic_x').find('option').remove();
                                    $('.statistic_x').find('optgroup').remove();
                                    $('.statistic_mx').find('option').remove();
                                    if(chartObject) chartObject.destroy();
                                }

                                function updateChart() {
                                    var value_y = $('.statistic_y').val();
                                    var competitionsname = [];
                                    var value_x = [];
                                    var type = $('.statistic').val();
                                    $.each($(".statistic_x option:selected"), function(){
                                        competitionsname.push($(this).attr('id'));
                                        value_x.push($(this).val());
                                    });

                                    if(value_x.length == 0 || (value_x.length == 1 && value_x[0] == 'none') || value_y == 'none' || type == 'none') {
                                        if(chartObject) chartObject.destroy();
                                        return;
                                    }

                                    $.ajax({
                                        url: '/viewer/statistics/y_result',
                                        type: 'POST',
                                        //context: {element: $(this)},
                                        data: {_token: CSRF_TOKEN, value_y: value_y,value_x:value_x,type:type},
                                        dataType: 'JSON',
                                        success: function (data) {
                                            proposals=Object.values(data);

                                            if(chartObject) chartObject.destroy();
                                            var ctx = document.getElementById("canvas").getContext('2d');
                                            chartObject = new Chart(ctx, {
                                                type: 'bar',
                                                data: {
                                                    labels:Object.keys(data),
                                                    datasets: [{
                                                        label: 'Proposal Count',
                                                        backgroundColor: "red",
                                                        data: proposals,
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    scales: {
                                                        yAxes: [{
                                                            ticks: {
                                                                beginAtZero:true
                                                            }
                                                        }]
                                                    }
                                                }
                                            });
                                        },
                                        error: function (response) {
                                            console.log(response);
                                        }
                                    });
                                }

                                function updateChartM() {
                                        var value_y = $('.statistic_my').val();
                                        var type = $('.statistic_m').val();
                                        var statistic_prop_state  = $('.statistic_prop_state').val();
                                        var categories = [];
                                        var value_x = [];
                                        var c_n = [];
                                        var data_d  =[];
                                        var datasets=[];
                                        $.each($(".statistic_m option:selected"), function(){
                                            value_x.push($(this).val());
                                        });
                                        $.each($(".statistic_mx option:selected"), function(){
                                            categories.push($(this).val());
                                        });

                                        if(value_x.length == 0 || categories.length == 0 || statistic_prop_state.length == 0 || value_y == 'none') {
                                            if(chartObject) chartObject.destroy();
                                            return;
                                        }

                                        $.ajax({
                                            url: '/viewer/statistics/my_result',
                                            type: 'POST',
                                            data: {_token: CSRF_TOKEN, value_my:value_y, value_mx:value_x, value_cat:categories, statistic_prop_state:statistic_prop_state},
                                            dataType: 'JSON',
                                            success: function (data) {
                                                comp_name  = Object.keys(data);
                                                alldata = Object.values(data);
                                                backgroundColor = ['red','blue', 'orange', 'purple', 'yellow'];
                                                categories = Object.keys(alldata[0]);
                                                datasets = [];
                                                for(var i = 0; i<categories.length; i++){
                                                    data_d = [];
                                                    for(var j=0; j<alldata.length; j++){
                                                        data_d.push(alldata[j][categories[i]]);
                                                    }
                                                    datasets.push({
                                                        label:categories[i],
                                                        backgroundColor:backgroundColor[i],
                                                        data:data_d,
                                                        borderWidth:1
                                                    });
                                                }

                                                // console.log(alldata[j]);
                                                if(chartObject) chartObject.destroy();
                                                var ctxx = document.getElementById("canvas").getContext('2d');
                                                chartObject = new Chart(ctxx, {
                                                    type: 'bar',
                                                    data: {
                                                        labels:comp_name,
                                                        datasets: datasets
                                                    },
                                                    options: {
                                                        scales: {
                                                            yAxes: [{
                                                                ticks: {
                                                                    beginAtZero:true
                                                                }
                                                            }]
                                                        }
                                                    }
                                                });
                                            },
                                            error: function (response) {
                                                console.log(response);
                                            }
                                        });
                                        //});

                                }

                                $(document).ready(function(){
                                    $(document).on("change", '.statistic_x', function() {
                                        updateChart();
                                    });

                                    $(document).on("change", '.statistic', function() {
                                        statisticval = $(this).val();
                                        $('.statistic_x').find('option').remove();
                                        $('.statistic_x').find('optgroup').remove();
                                        $.ajax({
                                            url: '/gclfs',
                                            type: 'POST',
                                            context: { element: $(this) },
                                            data: { _token: CSRF_TOKEN, value: statisticval },
                                            dataType: 'JSON',
                                            success: function(data) {
                                                console.log(data);
                                                for (var i in data) {
                                                    $('.statistic_x').append(' <optgroup class="text-capitalize" label ="' + data[i].parent + '" value="' + data[i].parent + ' " id = "' + data[i].parent + '">' + data[i].parent + '</optgroup>');
                                                    for (var s in data[i].sub) {
                                                        $('.statistic_x').append(' <option class="text-capitalize"  value="' + data[i].sub[s].id + ' " id = "' + data[i].sub[s].id + '">' + data[i].sub[s].title + '</option>');
                                                    }
                                                }
                                                updateChart();
                                            },
                                            error: function(data) {
                                                console.log(data);
                                            }
                                        });
                                    });

                                    $(document).on("change", '.statistic_m', function() {
                                        statisticval = $(this).val();
                                        $('.statistic_mx').find('option').remove();
                                        $.ajax({
                                            url: '/gclfsm',
                                            type: 'POST',
                                            context: { element: $(this) },
                                            data: { _token: CSRF_TOKEN, value: statisticval },
                                            dataType: 'JSON',
                                            success: function(data) {
                                                for (var i in data) {
                                                    $('.statistic_mx').append(' <option class="text-capitalize"  value="' + i + ' " id = "' + i + '">' + data[i].parent + '</option>');
                                                }
                                                updateChartM();
                                            },
                                            error: function(data) {
                                                console.log(data);
                                            }
                                        });
                                    });

                                    $('.statistic_y').change(function(){
                                        updateChart();
                                    });

                                      /*For Multiple Competition*/
                                    $('.statistic_mx').change(function(){
                                        updateChartM();
                                    });

                                    $('.statistic_prop_state').change(function(){
                                        updateChartM();
                                    });

                                    $('.statistic_my').change(function(){
                                        updateChartM();
                                    });
                                });
                            </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
