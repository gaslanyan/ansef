@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                        </div><br />
                    @elseif (\Session::has('wrong'))
                        <div class="alert alert-success">
                            <p>{{ \Session::get('wrong') }}</p>
                        </div><br/>
                    @endif
                    <div class="card-header">Edit a Honors&Grants</div>

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
                        <form method="post" action="{{ action('Base\HonorsController@update', $id) }}">
                            @csrf
                            <div class="form-group">
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="description">Honors&Grants Description:</label>
                                <input type="text" class="form-control" name="description" id="description" value="{{$honor->description}}">

                            </div>
                            <div class="form-group">
                                <label for="year">Honors&Grants Year:</label>
                                <input type="text" class="form-control" name="year" value="{{$honor->year}}"
                                       id="year">
                            </div>

                            <button type="submit" class="btn btn-primary">Edit Meeting</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
