@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Competition</div>

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
                                <h3 class="box-title">Competition information</h3>
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-heading margin-r-5"></i> Competition title:</strong>
                                        <p>@if(!empty($com['title'])){{$com['title']}}@endif</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-bookmark margin-r-5"></i> State:</strong>
                                        <p>@if(!empty($com['state'])){{$com['state']}}@endif</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-info margin-r-5"> </i>Description:</strong>
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
                                            <strong class="col-md-12"><i class="fa fa-list-ol margin-r-5"></i>Categories:</strong>
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
                                        <p>@if(!empty($com['duration'])){{$com['duration'].' months'}}@endif</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-money margin-r-4"></i>Min budget:</strong>
                                        <p>@if(!empty($com['min_budget'])){{$com['min_budget'].'$'}} @endif</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-money margin-r-4"></i>Max budget:</strong>
                                        <p>@if(!empty($com['max_budget'])){{$com['max_budget'].'$'}}@endif</p>
                                    </div>

                                    <div class="col-md-4">
                                        <strong><i class="fa fa-birthday-cake margin-r-4"></i>Min
                                            age:</strong>
                                        <p>@if(!empty($com['min_age'])){{$com['min_age'].' years'}}  @endif</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-birthday-cake margin-r-4"></i>Max
                                            age:</strong>
                                        <p>@if(!empty($com['min_age'])){{$com['max_age'].' years'}} @endif</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fa fa-bookmark margin-r-4"></i> Allow foreign:</strong>
                                        <p>@if($com['allow_foreign'] == 0){{'false'}}@else {{'true'}}@endif</p>
                                    </div>
                                </div>
                                <hr>
                                <!-- /.box-body -->
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-graduation-cap margin-r-5"></i> Min Level
                                            Degree:</strong>
                                        <p>@if(!empty($com['min_degree'])){{$com['min_degree']['text']}} @endif</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-graduation-cap margin-r-5"></i> Max Level
                                            Degree:</strong>
                                        <p>@if(!empty($com->max_degree['text'])){{$com->max_degree['text']}} @endif</p>
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
                                        <strong><i class="fa fa-bookmark margin-r-5"></i>Recommendations:</strong>
                                        <p>@if($com->recommendations_id == 0){{'No'}}@else {{'Yes'}}@endif</p>
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
                                                <p>{{$item}} </p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <hr>

                            </div>@if(!empty($st->all()))
                                <div class="box-body col-md-12">
                                    <div class="row">

                                        <div class="col-md-12 border-bottom mb-3">
                                            <strong><i class="fa fa-heading margin-r-5"></i>Competition Score
                                                Type:</strong>
                                        </div>
                                        @foreach($st as $key=>$value)
                                            {{--                                           @php dd($value);@endphp--}}

                                            <div class="col-md-6">
                                                <strong><i class="fa fa-heading margin-r-5"></i>Name:</strong>
                                                <p>@if(!empty($value->name)){{$value->name}} @endif</p>
                                            </div>
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-text-width margin-r-5"></i>Description:</strong>
                                                <p>@if(!empty($value->description)){{$value->description}} @endif</p>
                                            </div>
                                            <div class="col-md-4">
                                                <strong><i class="fa fa-window-minimize margin-r-5"></i>Min
                                                    value:</strong>
                                                <p>@if(!empty($value->min)){{$value->min.' $'}} @endif</p>
                                            </div>
                                            <div class="col-md-4">
                                                <strong><i class="fa fa-window-maximize margin-r-5"></i>Max
                                                    value:</strong>
                                                <p>@if(!empty($value->max)){{$value->max.' $'}} @endif</p>
                                            </div>
                                            <div class="col-md-4">
                                                <strong><i class="fa fa-weight margin-r-5"></i>Weight value:</strong>
                                                <p>@if(!empty($value->weight)){{$value->weight}} @endif</p>
                                            </div>
                                        @endforeach

                                    </div>
                                    <hr>
                                </div>
                            @endif
                            @if(!empty($bc->all()))
                            <div class="box-body col-md-12">
                                <div class="row">
                                        <div class="col-md-12 border-bottom mb-3">
                                            <strong><i class="fa fa-heading margin-r-5"></i>Competition Budget
                                                Categories:</strong>
                                        </div>
                                        @foreach($bc as $key=>$value)
                                            {{--                                           @php dd($value);@endphp--}}

                                            <div class="col-md-3">
                                                <strong><i class="fa fa-heading margin-r-5"></i>Name:</strong>
                                                <p>@if(!empty($value->name)){{$value->name}} @endif</p>
                                            </div>
                                            <div class="col-md-3">
                                                <strong><i class="fa fa-window-minimize margin-r-5"></i>Min
                                                    value:</strong>
                                                <p>@if(!empty($value->min)){{$value->min.' $'}}
                                                    @endif</p>
                                            </div>
                                            <div class="col-md-3">
                                                <strong><i class="fa fa-window-maximize margin-r-5"></i>Max
                                                    value:</strong>
                                                <p>@if(!empty($value->max)){{$value->max.' $'}} @endif</p>
                                            </div>
                                            <div class="col-md-3">
                                                <strong><i class="fa fa-weight margin-r-5"></i>Weight value:</strong>
                                                <p>@if(!empty($value->weight)){{$value->weight}} @endif</p>
                                            </div>
                                        @endforeach

                                </div>
                                <hr>
                            </div>
                            @endif
                            @if(!empty($rr->all()))
                            <div class="box-body col-md-12">
                                <div class="row">

                                        <div class="col-md-12 border-bottom mb-3">
                                            <strong><i class="fa fa-heading margin-r-5"></i>Competition Ranking
                                                Roles:</strong>

                                        </div>
                                        @foreach($rr as $key=>$value)
                                            {{--                                                                                       @php dd($value);@endphp--}}

                                            <div class="col-md-4">
                                                <strong><i class="fa fa-heading margin-r-5"></i>Sql:</strong>
                                                <p>@if(!empty($value->sql)){{$value->sql}} @endif</p>
                                            </div>
                                            <div class="col-md-4">
                                                <strong><i class="fa fa-money margin-r-5"></i>
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

