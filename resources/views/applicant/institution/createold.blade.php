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
                 <div class="card" >
                    <div class="card-header">Institution
                        <a href="{{ action('Applicant\InfoController@index') }}"
                           class="display float-lg-right btn-box-tool"> Back</a>
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        @if(!empty($ins_array))
                            <form method="post" action="{{ action('Base\InstitutionController@update', 53) }}"
                                  class="row">
                                @csrf
                                {{ method_field('PUT') }}
                                @foreach($ins_array as $institution)
                                    <div class="form-group col-lg-12">
                                        <label for="name">Institution Name:</label>
                                        <input type="text" class="form-control" name="content" id="name"
                                               value="@if(!empty($institution['content'])) {{$institution['content']}}@endif">
                                    </div>
                                    <div class="col-lg-12 ">
                                        <label>Addresses:</label>
                                        <div class="row addresses">
                                            <div class="form-group col-lg-6">
                                                <label for="addr">Country:</label>
                                                <select id="addr" class="addr form-control" name="countries[0]">
                                                    <option>Select country</option>
                                                    @if(!empty($countries))
                                                        @foreach($countries as $val=>$item)
                                                            <option class="text-capitalize" value="{{$val}}"
                                                                    @if($address->cc_fips === $val ) selected @endif>{{$item}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="provence">Provence:</label>
                                                <input type="text" class="form-control" name="provence[0]" id="provence"
                                                       value="@if(!empty($institution->address->province)) {{$institution->address->province}} @endif">
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <label for="city">City:</label>
                                                <select id="city" class="city form-control" name="city[0]">
                                                    <option>Select city</option>

                                                    @if(!empty($cities))
                                                        @foreach($cities as $val => $item)

                                                            <option class="text-capitalize" value="{{$item['id']}}"
                                                                    @if($city->id === $item['id'] ) selected @endif>{{$item['name']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="street">Street:</label>
                                                <input type="text" class="form-control" name="street[0]" id="street"
                                                       value="@if(!empty($institution->address->street)) {{$institution->address->street}} @endif">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-group col-lg-12">
                                    <button type="submit" class="btn btn-primary">Edit Institution</button>
                                </div>
                            </form>
                        @endif


                        <form method="post" action="{{ action('Base\InstitutionController@store') }}" class="row">
                            @csrf
                            <div class="form-group col-lg-12">
                                <label for="name">Institution Name:</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>

                            <div class="col-lg-12 ">
                                <label>Addresses:</label>
                                <div class="row addresses">
                                    <div class="form-group col-lg-6">
                                        <label for="addr">Country:</label>
                                        <select id="addr" class="addr form-control" name="countries[0]">
                                            <option>Select country</option>
                                            @if(!empty($countries))
                                                @foreach($countries as $val=>$item)
                                                    <option class="text-capitalize" value="{{$val}}">{{$item}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="provence">Provence:</label>
                                        <input type="text" class="form-control" name="provence[0]" id="provence">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="city">City:</label>
                                        <select id="city" class="city form-control" name="city[0]">
                                            <option>Select city</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="street">Street:</label>
                                        <input type="text" class="form-control" name="street[0]" id="street">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" class="form-control" name="institution_creare_hidden"
                                   value="{{$id}}"
                                   id="email">
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Add Institution</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

