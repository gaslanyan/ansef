@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Send Email into Research Board
                        <a href="{{ action('Applicant\InfoController@index') }}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>
                    <div class="card-body card_body">
                        @include('partials.status_bar')

                       <form method="post" action="{{action('Applicant\ResearchBoardController@send') }}">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group col-lg-10 emails">
                                        <label for="board">Message:</label>
                                        <textarea class="form-control" name="board"
                                               id="board">
                                        </textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Email</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
