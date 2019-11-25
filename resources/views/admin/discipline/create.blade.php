@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                    <div class="card-header">Create Discipline
                        <br>
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\DisciplineController@store') }}">
                            <div class="form-group">
                                @csrf
                                <label for="title">Discipline Title *:</label>
                                <input type="text" class="form-control" name="text" id="title">
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        <a href = "{{ action('Admin\DisciplineController@index') }}" class="btn btn-secondary"> Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
