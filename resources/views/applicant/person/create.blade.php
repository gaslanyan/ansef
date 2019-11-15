@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Create Person
                        <a href="{{action('Applicant\PersonController@create')}}"
                           class="display float-lg-right btn-primary px-2">Back</a>
                    </div>
                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        <form method="post" action="{{ action('Applicant\PersonController@store') }}" class="row">
                            {{ csrf_field() }}
                            <div class="form-group col-lg-6">
                                <label for="f_name">First Name:</label>
                                <input type="text" class="form-control" name="first_name" id="f_name" value="{{ old('first_name') }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="l_name">Last Name:</label>
                                <input type="text" class="form-control" name="last_name" id="l_name" value="{{ old('last_name') }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthdate">Birth date:</label>
                                <input type="date" class="form-control " name="birthdate" id="birthdate" value="{{ old('birthdate') }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthplace">Birth Place:</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace" value="{{ old('birthplace') }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="state">Account State:</label>
                                <select id="state" class="form-control" name="state">
                                    <?php $enum = getEnumValues('persons', 'state');?>
                                    <option>Select state</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $val => $item)
                                            <option class="text-capitalize" <?php if(old('state') == $val) echo 'selected'; ?> value="{{$item}}">{{$item}}</option>
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
                                        @foreach($enum as $val => $item)
                                            @if($item != "admin" && $item != "referee" && $item != "viewer")
                                                <option class="text-capitalize" <?php if(old('type') == $val) echo 'selected'; ?> value="{{$item}}">{{$item}}</option>
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
                                        @foreach($countries as $val => $item)
                                            <option class="text-capitalize" <?php if(old('nationality') == $val) echo 'selected'; ?> value="{{$item}}">{{$item}}</option>
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
                                        @foreach($enum as $val=>$item)
                                            <option class="text-capitalize" <?php if(old('sex') == $val) echo 'selected'; ?> value="{{$item}}">{{$item}}</option>
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
                            <div class="form-group col-lg-1">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                            <div class="form-group col-lg-1">
                                <input type="reset" class="btn btn-primary" value ="Cancel">
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

