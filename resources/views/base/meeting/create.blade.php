@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Update Meetings for {{$person[0]['first_name']." ".$person[0]['last_name']}}
                        <a href="{{ action('Applicant\AccountController@index') }}"
                           class="display float-lg-right btn-box-tool">Go Back</a></div>
                    <div class="card-body card_body" style="overflow:auto;">
                    <div class="card-body card_body" style="overflow:auto;">
                        <p><b>Add New Meeting</b></p>

                        <form method="post" action="{{ action('Applicant\MeetingController@store') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="description">Meeting Description and location:</label>
                                <input type="text" class="form-control" name="description" id="description" value="{{old('description')}}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-4">
                                    <label for="year">Year:</label>
                                <input type="text" class="form-control" name="year" id="year" value="{{old('year')}}">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="ansef_add" class="label">Supported by ANSEF?<br/>
                                        <input type="checkbox" class="" value=1 name="ansef_add" id="ansef_add">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="domestic_add" class="label">Meeting held in Armenia?<br/>
                                        <input type="checkbox" class="" name="domestic_add" id="domestic_add" value=1>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <input type="hidden" class="form-check-inline" name="meeting_add_hidden_id" value="{{$id}}"
                                   id="title">
                            <button type="submit" class="btn btn-primary">Add Meeting</button>
                        </form>
                    </div>
                        @include('partials.status_bar')
  <hr>

                        @if(!empty($meetings))
                            <form method="post" action="{{ action('Applicant\MeetingController@update', $id) }}">
                                @csrf
                                <p class="col-12"><b>Meeting list:</b></p>
                                @foreach($meetings as  $i =>$meeting)
                                    <div class="row">
                                        <div class="form-group col-lg-10">
                                            <input name="_method" type="hidden" value="PATCH">
                                            <label for="description">Meeting Description and location:</label>
                                            <input type="text" class="form-control" name="meeting_description[]"
                                                   id="description" value="{{$meeting['description']}}">
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>
                                                <a href="{{action('Applicant\MeetingController@destroy', $meeting['id'])}}"
                                                   class="btn-link col-lg-2">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </label>
                                            <input type="hidden" class="form-check-inline" name="meeting_hidden_id[]"
                                                   value="{{$meeting['id']}}"
                                                   id="title">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label for="year">Year:</label>
                                            <input type="text" class="form-control" name="meeting_year[]"
                                                   value="{{$meeting['year']}}"
                                                   id="year">
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="meeting_edit_ansefsupported_<?= $i;?>" class="label">Ansef
                                                Supported
                                                <input type="checkbox" class=""
                                                       name="ansef_edit[{{$i}}]"
                                                       id="meeting_edit_ansefsupported_<?= $i;?>" <?= ($meeting['ansef_supported'] == 1) ? 'checked' : '';?> >
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <label for="meeting_edit_domestic<?= $i;?>" class="label">Domestic
                                                <input type="checkbox" class=""
                                                       name="domestic[{{$i}}]"
                                                       id="meeting_edit_domestic<?= $i;?>"
                                                <?= ($meeting['domestic'] == 1) ? 'checked' : '';?>>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>

                                    </div><br/>
                                @endforeach
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        @endif
                        </div>
                                      </div>
            </div>
        </div>
    </div>
@endsection
