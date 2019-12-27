@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Send email to Research Board
                    </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                       <form method="post" action="{{action('Applicant\ResearchBoardController@send') }}">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <p style="color:#999;margin-left:15px;margin-right:15px;">
                                    Communicate with the Research Board with questions you might have about
                                    a competition, process, and requirements. You will receive a response usually within 2 days.
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-10 emails">
                                        <input type="hidden" name="target" value="board">
                                        <label for="board">Message:</label>
                                        <textarea class="form-control" name="message" id="message"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Send message</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
