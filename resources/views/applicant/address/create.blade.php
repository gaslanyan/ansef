@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="offset-md-2 col-md-10">
            <div class="card" >
                <div class="card-header">Update addresses for {{$person['first_name']}} {{$person['last_name']}}
                        <a href="{{action('Applicant\AccountController@index')}}"
                           class="display float-lg-right btn-box-tool"> Go Back</a>
                </div>
                <div class="card-body card_body" style="overflow:auto;">
                    <div class="card-body card_body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                        <p><b>Add New Address</b></p>
                        <form method="post" action="{{action('Applicant\AddressController@store') }}">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="street">Street *:</label>
                                        <input type="text" class="form-control street" name="street" value="{{old('street')}}" id="street">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="province">State/Municipality *:</label>
                                        <input type="text" class="form-control province" name="province" value="{{old('province')}}" id="province">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="strecityet">City *:</label>
                                        <input type="text" class="form-control city" name="city" value="{{old('city')}}" id="city">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Country *:</label>
                                        <select class="form-control" name="country" id="country">
                                            <option value="0">Select country</option>
                                            @if(!empty($country_list))
                                                @foreach($country_list as $item)
                                                <option class="text-capitalize" value="{{$item['id']}}" {{old('country') == $item['id'] ? 'selected' : ''}}>{{$item['country_name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" name="hidden_person_id" value="{{$id}}" id="hidden_person_id">
                                <button type="submit" class="btn btn-primary">Add new address</button>
                            </div>

                        </form>
                </div>
                        @include('partials.status_bar')
<hr>

                        @if(!empty($address_list) && count($address_list)>0)
                            <form method="post" action="{{action('Applicant\AddressController@update', $id) }}">
                                <div class="form-group">
                                    @csrf
                                    <input name="_method" type="hidden" value="PATCH">
                                    <label for="email"><b>Current addresses:</b></label><br/><br/>
                                    @foreach($address_list as $el)
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label>Street *:</label>
                                            <input type="text" class="form-control" name="street_list[]" value="{{$el['street']}}" id="street_list[]">
                                        </div>
                                        <div class="col-lg-5">
                                            <label>State/Municipality *:</label>
                                            <input type="text" class="form-control province" name="province_list[]" value="{{$el['province']}}" id="province_list[]">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label>City *:</label>
                                            <input type="text" class="form-control city" name="city_list[]" value="{{$el['city']}}" id="city_list[]">
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Country *:</label>
                                            <select class="form-control" name="country_list[]" id="country_list[]">
                                                <option value="0">Select country</option>
                                                @if(!empty($country_list))
                                                    @foreach($country_list as $item)
                                                    <option class="text-capitalize" value="{{$item['id']}}" {{$el['country_id'] == $item['id'] ? 'selected' : ''}}>{{$item['country_name']}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-lg-1">
                                            <a href="{{action('Applicant\AddressController@destroy', $el['id'])}}" class="btn-link col-lg-2"> <i class="fa fa-trash"></i></a>
                                            <input type="hidden" class="form-control" name="address_list_hidden[]"
                                                    value="{{$el['id']}}" id="address">
                                        </div>
                                    </div><br/>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
                        @endif
                    </div>
                                </div>
        </div>
    </div>
</div>
@endsection
