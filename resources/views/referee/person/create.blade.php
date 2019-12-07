@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                 <div class="card" >
                    <div class="card-header">Create Your Profile
                        <br>
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')


                        <form method="post" action="{{ action('Referee\PersonController@store') }}" class="row">
                            @csrf
                            <div class="form-group col-lg-6">
                                <label for="f_name">First Name *:</label>
                                <input type="text" class="form-control" name="first_name" id="f_name">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="l_name">Last Name *:</label>
                                <input type="text" class="form-control" name="last_name" id="l_name">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthdate">Birth date *:</label>
                                <input type="text" class="form-control date datepicker" name="birthdate" id="birthdate">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthplace">Birth Place *:</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace">
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="nationality">Nationality *:</label>
                                <select class="form-control" name="nationality" id="nationality">
                                    <option value="0">Select country</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $item)
                                            <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="type">Sex *:</label>
                                <select  id="type" class="form-control" name="sex">
                                    <?php $enum = getEnumValues('persons', 'sex');?>
                                    <option value="0">Select sex</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $item)
                                            <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-12 ">
                                <label>Addresses:</label>
                                <i class="fa fa-plus pull-right add text-blue"
                                   style="cursor: pointer"></i>
                                <div class="row addresses">
                                    <div class="form-group col-lg-6">
                                        <label for="street">Street *:</label>
                                        <input type="text" class="form-control" name="street[0]" id="street">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="city">City *:</label>

                                        <datalist id="city" name="city[0]">
                                            <option data-value="0" value="Select City"></option>

                                        </datalist>
                                        <input list="city" name="city[]" class="form-control"
                                               value="" id="_city">
                                        <input type="hidden" name="city_id[]" value=""
                                               id="city_id">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="provence">Municipality/State *:</label>
                                        <input type="text" class="form-control" name="provence[0]" id="provence">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="addr">Country *:</label>
                                        <select id="addr" class="addr form-control" name="countries[0]">
                                            <option value="0">Select country</option>
                                            @if(!empty($countries))
                                                @foreach($countries as $val=>$item)
                                                    <option class="text-capitalize" value="{{$val}}">{{$item}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Add Person</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

