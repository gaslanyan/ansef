@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Edit Person </div>
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
                        <form method="post" action="{{ action('Viewer\PersonController@update', $id) }}" class="row">
                            @csrf
                            <input name="_method" type="hidden" value="PATCH">
                            <div class="form-group col-lg-6">
                                <label for="f_name">First Name:</label>
                                <input type="text" class="form-control" name="first_name" id="f_name"
                                       value="<?php if(!empty($person['first_name'])) echo $person['first_name']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="l_name">Last Name:</label>
                                <input type="text" class="form-control" name="last_name" id="l_name"
                                       value="<?php if(!empty($person['last_name'])) echo $person['last_name']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthdate">Birth date:</label>
                                <input type="text" class="form-control date datepicker" name="birthdate" id="birthdate"
                                       value="<?php if(!empty($person['birthdate'])) echo $person['birthdate']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthplace">Birth Place:</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace"
                                       value="<?php if(!empty($person['birthplace'])) echo $person['birthplace']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="state">Account State:</label>
                                <select id="state" class="form-control" name="state">
                                    <?php $enum = getEnumValues('persons', 'state');?>
                                    <option value="0">Select state</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $item)
                                            @if($item  == $person['state'])
                                                <option class="text-capitalize" value="{{$person['state']}}"
                                                        selected>{{$person['state']}}</option>
                                            @else
                                                <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="type">Account Type:</label>
                                <select id="type" class="form-control" name="type"  <?php if($person['type'] == 'PI') echo "disabled" ?> >
                                    <?php $enum = getEnumValues('persons', 'type');?>
                                    <option>Select type</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $item)
                                            @if($item != "admin" && $item != "referee" && $item != "viewer")
                                                <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                            @endif
                                            @if($item == $person['type'])
                                                <option class="text-capitalize" value="{{$item}}"
                                                        selected>{{$item}}</option>
                                            @endif;
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="nationality">Nationality:</label>
                                <select class="form-control" name="nationality" id="nationality">
                                    <option value="0">Select country</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $item)
                                            @if($item == $person['nationality'])
                                                <option class="text-capitalize" value="{{$person['nationality']}}"
                                                        selected>{{$person['nationality']}}</option>
                                            @else
                                                <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
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
                                            @if($item == $person['sex'])
                                                <option class="text-capitalize" value="{{$person['sex']}}"
                                                        selected>{{$person['sex']}}</option>
                                            @else
                                                <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-12 ">
                                <label>Addresses:</label>
                                <i class="fa fa-plus pull-right add text-blue"
                                   style="cursor: pointer"></i>
                                @if(!empty($fulladddress))
                                @foreach($fulladddress as $address)
                                    <div class="row addresses">
                                        <div class="form-group col-lg-6">
                                            <label for="addr">Country:</label>
                                            <select id="addr" class="addr form-control" name="countries[0]">
                                                <option value="0">Select country</option>
                                                @if(!empty($countries))
                                                    @foreach($countries as $val=>$item)
                                                        @if($address['country'] == $item)
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
                                            <label for="provence">Provence:</label>
                                            <input type="text" class="form-control" name="provence[0]" id="provence"
                                                   value="{{$address['province']}}">
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="city">City:</label>
                                            <select id="city" class="city form-control" name="city[0]">
                                                <option value="0">Select city</option>
                                                <option value="{{$address['city_id']}}"
                                                        selected>{{$address['city']}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="street">Street:</label>
                                            <input type="text" class="form-control" name="street[0]" id="street"
                                                   value="{{$address['street']}}">
                                        </div>

                                    </div>
                                @endforeach
                                @endif
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Edit Person</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

