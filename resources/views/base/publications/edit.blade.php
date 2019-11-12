@extends('layouts.master')

@section('content')
    <div class="container">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
            </div><br/>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                <div class="card">
                    <div class="card-header">Edit a Publication</div>

                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                        @endif
                        <form method="post" action="{{ action('Base\PublicationsController@update', $id) }}">
                            @csrf
                            <div class="form-group">
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="title">Publication Title:</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{$publication->title}}">

                            </div>
                            <div class="form-group">
                                <label for="journal">Publication Journal:</label>
                                <input type="text" class="form-control" name="journal" value="{{$publication->journal}}"
                                       id="year">
                            </div>
                            <div class="form-group">
                                <label for="year">Publication Year:</label>
                                <input type="text" class="form-control" name="year" value="{{$publication->year}}"
                                       id="year">
                            </div>
                            <div class="form-group">
                                <label for="title">Ansef Supported </label>
                                 <?php if($publication->ansef_supported == 1): ?>
                                <input type="checkbox" class="form-check-inline" name="ansef" value="{{$publication->ansef_supported}}"
                                       id="title" checked>
                                <?php else:?>
                                <input type="checkbox" class="form-check-inline" name="ansef" value="{{$publication->ansef_supported}}"
                                       id="title">
                                <?php endif;?>
                            </div>
                            <div class="form-group">
                                <label for="title">Domestic </label>
                                <?php if($publication->domestic == 1): ?>
                                <input type="checkbox" class="form-check-inline" name="domestic" value="{{$publication->domestic}}"
                                       id="title" checked>
                                <?php else:?>
                                <input type="checkbox" class="form-check-inline" name="domestic" value="{{$publication->domestic}}"
                                       id="title">
                                <?php endif;?>



                            </div>
                            <button type="submit" class="btn btn-primary">Edit Publication</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
