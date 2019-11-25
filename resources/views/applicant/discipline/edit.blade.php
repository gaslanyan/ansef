@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                    <div class="card-header">Edit a discipline</div>

                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Base\DisciplineController@update', $id) }}">
                            @csrf
                            <div class="form-group">
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="title">Discipline Title:</label>
                                <input type="text" class="form-control" name="title" value="{{$discipline->title}}"
                                       id="title">

                            </div>
                            <div class="form-group">
                                <label for="name">Person name:</label>
                                @if(!empty($persons))

                                    @foreach($persons as $person)
                                        <input class="form-control" type="text" value="{{$person['first_name']. " " .$person['last_name']}}" disabled id="name">
                                    @endforeach
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Edit Discipline</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
