@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Create Person</div>
                    <div class="card-body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Viewer\PersonController@store') }}" class="row">
                            {{ csrf_field() }}
                            <div class="form-group col-lg-6">
                                <label for="f_name">First Name:</label>
                                <input type="text" class="form-control" name="first_name" id="f_name">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="l_name">Last Name:</label>
                                <input type="text" class="form-control" name="last_name" id="l_name">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthdate">Birth date:</label>
                                <input type="date" class="form-control " name="birthdate" id="birthdate">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthplace">Birth Place:</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="state">Account State:</label>
                                <select id="state" class="form-control" name="state">
                                    <?php $enum = getEnumValues('persons', 'state');?>
                                    <option>Select state</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $item)
                                            <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="type">Account Type:</label>
                                <select id="type" class="form-control" name="type">
                                    <?php $enum = getEnumValues('persons', 'type');?>
                                    <option>Select type</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $item)
                                            @if($item != "admin" && $item != "referee" && $item != "viewer")
                                                <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="nationality">Nationality:</label>
                                <select class="form-control" name="nationality" id="nationality">
                                    <option>Select country</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $item)
                                            <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="type">Sex:</label>
                                <select id="type" class="form-control" name="sex">
                                    <?php $enum = getEnumValues('persons', 'sex');?>
                                    <option>Select sex</option>
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
                                        <label for="addr">Country:</label>
                                        <select id="addr" class="addr form-control" name="countries[0]">
                                            <option value="0">Select country</option>
                                            @if(!empty($countries))
                                                @foreach($countries as $val=>$item)
                                                    <option class="text-capitalize" value="{{$val}}">{{$item}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="provence">Province:</label>
                                        <input type="text" class="form-control" name="provence[0]" id="provence">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="city">City:</label>
                                        <?php $selected = "";
                                        $selected_id = 0;?>
                                        <datalist id="city" name="city[0]">
                                            <option data-value="0" value="Select City"></option>
                                            <option data-value=""
                                                    value="">
                                            </option>

                                        </datalist>
                                        <input list="city" name="city[]" class="form-control"
                                               value="{{$selected}}" id="_city">
                                        <input type="hidden" name="city_id[]" value="{{$selected_id}}"
                                               id="city_id">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="street">Street:</label>
                                        <input type="text" class="form-control" name="street[0]" id="street">
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12 ">
                                <div class="row">
                                    <div class="form-group col-lg-6 emails">
                                        <label for="email">Emails:</label>
                                        <i class="fa fa-plus pull-right add  text-blue"
                                           style="cursor: pointer"></i>
                                        <input type="text" class="form-control email" name="email[]"
                                               id="email">
                                    </div>
                                    <div class="form-group col-lg-6 phones">
                                        <label for="phone">Phones:</label>
                                        <i class="fa fa-plus pull-right add text-blue"
                                           style="cursor: pointer"></i>
                                        <input type="text" class="form-control phone" name="phone[]"
                                               id="phone">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 ">
                                <label>Affiliations / Employments:</label>
                                <i class="fa fa-plus pull-right add text-blue"
                                   style="cursor: pointer"></i>

                                <div class="row institution">
                                    <div class="form-group col-lg-4">
                                        <label for="inst">Institution:</label>
                                        <select id="inst" class="form-control" name="institution[]">
                                            <option value="0">Select institution</option>
                                            @if(!empty($institutions))
                                                @foreach($institutions as $val=>$item)
                                                    <option class="text-capitalize" value="{{$val}}">{{$item}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="title">Title:</label>
                                        <input type="text" class="form-control" name="i_title[]" id="title">
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="i_type">Institution type:</label>
                                        <select id="i_type" class="form-control" name="i_type[]">
                                            <option value="0">Select type</option>
                                            <option value="affiliation">Affiliation</option>
                                            <option value="employment">Employment</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="start">Start:</label>
                                        <input type="date" class="form-control" name="start[]" id="start">
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="end">End:</label>
                                        <input type="date" class="form-control" name="end[]" id="end">
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

