@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Show Honors&Grants
                        - {{$person_id[0]['first_name']." ".$person_id[0]['last_name']}}
                        <a href="{{ action('Applicant\InfoController@index') }}"
                           class="display float-lg-right btn-box-tool"> Back</a>
                    </div>

                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        @if (\Session::has('delete'))
                            <div class="alert alert-info">
                                <p>@php echo html_entity_decode(\Session::get('delete'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        @if(!empty($honors))
                            <form method="post" action="{{ action('Base\HonorsController@update', $id) }}">
                                @csrf
                                {{--<input name="_method" type="hidden" value="PATCH">--}}
                                <div class="col-lg-12 ">
                                    <div class="row">
                                        @foreach($honors as $honor)
                                            <div class="form-group col-lg-5 ">
                                                <input name="_method" type="hidden" value="PATCH">
                                                <label for="description">Honors&Grants Description:</label>
                                                <input type="text" class="form-control" name="description[]"
                                                       id="description" value="{{$honor['description']}}">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="year">Honors&Grants Year:</label>
                                                <input type="text" class="form-control" name="year[]"
                                                       value="{{$honor['year']}}"
                                                       id="year">
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label for="year">Remove Honors&Grants <br/>
                                                    <a href="{{action('Base\HonorsController@destroy', $honor['id'])}}"
                                                       class="btn-link">
                                                        <i class="fa fa-trash"></i></a>
                                                </label>
                                            </div>
                                            <input type=hidden value="{{$honor['id']}}" name="honor_hidden_id[]">
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <button type="submit" class="btn btn-primary">Edit Meeting</button>
                                </div>
                            </form>
                        @endif
                        <form method="post" action="{{ action('Base\HonorsController@store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="description">Honors&Grants Description:</label>
                                <input type="text" class="form-control" name="description" id="description">
                            </div>
                            <div class="form-group">
                                <label for="year">Honors&Grants Year:</label>
                                <input type="text" class="form-control" name="year" id="year">
                            </div>
                            <input type=hidden value="{{$id}}" name="honor_hidden_id[]">
                            <button type="submit" class="btn btn-primary">Add Honors&Grants</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
