@extends('layouts.master')

@section('content')
    <div class="container">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
            </div><br/>
        @endif
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Edit a Degree</div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')
                        <form method="post" action="{{ action('Base\DegreeController@update', $id) }}">
                            @csrf
                            <div class="form-group">
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="description">Degree Description:</label>
                                <input type="text" class="form-control" name="description" id="description" value="{{$degree->description}}">

                            </div>
                            <div class="form-group">
                                <label for="year">Degree Year:</label>
                                <input type="text" class="form-control" name="year" value="{{$degree->year}}"
                                       id="year">
                            </div>

                            <button type="submit" class="btn btn-primary">Edit Degree</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
