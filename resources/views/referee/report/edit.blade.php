@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Review and submit scores

                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        <div class="row">
                            <a href="{{action('Referee\ReportController@generatePDF', $report->id)}}"
                            class="download float-left myButton" title="Download Report"><i class="fa fa-download" style="margin-right:5px;"></i>Download proposal
                            </a>
                            <a href="{{action('Referee\SendEmailController@showEmail', $report->id)}}"
                            class="email float-left myButton" title="Send email" ><i class="fa fa-envelope-open" style="margin-right:5px;"></i>Contact program officer
                            </a>
                            <a href="{{action('Referee\ReportController@show', $report->id)}}"
                            class="view float-left myButton" title="View"><i class="fa fa-eye" style="margin-right:5px;"></i>View proposal
                            </a>
                            <input style="margin-left:20px;" type="button" class="display btn btn-primary" onclick="open_container()" value="View refereeing guidelines">

                        </div><br/><br/>
                        @include('partials.status_bar')

                        @if(!empty($report))
                            <form method="post" action="{{ action('Referee\ReportController@update', $report->id) }}"
                                  class="row">
                                @csrf
                                <div class="form-group">
                                    <input name="_method" type="hidden" value="PATCH">
                                </div>
                                <div class="form-group col-lg-12">
                                    <p for="title"><b>{{getProposalTag($report->proposal->id)}}</b><br/> {{$report->proposal->title}}</p>
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
                                            <label for="due_date">Report due date:</label>
                                            <b>{{$report->due_date}}</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <div>
                                        @foreach($scoreTypes as $scoreType)
                                            @php
                                                $vals= range($scoreType->min, $scoreType->max)
                                            @endphp
                                            <div class="row col-12">
                                            {{$scoreType->description}}
                                            </div>
                                            <div class="row col-12">
                                                <div class="col-6"><b>{{$scoreType->name}}: </b></div>
                                                <select class="form-control col-6" name="name[{{mb_strtolower($scoreType->id)}}]" id="{{$scoreType->id}}">
                                                    @foreach($vals as $val)
                                                    <option value="{{$val}}">{{$val}}{{$val == 0 ? ' (Poor)' : ''}}{{$val == $scoreType->max ? ' (Outstanding)' : ''}}</option>
                                                    @endforeach
                                                </select>
                                            </div><br/>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <button type="submit" name="state_r" value="rejected" class="btn btn-secondary">
                                        Reject to review
                                    </button>
                                    <button style="margin-left:10px;" type="submit" name="state_p" value="in-progress" class="btn btn-primary float-right">
                                        Save but do not submit
                                    </button>
                                    <button style="margin-left:10px;" type="submit" name="state_c" value="complete" class="btn btn-primary float-right">Save
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

