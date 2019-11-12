@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card card_body">
                    <div class="card-header">Edit a Meeting</div>

                    <div class="card-body">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br />
                        @elseif (\Session::has('wrong'))
                            <div class="alert alert-success">
                                <p>{{ \Session::get('wrong') }}</p>
                            </div><br/>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                        @endif
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
