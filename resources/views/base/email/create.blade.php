@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                        @include('partials.status_bar')
                    <div class="card-header">Create Emails</div>

                    <div class="card-body card_body" >
                        <form method="post" action="{{ action('Base\EmailController@store') }}">
                            @csrf
                            <div class="form-group">

                                <div class="col-lg-12 ">
                                    <div class="row">
                                        <div class="form-group col-lg-10 emails">
                                            <label for="email">Emails:</label>
                                            <i class="fa fa-plus pull-right add  text-blue"
                                               style="cursor: pointer"></i>
                                            <input type="text" class="form-control email" name="email[]"
                                                   id="email">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Email</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
