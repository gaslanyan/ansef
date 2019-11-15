@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Show Publications -  {{$person_id[0]['first_name']." ".$person_id[0]['last_name']}}
                        <a href="{{ action('Applicant\InfoController@index') }}" class="display float-lg-right btn-box-tool"> Back</a>
                    </div>
                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                            @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                                </div><br/>
                            @elseif (\Session::has('wrong'))
                                <div class="alert alert-success">
                                    <p>{{ \Session::get('wrong') }}</p>
                                </div><br/>
                            @endif
                        @endif
                        @if(!empty($publications))
                            <form method="post" action="{{ action('Base\PublicationsController@update', $id) }}"
                                  class="row">
                                @csrf
                                @foreach($publications as $i =>$publication)
                                    <div class="form-group col-lg-6">
                                        <input name="_method" type="hidden" value="PATCH">
                                        <label for="title">Publication Title:</label>
                                        <input type="text" class="form-control" name="title[]" id="title"
                                               value="{{$publication['title']}}">

                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="journal">Publication Journal:</label>
                                        <input type="text" class="form-control" name="journal[]"
                                               value="{{$publication['journal']}}"
                                               id="year">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="year">Publication Year:</label>
                                        <input type="text" class="form-control" name="year[]"
                                               value="{{$publication['year']}}"
                                               id="year">
                                    </div>

                                    <div class="form-group col-lg-2">
                                        <label for="pub_edit_ansefsupported_<?= $i;?>" class="label">Ansef Supported
                                            <input type="checkbox" class="form-control form-check-inline"
                                                   name="ansef_edit[{{$i}}]"
                                                   id="pub_edit_ansefsupported_<?= $i;?>" <?= ($publication['ansef_supported'] == 1) ? 'checked' : '';?> >
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="form-group col-lg-2">
                                        <label for="pub_edit_domestic<?= $i;?>" class="label">Domestic
                                            <input type="checkbox" class="form-control form-check-inline"
                                                   name="domestic[{{$i}}]"
                                                   id="pub_edit_domestic<?= $i;?>"
                                                   <?= ($publication['domestic'] == 1) ? 'checked' : '';?>>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Remove Publication
                                            <a href="{{action('Base\PublicationsController@destroy', $publication['id'])}}"
                                               class="btn-link col-lg-2">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </label>
                                        <input type="hidden" class="form-check-inline" name="publication_hidden_id[]"
                                               value="{{$publication['id']}}"
                                               id="title">
                                    </div>
                                    <br>
                                    <br>
                                @endforeach
                                <div class="form-group col-lg-12">
                                    <button type="submit" class="btn btn-primary">Edit Publication</button>
                                </div>
                            </form>
                            <hr>
                        @endif
                        <form method="post" action="{{ action('Base\PublicationsController@store') }}" class="row">
                            @csrf
                            <div class="form-group col-lg-6">
                                <label for="title">Publication Title:</label>
                                <input type="text" class="form-control" name="title" id="title">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="journal">Publication Journal:</label>
                                <input type="text" class="form-control" name="journal" id="journal">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="year">Publication Year:</label>
                                <input type="text" class="form-control" name="year" id="year">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="ansef_add" class="label">Ansef Supported
                                    <input type="checkbox" class="form-control form-check-inline" value=1
                                           name="ansef_add" id="ansef_add">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="domestic_add" class="label">Domestic
                                    <input type="checkbox" class="form-control form-check-inline" name="domestic_add"
                                           id="domestic_add" value=1>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <input type="hidden" class="form-check-inline" name="publication_add_hidden_id"
                                   value="{{$id}}"
                                   id="title">
                            <br/>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Add Publication</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
