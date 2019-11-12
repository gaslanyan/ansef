@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                <div class="card">
                    <div class="card-header">Edit Report
                        <input type="button" class="display float-lg-right btn btn-primary px-2" onclick="open_container()"
                               value="View refereeing guidelines">
                        <a href="{{action('Referee\ReportController@generatePDF', $report->id)}}"
                           class="download float-lg-right px-2 btn" title="Download Report"><i class="fa fa-download"></i>
                        </a>
                        <a href="{{action('Referee\SendEmailController@showEmail', $report->id)}}"
                           class="view float-lg-right px-2 btn" title="Send email" ><i class="fa fa-envelope-open"></i>
                        </a>
                        <a href="{{action('Referee\ReportController@show', $report->id)}}"
                           class="view float-lg-right px-2 btn" title="View"><i class="fa fa-eye"></i>
                        </a>

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
                        @if(!empty($report))
                            <form method="post" action="{{ action('Referee\ReportController@update', $report->id) }}"
                                  class="row">
                                @csrf
                                <div class="form-group">
                                    <input name="_method" type="hidden" value="PATCH">
                                </div>
                                <div class="form-group col-lg-12">
                                    <h2 for="title">{{$report->proposal->title}}</h2>
                                    {{--<input type="text" class="form-control" name="title" id="title"--}}
                                    {{--value="{{$report->proposal->title}}">--}}
                                </div>
                                <div class="form-group col-lg-6">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label for="public">Public Comment:</label>
                                            <textarea rows="4" class="form-control" name="public_comment"
                                                      id="public">{{$report->public_comment}}</textarea>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="private">Private Comment:</label>
                                            <textarea rows="4" class="form-control" name="private_comment"
                                                      id="private">{{$report->private_comment}}</textarea>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="dur_date">Duration Date:</label>
                                            <input type="text" class="form-control date datepicker"
                                                   value="{{$report->dur_date}}"
                                                   name="dur_date" id="dur_date">
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="overall_scope">Overall Scope</label>
                                            <span type="number" class="form-control"
                                                  id="overall_scope">{{$report->overall_scope}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Score Type Name:</label>
                                    <div class="col-lg-12">
                                        @php
                                            $gstns = $scoreTypes;
                                            $gstvs = getScoreTypeValues();
                                        @endphp
                                        @foreach($gstns as $gstn)
                                            <div class="row">
                                                <label for="{{$gstn->name}}" class="form-control col-lg-6">{{$gstn->name}}</label>

                                                <select class="form-control col-lg-6"
                                                        name="name[{{strtolower($gstn->id)}}]" id="{{$gstn->id}}">
{{--                                                    <option value="0">Select a Score</option>--}}
                                                    @foreach($gstvs as $key=> $gstv)
                                                        @php
                                                            $gstn_s = strtolower($gstn->id);
                                                        $stv = json_decode($gstn->score, true);

                                                        @endphp
                                                        <option value="{{$key}}"
                                                        @if($stv['value'] == $key) {{'selected'}} @endif
                                                        >{{$gstv}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <button type="submit" name="state_r" value="rejected" class="btn btn-primary">
                                        Reject to review
                                    </button>
                                    <button type="submit" name="state_p" value="in-progress" class="btn btn-primary">
                                        Save but do not submit
                                    </button>
                                    <button type="submit" name="state_c" value="complete" class="btn btn-primary">Save
                                        and submit review
                                    </button>

                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h2></h2>
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body" id="modal-bodyku">
                    @include('partials.help')
                </div>
                <div class="modal-footer" id="modal-footerq"></div>
            </div>
        </div>
    </div>

    <script>
        function open_container() {
            var size = 'small',
                content = '',
                title = 'Referee guidelines',
                footer = '';
            jQuery.noConflict();
            setModalBox(title, size);
            jQuery('#myModal').modal('show');
        }

        function setModalBox(title, content, footer, $size) {
            jQuery('#myModal').find('.modal-header h2').text(title);

            if ($size === 'small') {
                jQuery('#myModal').attr('class', 'modal fade bs-example-modal-sm')
                    .attr('aria-labelledby', 'mySmallModalLabel');
                jQuery('.modal-dialog').attr('class', 'modal-dialog modal-sm');
            }
        }


    </script>
@endsection

