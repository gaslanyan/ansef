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
                    <div class="card-body card_body">
                        <div class="form-group col-lg-12 align-items-center">
                        <div class="row">
                            <div class="col-md-4 col-md-offset-1">
                                <select class="form-control -align-center statistic" >
                                    <option>Statistic for </option>
                                    <option value = 'competition'>Competition</option>
                                    <option value = 'pi'>PI</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-md-offset-1">
                                <select class="form-control -align-center statistic_x" multiple>
                                    <option>Choose X axis Value</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-md-offset-1">
                                <select class="form-control -align-center statistic_y">
                                    <option>Choose Y axis Value</option>
                                </select>
                            </div>
                        </div>
                        </div>

                        <div class="box-body col-md-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><b>Charts</b></div>
                                        <div class="panel-body">
                                            <canvas id="canvas" height="280" width="600"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js" charset="utf-8"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js" charset="utf-8"></script>
                            <script>
                                var url = "{{url('viewer/statistics/chart')}}";
                                var competitionsname = new Array();
                                var Labels = new Array();
                                var proposals = new Array();
                                $(document).ready(function(){
                                      $('.statistic_y').change(function(){
                                        var value_y = $('.statistic_y').val();

                                          var competitionsname = [];
                                          var value_x = [];
                                          var type = $('.statistic').val();
                                          $.each($(".statistic_x option:selected"), function(){
                                              competitionsname.push($(this).attr('id'));
                                              value_x.push($(this).val());
                                          });

                                    $.ajax({
                                        url: '/viewer/statistics/y_result',
                                        type: 'POST',
                                        //context: {element: $(this)},
                                        data: {_token: CSRF_TOKEN, value_y: value_y,value_x:value_x,type:type},
                                        dataType: 'JSON',
                                        success: function (data) {
                                           console.log(data);



                                        //competitionsname= ['1996','1985','1998','2000','1992'];
                                        Labels=['TTTT','ggggg','jjjjj'];
                                        proposals=['2000','3000','5000','2500','1200'];

                                      //  });

                                        var ctx = document.getElementById("canvas").getContext('2d');
                                        var myChart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels:competitionsname,
                                                datasets: [{
                                                    label: 'Infosys Price',
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
                                    //});
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
