@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Review and submit scores
                            <input style="margin-left:40px;" type="button" class="display btn btn-primary float-right" onclick="open_container()" value="View refereeing guidelines">

                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        <div class="row">
                            <a href="{{action('Referee\ReportController@show', $report->id)}}"
                            class="view float-left myButton" title="View"><i class="fa fa-eye" style="margin-right:5px;"></i>View proposal
                            </a>
                            <a href="{{action('Referee\ReportController@generatePDF', $report->id)}}"
                            class="download float-left myButton" title="Download Report"><i class="fa fa-download" style="margin-right:5px;"></i>Download proposal
                            </a>
                            <a style="margin-left:20px;" href="{{action('Referee\SendEmailController@showEmail', $report->id)}}"
                            class="email float-left myButton" title="Send email" ><i class="fa fa-envelope-open" style="margin-right:5px;"></i>Contact program officer
                            </a>
                        </div><br/><br/>

                        @if(!empty($report))
                            <form method="post" action="{{ action('Referee\ReportController@update', $report->id) }}"
                                  class="row">
                                @csrf
                                <div class="form-group">
                                    <input name="_method" type="hidden" value="PATCH">
                                </div>
                                <div class="form-group col-lg-12">
                                    <p for="title"><h5><b>{{getProposalTag($report->proposal->id)}}</b></h5>{{$report->proposal->title}}</p>
                                </div>
                                <div class="form-group col-lg-6">
                                    <h4>Comments</h4>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label for="public">Public Comments:</label>
                                            <p style="color:#999;">Please provide a few words summarizing your assessment.
                                                The comments will be shared with the Principal Investigator of the proposal in an effort to help him or her
                                                improve their project.
                                            </p>
                                            <textarea rows="4" class="form-control" name="public_comment"
                                                      id="public">{{$report->public_comment}}</textarea>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="private">Private Comment:</label>
                                            <p style="color:#999;">You can provide the ANSEF Research Board with private comments about the proposal.
                                                These comments will NOT be shared with any members of the proposal and will be treated
                                                confidentially.
                                            </p>
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
                                    <h4>Scores</h4>
                                    <div>
                                        @foreach($scoreTypes as $scoreType)
                                            @php
                                                $vals= range($scoreType->min, $scoreType->max)
                                            @endphp
                                            <div class="row col-12" style="color:#999;">
                                            {{$scoreType->description}}
                                            </div>
                                            <div class="row col-12">
                                                <div class="col-6"><b>{{$scoreType->name}}: </b></div>
                                                    <select max="{{$scoreType->max}}" weight="{{$scoreType->weight}}" class="form-control col-6 scoremenus" name="name[{{$scoreType->id}}]" id="{{$scoreType->id}}">
                                                    @foreach($vals as $val)
                                                    <option value="{{$val}}" {{$val == $scores[$scoreType->id]->value ? 'selected' : ''}}>{{$val}}{{$val == 0 ? ' (Poor)' : ''}}{{$val == $scoreType->max ? ' (Outstanding)' : ''}}</option>
                                                    @endforeach
                                                </select>
                                            </div><hr/>
                                        @endforeach
                                    </div>
                                    <div>
                                        <b>Overall score:</b> <span id="overallspan">{{$overall}}%</span>
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <button type="submit" name="submitaction" value="rejected" class="btn btn-secondary">
                                        Decline to review
                                    </button>
                                    <button style="margin-left:10px;" type="submit" name="submitaction" value="in-progress" class="btn btn-primary float-right">
                                        Save but do not submit
                                    </button>
                                    <button onclick="return confirm('Are you sure you want to submit your report? No further changes will be possible.');" style="margin-left:10px;" type="submit" name="submitaction" value="complete" class="btn btn-primary float-right">Save
                                        and submit review
                                    </button>
                                </div>
                            </form>
                        @include('partials.status_bar')
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
        $(document).ready(function(){
                $('select.scoremenus').on('change',function(){
                    var result = 0.0;
                    var weight = 0.0;
                    $("select.scoremenus").each(function( index ) {
                        var val = parseFloat($(this).children("option:selected").val());
                        var max = parseFloat($(this).attr('max'));
                        var w = parseFloat($(this).attr('weight'));
                        weight += w;
                        result += (w*100.0*val/max);
                    });
                    var score = Math.round(result/weight);
                    $("#overallspan").text(score + '%');
                });
        });



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

