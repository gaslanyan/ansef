@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Edit Your Profile
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')

                        <form method="post" action="{{action('Admin\PersonController@update', $id)}}" class="row">
                            @csrf
                            <div class="form-group col-lg-6">
                                <label for="first_name">First Name *:</label>
                                <input value="{{$person->first_name}}" type="text" class="form-control" name="first_name" id="f_name">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="last_name">Last Name *:</label>
                                <input value="{{$person->last_name}}" type="text" class="form-control" name="last_name" id="l_name">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthdate">Birth date:</label>
                                <input value="{{$person->birthdate}}" type="text" class="form-control date datepicker" name="birthdate" id="birthdate">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthplace">Birth Place:</label>
                                <input value="{{$person->birthplace}}" type="text" class="form-control" name="birthplace" id="birthplace">
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="nationality">Nationality:</label>
                                <select class="form-control" name="nationality" id="nationality">
                                    <option value="0">Select country</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $item)
                                            <option class="text-capitalize" value="{{$item->id}}" {{$person->nationality == $item->id ? 'selected' : ''}}>{{$item->country_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="type">Sex:</label>
                                <select  id="type" class="form-control" name="sex">
                                    <?php $enum = getEnumValues('persons', 'sex');?>
                                    <option value="0">Select sex</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $item)
                                            <option class="text-capitalize" value="{{$item}}" {{$person->sex == $item ? 'selected' : ''}}>{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-12 ">
                                <label>Address:</label>
                                <div class="row addresses">
                                    <div class="form-group col-lg-6">
                                        <label for="street">Street:</label>
                                        <input value="{{$address->street}}" type="text" class="form-control" name="street" id="street">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="city">City:</label>
                                        <input value="{{$address->city}}" list="city" name="city" class="form-control"
                                               value="" id="city">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="province">Municipality/State:</label>
                                        <input value="{{$address->province}}" type="text" class="form-control" name="province" id="province">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="addr">Country *:</label>
                                        <select id="country" class="addr form-control" name="country">
                                            <option value="0">Select country</option>
                                            @if(!empty($countries))
                                                @foreach($countries as $country)
                                                    <option class="text-capitalize" value="{{$country->id}}" {{$address->country_id == $country->id ? 'selected' : '' }}>{{$country->country_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

