@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                    <div class="card-header">Show Disciplines - {{$person[0]['first_name']." ".$person[0]['last_name']}}
                        <a href="{{ action('Applicant\InfoController@index') }}"
                           class="display float-lg-right btn-box-tool"> Back</a>
                    </div>

                    <div class="card-body card_body">
                        @include('partials.status_bar')


                        @if(!empty($disciplines_person ))
                            <form method="post" action="{{ action('Applicant\DisciplineController@update', $id) }}"
                                  class="row">
                                @csrf
                                @foreach( $disciplines_person  as $dp)
                                    <div class="form-group col-lg-10">
                                        <input name="_method" type="hidden" value="PATCH">
                                        <label for="title">Discipline Title:</label>
                                        <input type="text" class="form-control" name="title[]"
                                               value="{{$dp->text}}"
                                               id="title">

                                    </div>
                                    {{--<div class="form-group col-lg-5" >--}}
                                    {{--<label for="name">Person name:</label>--}}
                                    {{--<input class="form-control" type="text" value="{{$discipline->first_name. " " .$discipline->last_name}}" disabled id="name">--}}

                                    {{--</div>--}}
                                    <div class="form-group col-lg-2">
                                        <label>Remove Discipline
                                            <a href="{{action('Applicant\DisciplineController@destroy', $dp->id)}}"
                                               class="btn-link col-lg-2">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </label>
                                        <input type="hidden" class="form-check-inline"
                                               name="discipline_edit_hidden_id[]"
                                               value="{{$dp->id}}"
                                               id="title">
                                    </div>
                                @endforeach
                                <div class="form-group col-lg-12">
                                    <button type="submit" class="btn btn-primary">Edit Discipline</button>
                                </div>
                            </form>
                        @endif


                        <form method="post" action="{{ action('Applicant\DisciplineController@store') }}">
                            <div class="form-group">
                                @csrf
                                <label for="title">Discipline Title:</label>
                                @if(!empty($disciplines))
                                    <select class="form-control" name="discipline" id="title">
                                        <option value="0">Select Discipline</option>
                                        @foreach($disciplines as $d)
                                            <option <?php if(old('discipline') == $d->text) echo 'selected'; ?> value="{{$d->id}}">{{$d->text}}</option>

                                        @endforeach
                                    </select>
                            </div>
                            <input type="hidden" class="form-check-inline" name="discipline_add_hidden_id"
                                   value="{{$id}}"
                                   id="title">
                            @endif
                            <br/>
                            <button type="submit" class="btn btn-primary">Add Discipline</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
