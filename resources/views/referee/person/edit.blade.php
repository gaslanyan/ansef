@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Edit your profile</div>

                    <div class="card-body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')


                        <form method="post" action="{{ action('Referee\PersonController@update', $id) }}" class="row">
                            @csrf
                            <div class="form-group col-lg-6">
                                <label for="f_name">First Name*:</label>
                                <input type="text" class="form-control" name="first_name" id="f_name"
                                       value="{{$person['first_name']}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="l_name">Last Name*:</label>
                                <input type="text" class="form-control" name="last_name" id="l_name"
                                       value="{{$person['last_name']}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthdate">Birth date:</label>
                                <input type="text" class="form-control date datepicker" name="birthdate" id="birthdate"
                                       value="{{$person['birthdate']}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthplace">Birth place:</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace"
                                       value="{{$person['birthplace']}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="nationality">Nationality*:</label>
                                <select class="form-control" name="nationality" id="nationality">
                                    <option value="0">Select country</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $item)
                                            @if($item->country_name == $person['nationality'])
                                                <option class="text-capitalize" value="{{$item->country_name}}"
                                                        selected>{{$item->country_name}}</option>
                                            @else
                                                <option class="text-capitalize" value="{{$item->country_name}}">{{$item->country_name}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="type">Sex:</label>
                                <select id="type" class="form-control" name="sex">
                                    <?php $enum = getEnumValues('persons', 'sex');?>
                                    <option value="0">Select sex</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $item)
                                            <option class="text-capitalize"
                                                    @if ($item == $person['sex']) {{'selected'}}@endif
                                                    value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-12 ">
                                <label>Address:</label>
                                    <div class="row addresses">
                                        <div class="form-group col-lg-6">
                                            <label for="street">Street:</label>
                                            <input value="{{$address['street']}}" type="text" class="form-control" name="street" id="street">
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="city">City:</label>
                                            <input type="text" name="city" class="form-control" value="{{$address['city']}}" id="city">
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="provence">State/Municipality:</label>
                                            <input value="{{$address['province']}}" type="text" class="form-control" name="province" id="province">
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="country_id">Country*:</label>
                                            <select id="country_id" class="addr form-control" name="country_id">
                                                <option value="0">Select country</option>
                                                @if(!empty($countries))
                                                    @foreach($countries as $item)
                                                        @if($item->id == $address['country_id'])
                                                        <option class="text-capitalize" value="{{$item->id}}" selected>{{$item->country_name}}</option>
                                                        @else
                                                        <option class="text-capitalize" value="{{$item->id}}">{{$item->country_name}}</option>
                                                        @endif
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

