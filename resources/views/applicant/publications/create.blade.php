@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Show Publications for  {{$person_id[0]['first_name']." ".$person_id[0]['last_name']}}
                        <a href="{{action('Applicant\AccountController@index')}}"
                        class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>
                    <div class="card-body" style="overflow:auto;">
                        <p>List only recent publications, within the last 10 years, and as relevant to your proposal.</p>
                        <div class="" style="overflow:auto;">
                        <p><b>Add New Publication</b></p>

                        <form method="post" action="{{ action('Applicant\PublicationsController@store') }}" class="row">
                            @csrf
                            <div class="form-group col-lg-6">
                                <label for="title">Title:</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{old('title')}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="journal">Journal (if any):</label>
                                <input type="text" class="form-control" name="journal" id="journal" value="{{old('journal')}}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="year">Year:</label>
                                <input type="text" class="form-control" name="year" id="year" value="{{old('year')}}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="ansef_add" class="label">Supported by ANSEF?<br/>
                                    <input type="checkbox" class="form-control form-check-inline" value=1
                                           name="ansef_add" id="ansef_add">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="domestic_add" class="label">Is journal based in Armenia?<br/>
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
                        @include('partials.status_bar')
 <hr>

                        @if(!empty($publications))
                            <form method="post" action="{{ action('Applicant\PublicationsController@update', $id) }}">
                                @csrf
                                <label><b>Recent publications:</b></label><br/><br/>
                                @foreach($publications as $i =>$publication)
                                <div class="row">
                                    <div class="row col-12" style="margin-bottom: 5px;">
                                        <div class="col-lg-1">
                                            <input name="_method" type="hidden" value="PATCH">
                                            <span>Title:</span>
                                        </div>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control" name="title[]" id="title"
                                                value="{{$publication['title']}}">

                                        </div>
                                        <div class="col-lg-2">
                                            <span>Journal (if any):</span>
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" name="journal[]"
                                                value="{{$publication['journal']}}"
                                                id="year">
                                        </div>
                                    </div>

                                    <div class="row col-12">
                                    <div class="col-lg-2">
                                        <span>Year:</span>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control" name="year[]"
                                               value="{{$publication['year']}}"
                                               id="year">
                                    </div>

                                    <div class="form-group col-lg-3">
                                        <label for="pub_edit_ansefsupported_<?= $i;?>" class="label">Supported by ANSEF?<br/>
                                            <input type="checkbox" class="form-control form-check-inline"
                                                   name="ansef_edit[{{$i}}]"
                                                   id="pub_edit_ansefsupported_<?= $i;?>" <?= ($publication['ansef_supported'] == 1) ? 'checked' : '';?> >
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label for="pub_edit_domestic<?= $i;?>" class="label">Is journal based in Armenia?<br/>
                                            <input type="checkbox" class="form-control form-check-inline"
                                                   name="domestic[{{$i}}]"
                                                   id="pub_edit_domestic<?= $i;?>"
                                                   <?= ($publication['domestic'] == 1) ? 'checked' : '';?>>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group col-lg-1">
                                        <label>
                                            <a href="{{action('Applicant\PublicationsController@destroy', $publication['id'])}}"
                                               class="btn-link col-lg-2">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </label>
                                        <input type="hidden" class="form-check-inline" name="publication_hidden_id[]"
                                               value="{{$publication['id']}}"
                                               id="title">
                                    </div>
                                    </div>
                                </div><br/>
                                @endforeach
                                <div class="form-group col-lg-12">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        @endif
                    </div>
                                   </div>
            </div>
        </div>
    </div>
@endsection
