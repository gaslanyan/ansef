@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Edit Your Profile
                        <br>
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\PersonController@update', $person_id) }}" class="row">
                            @csrf
                            <input name="_method" type="hidden" value="PATCH">
                        <input name="person_id" type="hidden" value="{{$person_id}}">
                            <div class="form-group col-lg-6">
                                <label for="f_name">First Name *:</label>
                                <input type="text" class="form-control" name="first_name" id="f_name"
                                       value="{{empty(old('first_name')) ? $person->first_name : old('first_name')}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="l_name">Last Name *:</label>
                                <input type="text" class="form-control" name="last_name" id="l_name"
                                       value="{{empty(old('last_name')) ? $person->last_name : old('last_name')}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthdate">Birth date:</label>
                                <input type="text" class="form-control date datepicker" name="birthdate" id="birthdate"
                                       value="{{empty(old('birthdate')) ? $person->birthdate : old('birthdate')}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthplace">Birth Place:</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace"
                                       value="{{empty(old('birthplace')) ? $person->birthplace : old('birthplace')}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="nationality">Nationality:</label>
                                <select class="form-control" name="nationality" id="nationality">
                                    <option value="0">Select country</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $item)
                                            <option class="text-capitalize" value="{{$item->country_name}}"
                                            @if ($item->country_name == $person->nationality || $item->country_name == old('nationality')) {{'selected'}}@endif>{{$item->country_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="type">Sex:</label>
                                <select id="type" class="form-control" name="sex">
                                    <?php $enum = getEnumValues('persons', 'sex');?>
                                    <option value="0">Select sex *:</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $item)
                                            <option class="text-capitalize"
                                                    @if ($item == $person['sex'] || $item == old('sex')) {{'selected'}}@endif
                                                    value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-12 ">
                                <label>Addresses:</label>
                                    <div class="row addresses">
                                        <div class="form-group col-lg-6">
                                            <label for="street">Street:</label>
                                        <input type="text" value="{{$address->street}}" class="form-control" name="street" id="street">
                                        </div>
                                    <div class="form-group col-lg-6">
                                        <label for="city">City:</label>
                                        <input value="{{$address->city}}" name="city" class="form-control" id="city">
                                    </div>
                                        <div class="form-group col-lg-6">
                                            <label for="provence">State/Municipality:</label>
                                            <input type="text" value="{{$address->province}}" class="form-control" name="provence" id="provence">
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="addr">Country:</label>
                                            <select id="addr" class="addr form-control" name="country">
                                                <option value="0">Select country</option>
                                                @if(!empty($countries))
                                                    @foreach($countries as $val=>$item)
                                                        <option class="text-capitalize"
                                                                value="{{$val}}" {{$val == $address->country_id ? 'selected' : ''}}>{{$item->country_name}}</option>
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

