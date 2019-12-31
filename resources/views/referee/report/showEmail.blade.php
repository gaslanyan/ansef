@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Communicate with Program Officer
                        @if(empty($rejected))
                        <a href="{{action('Referee\ReportController@state', 'in-progress')}}" class="display float-lg-right btn-box-tool">Go Back</a>
                        @endif
                    </div>

                    <div class="card-body" style="overflow:auto;">
                        @include('partials.status_bar')


                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Communicate with Program Officer about proposal: {{$tag}} </h3>
                            </div>
                            <div class="box-body col-md-12">
                                @if(!empty($rejected))
                                <p>Please describe in a few words why you do not want
                                    to review this proposal. If relevant to your reason,
                                    we would also appreciate if
                                    you can suggest an alternate referee for this proposal.
                                </p>
                                @endif
                                <form method="post" action="{{ action('Referee\SendEmailController@sendEmail', $pid) }}"
                                      class="row">
                                    @csrf
                                    <div class="form-group col-lg-12">
                                        <input type="hidden" name="rejected" value="{{$rejected}}">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label for="public">Message:</label>
                                                <textarea rows="4" class="form-control" name="comment"
                                                          id="public"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <button type="submit" class="btn btn-primary">
                                            Send Message
                                        </button>
                                        @if(!empty($rejected))
                                        <a href="/referee/report/in-progress" class="btn btn-secondary">
                                            Decline to send message
                                        </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
