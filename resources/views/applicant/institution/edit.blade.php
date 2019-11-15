@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Create Institution</div>

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
                                </div><br />
                            @endif
                        @endif
                        <form method="post" action="{{ action('Admin\InstitutionController@update', $id) }}" class="row">
                            @csrf
                            {{ method_field('PUT') }}
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


                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Edit Institution</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

