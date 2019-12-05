@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">Edit Person Data
                    </div>
                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        <p><i class="fas fa-question-circle text-blue"></i></i><span style="color:#777;margin-left:10px;">Add basic data about a person
                        who will serve as either support to a project or a participant in a project.</span></p>
                        <p><i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i></p>
                        <form method="post" action="{{ action('Applicant\PersonController@update', $id) }}" class="row">
                            @csrf
                            <input name="_method" type="hidden" value="PATCH">
                            <div class="form-group col-lg-6">
                                <label for="f_name">* First Name:</label>
                                <input type="text" class="form-control" name="first_name" id="f_name"
                                       value="<?php if(!empty($person['first_name'])) echo $person['first_name']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="l_name">* Last Name:</label>
                                <input type="text" class="form-control" name="last_name" id="l_name"
                                       value="<?php if(!empty($person['last_name'])) echo $person['last_name']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthdate">* Birth date:</label>
                                <input type="text" class="form-control date datepicker" name="birthdate" id="birthdate"
                                       value="<?php if(!empty($person['birthdate'])) echo $person['birthdate']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthplace">* Birth Place:</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace"
                                       value="<?php if(!empty($person['birthplace'])) echo $person['birthplace']; ?>">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="specialization">Specializations:</label>
                                <input type="text" class="form-control" name="specialization" id="specialization" value="{{ $person['specialization'] }}">
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="state">* Is person based in Armenia?:</label>
                                <select id="state" class="form-control" name="state">
                                    <?php $enum = getEnumValues('persons', 'state');?>
                                    <option>Select location</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $val => $item)
                                            <option class="text-capitalize" <?php if($person['state'] == $val) echo 'selected'; ?> value="{{$val}}">{{$item == 'foreign' ? 'Based outside Armenia' : 'Based in Armenia'}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="type">Will this person participate in a project or only provide support?:</label>
                                <b><?php echo($person['type'] == 'contributor' ? 'Will participate' : 'Will support only'); ?></b>
                                <p><i class="fas fa-question-circle text-blue"></i></i><span style="color:#777;margin-left:10px;">The person
                                type cannot be changed.</span></p>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="nationality">* Nationality:</label>
                                <select class="form-control" name="nationality" id="nationality">
                                    <option>Select country</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $val=>$item)
                                            <option class="text-capitalize" <?php if($person['nationality'] == $item) echo 'selected'; ?> value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="type">* Sex:</label>
                                <select id="type" class="form-control" name="sex">
                                    <?php $enum = getEnumValues('persons', 'sex');?>
                                    <option>Select sex</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $val=>$item)
                                            <option class="text-capitalize" <?php if($person['sex'] == $val) echo 'selected'; ?> value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <!--<div class="col-lg-12 " style="margin-top:30px;">-->
                            <!--    <label>* Addresses (at least most recent one required):</label>-->
                            <!--    <i class="fa fa-plus pull-right add text-blue" style="cursor: pointer"></i>-->
                            <!--    <p style="font-size:10px;"><i class="fas fa-question-circle text-blue"></i><span style="color:#777;margin-left:10px;">To add more, click the + button.</span></p>-->

                            <!--    <div class="row addresses">-->
                            <!--        <div class="form-group col-lg-6">-->
                            <!--            <label for="street">Street:</label>-->
                            <!--            <input type="text" class="form-control" name="street[0]" id="street">-->
                            <!--        </div>-->
                            <!--        <div class="form-group col-lg-6">-->
                            <!--            <label for="city">City:</label>-->

                            <!--        </div>-->
                            <!--        <div class="form-group col-lg-6">-->
                            <!--            <label for="addr">Country:</label>-->
                            <!--            <select id="addr" class="addr form-control" name="countries[0]">-->
                            <!--                <option value="0">Select country</option>-->
                            <!--                @if(!empty($countries))-->
                            <!--                    @foreach($countries as $val=>$item)-->
                            <!--                        <option class="text-capitalize"  value="{{$val}}">{{$item}}</option>-->
                            <!--                    @endforeach-->
                            <!--                @endif-->
                            <!--            </select>-->
                            <!--        </div>-->
                            <!--        <div class="form-group col-lg-6">-->
                            <!--            <label for="provence">Municipality/State:</label>-->
                            <!--            <input type="text" class="form-control" name="provence[0]" id="provence">-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->


                            {{--<div class="col-lg-12 ">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="form-group col-lg-6 emails">--}}
                                        {{--<label for="email">Emails:</label>--}}
                                        {{--<i class="fa fa-plus pull-right add  text-blue"--}}
                                           {{--style="cursor: pointer"></i>--}}
                                        {{--<input type="text" class="form-control email" name="email[]"--}}
                                               {{--id="email">--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group col-lg-6 phones">--}}
                                        {{--<label for="phone">Phones:</label>--}}
                                        {{--<i class="fa fa-plus pull-right add text-blue"--}}
                                           {{--style="cursor: pointer"></i>--}}
                                        {{--<input type="text" class="form-control phone" name="phone[]"--}}
                                               {{--id="phone">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <!--<div class="col-lg-12 " style="margin-top:30px;">-->
                            <!--    <label>Affiliations / Employments:</label>-->
                            <!--    <i class="fa fa-plus pull-right add text-blue" style="cursor: pointer"></i>-->
                            <!--    <p style="font-size:10px;"><i class="fas fa-question-circle text-blue"></i><span style="color:#777;margin-left:10px;">To add more, click the + button.</span></p>-->

                            <!--    <div class="row institution">-->
                            <!--        <div class="form-group col-lg-4">-->
                            <!--            <label for="inst">Institution:</label>-->
                            <!--            <select id="inst" class="form-control" name="institution[]">-->
                            <!--                <option value="0">Select institution</option>-->
                            <!--                @if(!empty($institutions))-->
                            <!--                    @foreach($institutions as $val=>$item)-->
                            <!--                        <option class="text-capitalize" value="{{$val}}">{{$item}}</option>-->
                            <!--                    @endforeach-->
                            <!--                @endif-->
                            <!--            </select>-->
                            <!--            <input type="text" class="form-control" name="i_custominstitution[]" id="custominstitution">-->
                            <!--            <p style="font-size:10px;"><i class="fas fa-question-circle text-blue"></i></i><span style="color:#777;margin-left:10px;">Type institution name if not in list.</span></p>-->
                            <!--        </div>-->
                            <!--        <div class="form-group col-lg-2">-->
                            <!--            <label for="title">Title:</label>-->
                            <!--            <input type="text" class="form-control" name="i_title[]" id="title">-->
                            <!--        </div>-->
                            <!--        <div class="form-group col-lg-2">-->
                            <!--            <label for="i_type">Position at institution:</label>-->
                            <!--            <select id="i_type" class="form-control" name="i_type[]">-->
                            <!--                <option value="0">Select</option>-->
                            <!--                <option value="affiliation">Affiliated</option>-->
                            <!--                <option value="employment">Employed</option>-->
                            <!--            </select>-->
                            <!--        </div>-->
                            <!--        <div class="form-group col-lg-2">-->
                            <!--            <label for="start">Start date:</label>-->
                            <!--            <input type="date" class="form-control" name="start[]" id="start">-->
                            <!--        </div>-->
                            <!--        <div class="form-group col-lg-2">-->
                            <!--            <label for="end">End date:</label>-->
                            <!--            <input type="date" class="form-control" name="end[]" id="end">-->
                            <!--            <p style="font-size:10px;"><i class="fas fa-question-circle text-blue"></i></i><span style="color:#777;margin-left:10px;">Leave blank if current.</span></p>-->
                            <!--        </div>-->

                            <!--    </div>-->
                            <!--</div>-->

                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href = "{{ action('Applicant\AccountController@index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

