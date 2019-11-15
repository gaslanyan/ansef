@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Edit your info</div>

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

                        <form method="post" action="{{ action('Referee\PersonController@update', $id) }}" class="row">
                            @csrf
                            <input name="_method" type="hidden" value="PATCH">
                            <div class="form-group col-lg-6">
                                <label for="f_name">First Name:</label>
                                <input type="text" class="form-control" name="first_name" id="f_name"
                                       value="{{$person['first_name']}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="l_name">Last Name:</label>
                                <input type="text" class="form-control" name="last_name" id="l_name"
                                       value="{{$person['last_name']}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthdate">Birth date:</label>
                                <input type="text" class="form-control date datepicker" name="birthdate" id="birthdate"
                                       value="{{$person['birthdate']}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthplace">Birth Place:</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace"
                                       value="{{$person['birthplace']}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="nationality">Nationality:</label>
                                <select class="form-control" name="nationality" id="nationality">
                                    <option value="0">Select country</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $item)
                                            @if($item == $person['nationality'])
                                                <option class="text-capitalize" value="{{$item}}"
                                                        selected>{{$item}}</option>
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
                                            <option class="text-capitalize"
                                                    @if ($item == $person['sex']) {{'selected'}}@endif
                                                    value="{{$item}}">{{$item}}</option>
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
                                        <?php //var_dump($address);die;?>
                                        <div class="row addresses">
                                            <div class="form-group col-lg-6">
                                                <label for="provence">Provence:</label>
                                                <input type="text" class="form-control" name="provence[0]" id="provence"
                                                       value="{{$address['province']}}">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="street">Municipality/state* :</label>
                                                <input type="text" class="form-control" name="street[0]" id="street"
                                                       value="{{$address['street']}}">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="city">City:</label>
                                                <?php $selected = "";
                                                $selected_id = 0;?>
                                                <datalist id="city" name="city[0]">
                                                    <option data-value="0" value="Select City"></option>
                                                    <option data-value="{{$address['city_id']}}"
                                                            value="{{$address['city']}}">
                                                    </option>
                                                    <?php
                                                    //if ($key == $scoreType->competition_id):
                                                    $selected = $address['city'];
                                                    $selected_id = $address['city_id'];
                                                    //                                                    endif
                                                    ?>

                                                    {{--</select>--}}
                                                </datalist>
                                                <input list="city" name="city[]" class="form-control"
                                                       value="{{$selected}}" id="_city">
                                                <input type="hidden" name="city_id[]" value="{{$selected_id}}"
                                                       id="city_id">
                                            </div>
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
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row addresses">
                                        <div class="form-group col-lg-6">
                                            <label for="addr">Country:</label>
                                            <select id="addr" class="addr form-control" name="countries[0]">
                                                <option value="0">Select country</option>
                                                @if(!empty($countries))
                                                    @foreach($countries as $val=>$item)
                                                        <option class="text-capitalize"
                                                                value="{{$val}}">{{$item}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="provence">Provence:</label>
                                            <input type="text" class="form-control" name="provence[0]" id="provence">
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="city">City:</label>
                                            <?php $selected = "";
                                            $selected_id = 0;?>
                                            <datalist id="city" name="city[0]">
                                                <option data-value="0" value="Select City"></option>

                                            </datalist>
                                            <input list="city" name="city[]" class="form-control"
                                                   value="" id="_city">
                                            <input type="hidden" name="city_id[]" value=""
                                                   id="city_id">
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="street">Street:</label>
                                            <input type="text" class="form-control" name="street[0]" id="street">
                                        </div>

                                    </div>
                                @endif
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

