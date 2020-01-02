@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header">Edit Person Data
                    </div>
                    <div class="card-body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> </i>&nbsp;<i class="text-blue">{{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')

                        <p><i class="fas fa-question-circle text-blue"></i></i><span style="color:#777;margin-left:10px;">Add basic data about a person
                        who will serve as either support to a project or a participant in a project.</span></p>
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
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href = "{{ action('Applicant\AccountController@index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                        <br/><br/><br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

