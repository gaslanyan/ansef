@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                <div class="card">
                    <div class="card-header">Create Institution
                        <a href="{{ action('Admin\InstitutionController@index') }}"
                           class="display float-lg-right btn-box-tool"> Back</a>
                        <br>
                        <i class="fa fa-info text-blue all"> * {{Lang::get('messages.required_all')}}</i>
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
                            @endif
                        @endif
                        <form method="post" action="{{ action('Admin\InstitutionController@update', $id) }}"
                              class="row">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="form-group col-lg-12">
                                <label for="name">Institution Name *:</label>
                                <input type="text" class="form-control" name="name" id="name"
                                       value="@if(!empty($institution['content'])) {{$institution['content']}}@endif">
                            </div>

                            <div class="col-lg-12 ">
                                <label>Addresses:</label>

                                <div class="row addresses">
                                    <div class="form-group col-lg-6">
                                        <label for="addr">Country *:</label>
                                        <select id="addr" class="addr form-control" name="countries[0]">
                                            <option value="0">Select country</option>
                                            @if(!empty($countries))
                                                @foreach($countries as $val=>$item)
                                                    @if($address['cc_fips'] == $val)
                                                        <option class="text-capitalize" value="{{$val}}"
                                                                selected>{{$item}}</option>
                                                    @else
                                                        <option class="text-capitalize"
                                                                value="{{$val}}">{{$item}}</option>
                                                    @endif
                                                @endforeach

                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="provence">Provence *:</label>
                                        <input type="text" class="form-control" name="provence[0]" id="provence"
                                               value="@if(!empty($institution->address->province)) {{$institution->address->province}} @endif">
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label for="city">City *:</label>

                                        <?php $selected = "";
                                        $selected_id = 0;?>
                                        <datalist id="city" name="city[0]">
                                            <option data-value="0" value="Select City"></option>
                                            @if(!empty($cities))
                                                @foreach($cities as $val => $item)

                                                    <option data-value="{{$city->id}}"
                                                            value="{{$item['name']}}">
                                                    </option>
                                                    <?php
                                                    $selected = $item['name'];
                                                    $selected_id = $city->id;
                                                    ?>
                                                @endforeach
                                            @endif
                                        </datalist>
                                        <input list="city" name="city[]" class="form-control"
                                               value="{{$selected}}" id="_city">
                                        <input type="hidden" name="city_id[]" value="{{$selected_id}}"
                                               id="city_id">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="street">Street *:</label>
                                        <input type="text" class="form-control" name="street[0]" id="street"
                                               value="@if(!empty($institution->address->street)) {{$institution->address->street}} @endif">
                                    </div>

                                </div>


                                {{--                                    <div class="row addresses">--}}
                                {{--                                        <div class="form-group col-lg-6">--}}
                                {{--                                            <label for="addr">Country:</label>--}}
                                {{--                                            <select id="addr" class="addr form-control" name="countries[0]">--}}
                                {{--                                                <option value="0">Select country</option>--}}
                                {{--                                                @if(!empty($countries))--}}
                                {{--                                                    @foreach($countries as $val=>$item)--}}
                                {{--                                                        <option class="text-capitalize"--}}
                                {{--                                                                value="{{$val}}">{{$item}}</option>--}}
                                {{--                                                    @endforeach--}}
                                {{--                                                @endif--}}
                                {{--                                            </select>--}}
                                {{--                                        </div>--}}
                                {{--                                        <div class="form-group col-lg-6">--}}
                                {{--                                            <label for="provence">Provence *:</label>--}}
                                {{--                                            <input type="text" class="form-control" name="provence[0]" id="provence">--}}
                                {{--                                        </div>--}}
                                {{--                                        <div class="form-group col-lg-6">--}}
                                {{--                                            <label for="city">City *:</label>--}}
                                {{--                                            <select id="city" class="city form-control" name="city[0]">--}}
                                {{--                                                <option value="0">Select city</option>--}}

                                {{--                                            </select>--}}
                                {{--                                        </div>--}}
                                {{--                                        <div class="form-group col-lg-6">--}}
                                {{--                                            <label for="street">Street *:</label>--}}
                                {{--                                            <input type="text" class="form-control" name="street[0]" id="street">--}}
                                {{--                                        </div>--}}

                                {{--                                    </div>--}}
                                {{--                                @endif--}}
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

