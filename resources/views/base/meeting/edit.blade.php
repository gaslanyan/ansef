@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card card_body">
                    <div class="card-header">Edit a Meeting</div>

                    <div class="card-body">
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Base\MeetingController@update', $id) }}">
                            @csrf
                            <div class="form-group">
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="description">Meeting Description:</label>
                                <input type="text" class="form-control" name="description" id="description" value="{{$meeting->description}}">

                            </div>
                            <div class="form-group">
                                <label for="year">Meeting Year:</label>
                                <input type="text" class="form-control" name="year" value="{{$meeting->year}}"
                                       id="year">
                            </div>
                            <div class="form-group">
                                <label for="title">Ansef Supported </label>
                                 <?php if($meeting->ansef_supported == 1): ?>
                                <input type="checkbox" class="form-check-inline" name="ansef" value="{{$meeting->ansef_supported}}"
                                       id="title" checked>
                                <?php else:?>
                                <input type="checkbox" class="form-check-inline" name="ansef" value="{{$meeting->ansef_supported}}"
                                       id="title">
                                <?php endif;?>
                            </div>
                            <div class="form-group">
                                <label for="title">Domestic </label>
                                <?php if($meeting->domestic == 1): ?>
                                <input type="checkbox" class="form-check-inline" name="domestic" value="{{$meeting->domestic}}"
                                       id="title" checked>
                                <?php else:?>
                                <input type="checkbox" class="form-check-inline" name="domestic" value="{{$meeting->domestic}}"
                                       id="title">
                                <?php endif;?>



                            </div>
                            <button type="submit" class="btn btn-primary">Edit Meeting</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
