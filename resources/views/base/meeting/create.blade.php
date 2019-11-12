@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Show Meetings - {{$person[0]['first_name']." ".$person[0]['last_name']}}
                        <a href="{{ action('Applicant\InfoController@index') }}"
                           class="display float-lg-right btn-box-tool"> Back</a></div>
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
                        @if(!empty($meetings))
                            <form method="post" action="{{ action('Base\MeetingController@update', $id) }}">
                                @csrf
                                @foreach($meetings as  $i =>$meeting)
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <input name="_method" type="hidden" value="PATCH">
                                            <label for="description">Meeting Description:</label>
                                            <input type="text" class="form-control" name="meeting_description[]"
                                                   id="description" value="{{$meeting['description']}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-7">
                                            <label for="year">Meeting Year:</label>
                                            <input type="text" class="form-control" name="meeting_year[]"
                                                   value="{{$meeting['year']}}"
                                                   id="year">
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="meeting_edit_ansefsupported_<?= $i;?>" class="label">Ansef
                                                Supported
                                                <input type="checkbox" class=""
                                                       name="ansef_edit[{{$i}}]"
                                                       id="meeting_edit_ansefsupported_<?= $i;?>" <?= ($meeting['ansef_supported'] == 1) ? 'checked' : '';?> >
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>

                                        <div class="form-group col-lg-1">
                                            <label for="meeting_edit_domestic<?= $i;?>" class="label">Domestic
                                                <input type="checkbox" class=""
                                                       name="domestic[{{$i}}]"
                                                       id="meeting_edit_domestic<?= $i;?>"
                                                <?= ($meeting['domestic'] == 1) ? 'checked' : '';?>>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>

                                        <div class="form-group col-lg-2">
                                            <label>Remove Meetings
                                                <a href="{{action('Base\MeetingController@destroy', $meeting['id'])}}"
                                                   class="btn-link col-lg-2">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </label>
                                            <input type="hidden" class="form-check-inline" name="meeting_hidden_id[]"
                                                   value="{{$meeting['id']}}"
                                                   id="title">
                                        </div>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary">Edit Meeting</button>
                            </form>
                        @endif


                        <form method="post" action="{{ action('Base\MeetingController@store') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="description">Meeting Description:</label>
                                    <input type="text" class="form-control" name="description" id="description">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-7">
                                    <label for="year">Meeting Year:</label>
                                    <input type="text" class="form-control" name="year" id="year">
                                </div>
                                <div class="form-group col-lg-2">
                                    <label for="ansef_add" class="label">Ansef Supported
                                        <input type="checkbox" class="" value=1 name="ansef_add" id="ansef_add">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group col-lg-1">
                                    <label for="domestic_add" class="label">Domestic
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
                </div>
            </div>
        </div>
    </div>
@endsection
