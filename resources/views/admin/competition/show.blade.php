@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header"><b>Competition:</b> @if(!empty($com['title'])){{$com['title']}}@endif
                        @if(!empty($com['state']))({{$com['state'] == 'enable' ? 'Enabled' : 'Disabled'}})@endif
                    </div>

                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Competition parameters</h3>
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-question-circle margin-r-5"> </i>Description:</strong>
                                        <p>@if(!empty($com['description'])){{$com['description']}}@endif</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-comment margin-r-5"></i> Comments:</strong>
                                        <p>@if(!empty($com['comments'])){{$com['comments']}}@endif</p>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-calendar margin-r-5"></i>Submission start
                                            date:</strong>
                                        <p>@if(!empty($com['submission_start_date'])){{$com['submission_start_date']}}@endif</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-calendar margin-r-5"></i>Submission end
                                            date:</strong>
                                        <p>@if(!empty($com['submission_end_date'])){{$com['submission_end_date']}}@endif</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-calendar margin-r-5"></i>Announcement date:</strong>
                                        <p>@if(!empty($com['announcement_date'])){{$com['announcement_date']}}@endif</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-calendar margin-r-5"></i>Project Start
                                            Date:</strong>
                                        <p>@if(!empty($com['project_start_date'])){{$com['project_start_date']}}@endif</p>
                                    </div>
                                </div>
                                <hr>
                                <!-- /.box-body -->
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <strong class="col-md-12"><i class="fa fa-list-ol margin-r-5"></i>Allowed categories:</strong><br/><br/>
                                            <?php if (!empty($cats)): ?>
                                            <ol class="col-md-6">
                                                <?php foreach ($cats as $index => $cat) : ?>
                                                <li class="cat_li"><p>{{$cat['parent']}}</p>
                                                </li>
                                                <?php endforeach; ?>
                                            </ol>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <!-- /.box-body -->
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-calendar margin-r-4"></i>Duration:</strong>
                                        <span>@if(!empty($com['duration'])){{$com['duration'].' months'}}@endif</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-money margin-r-4"></i>Min budget limit:</strong>
                                        <span>@if($com['min_budget'] == 0) {{'None'}} @else {{'$'.$com['min_budget']}} @endif</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-money margin-r-4"></i>Max budget limit:</strong>
                                        <span>@if($com['max_budget'] == 0) {{'None'}} @else {{'$'.$com['max_budget']}} @endif</span>
                                    </div>

                                    <div class="col-md-4">
                                        <strong><i class="fa fa-birthday-cake margin-r-4"></i>Min
                                            age limit:</strong>
                                        <span>@if($com['min_age'] == 0){{'None'}} @else {{$com['min_age'].' years'}}  @endif</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-birthday-cake margin-r-4"></i>Max
                                            age limit:</strong>
                                        <span>@if($com['max_age'] == 100){{'None'}} @else {{$com['max_age'].' years'}} @endif</spanp>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-bookmark margin-r-4"></i> Allow foreign PI:</strong>
                                        <span>@if($com['allow_foreign'] == 0){{'No'}}@else {{'Yes'}}@endif</span>
                                    </div>
                                </div>
                                <hr>
                                <!-- /.box-body -->
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-graduation-cap margin-r-5"></i> Min
                                            Degree:</strong>
                                        <span>@if($com['min_degree']['text'] == 'No degree'){{'Any'}} @else {{$com['min_degree']['text']}} @endif</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-graduation-cap margin-r-5"></i> Max
                                            Degree:</strong>
                                        <span>@if($com['max_degree']['text'] == 'No degree'){{'Any'}} @else {{$com['max_degree']['text']}} @endif</span>
                                    </div>
                                </div>
                                <hr>
                                <!-- /.box-body -->
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">

                                    <div class="col-md-4">
                                        <strong><i class="fa fa-calendar margin-r-5"></i>First Report Date:</strong>
                                        <p>@if(!empty($com->first_report)){{$com->first_report}} @endif</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-calendar margin-r-5"></i>Second Report Date:</strong>
                                        <p>@if(!empty($com->second_report)){{$com->second_report}} @endif</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-bookmark margin-r-5"></i>Min. recommendations:</strong>
                                        <p>@if($com->recommendations_id == 0){{'None'}}@else {{$com->recommendations_id}}@endif</p>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    @if($com->additional)
                                        @php $additional = json_decode($com->additional)@endphp
                                        @foreach ($additional as $index => $item)
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-calendar margin-r-5"></i>{{str_replace_first('_', " ",str_replace_first("_", " ", $index))}}
                                                    :</strong>
                                                <p>{{$item.'' == '0' || $item.'' == '' ? 'None' : $item}} </p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <hr>

                            </div>@if(!empty($st->all()))
                                <div class="box-body col-md-12">
                                    <div class="row">

                                        <div class="col-md-12 border-bottom mb-3">
                                            <strong><i class="fas fa-star margin-r-5"></i>Score
                                                Types:</strong>
                                        </div>
                                        @foreach($st as $key=>$value)
                                            {{--                                           @php dd($value);@endphp--}}

                                            <div class="col-md-6">
                                                <strong><i class="fas fa-tag margin-r-5"></i>Name:</strong>
                                                <span>@if(!empty($value->name)){{$value->name}} @endif</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Description:</strong>
                                                <p>@if(!empty($value->description)){{$value->description}} @endif</p>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Min:</strong>
                                                <span>{{$value->min}}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Max:</strong>
                                                <span>{{$value->max}}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Weight value:</strong>
                                                <span>@if(!empty($value->weight)){{$value->weight}} @endif</span>
                                            </div><br/><br/>
                                        @endforeach

                                    </div>
                                </div>
                            @endif
                            @if(!empty($bc->all()))
                            <div class="box-body col-md-12">
                                <div class="row">
                                        <div class="col-md-12 border-bottom mb-3">
                                            <strong><i class="fas fa-dollar-sign margin-r-5"></i>Budget
                                                Categories:</strong>
                                        </div>
                                        @foreach($bc as $key=>$value)
                                            {{--                                           @php dd($value);@endphp--}}

                                            <div class="col-md-3">
                                                <strong><i class="fas fa-tag margin-r-5"></i>Name:</strong>
                                                <p>@if(!empty($value->name)){{$value->name}} @endif</p>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Min value:</strong>
                                                <p>{{'$'.$value->min}}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Max value:</strong>
                                                <p>{{'$'.$value->max}}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Weight value:</strong>
                                                <p>{{$value->weight}}</p>
                                            </div>
                                        @endforeach

                                </div>
                            </div>
                            @endif
                            @if(!empty($rr->all()))
                            <div class="box-body col-md-12">
                                <div class="row">

                                        <div class="col-md-12 border-bottom mb-3">
                                            <strong><i class="fas fa-ruler-vertical margin-r-5"></i>Ranking
                                                Roles:</strong>

                                        </div>
                                        @foreach($rr as $key=>$value)
                                            {{--                                                                                       @php dd($value);@endphp--}}

                                            <div class="col-md-10">
                                                <strong><i class="fas fa-tag margin-r-5"></i>Sql:</strong>
                                                <p>@if(!empty($value->sql)){{$value->sql}} @endif</p>
                                            </div>
                                            <div class="col-md-2">
                                                <strong>
                                                    Value:</strong>
                                                <p>@if(!empty($value->value)){{$value->value}} @endif</p>
                                            </div>
                                            {{--                                            <div class="col-md-4">--}}
                                            {{--                                                <strong><i class="fa fa-user margin-r-5"></i>User--}}
                                            {{--                                                    value:</strong>--}}
                                            {{--                                                <p>@if(!empty($value->max)){{$value->max}} @endif</p>--}}
                                            {{--                                            </div>--}}

                                        @endforeach

                                </div>
                                <hr>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

