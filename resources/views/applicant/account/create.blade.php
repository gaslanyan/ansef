@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card"  >
                    <div class="card-header">Add A New Person</div>

                    <div class="card-body card_body">
                        @include('partials.status_bar')


                        <p><i class="fas fa-question-circle text-blue"></i></i><span style="color:#777;margin-left:10px;">Add basic data about a person
                        who will server as either support to a project or a participant in a project.</span></p>
                        <p><i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i></p>
                        <form method="post" action="{{ action('Applicant\AccountController@store') }}" class="row">
                            {{ csrf_field() }}
                            <div class="form-group col-lg-6">
                                <label for="f_name">* First Name:</label>
                                <input type="text" class="form-control" name="first_name" id="f_name" value="{{ old('first_name') }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="l_name">* Last Name:</label>
                                <input type="text" class="form-control" name="last_name" id="l_name" value="{{ old('last_name') }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="birthdate">* Birth date:</label>
                                <input type="text" class="form-control date datepicker" name="birthdate" id="birthdate" value="{{ old('birthdate') }}">
                           </div>
                            <div class="form-group col-lg-6">
                                <label for="birthplace">* Birth Place:</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace" value="{{ old('birthplace') }}">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="specialization">Specializations:</label>
                                <input type="text" class="form-control" name="specialization" id="specialization" value="{{ old('specialization') }}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="state">* Is person based in Armenia?:</label>
                                <?php $enum = getEnumValues('persons', 'state'); ?>
                                <select id="state" class="form-control" name="state">
                                    <option>Select location</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $val => $item)
                                            <option class="text-capitalize" <?php if(old('state') == $val) echo 'selected'; ?> value="{{$val}}">{{$item == 'foreign' ? 'Based outside Armenia' : 'Based in Armenia'}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="type">* Will this person participate in a project or only provide support?:</label>
                                <select id="type" class="form-control" name="type">
                                    <?php $enum = getEnumValues('persons', 'type');?>
                                    <option>Select person's role</option-->
                                    @if(!empty($enum))
                                        @foreach($enum as $val=>$item)
                                            @if($item != "admin" && $item != "referee" && $item != "viewer" && $item != "applicant")
                                                <option class="text-capitalize" <?php if(old('type') == $val) echo 'selected'; ?> value="{{$item}}">{{$item == 'participant' ? 'Will participate' : 'Will support only'}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="nationality">* Nationality:</label>
                                <select class="form-control" name="nationality" id="nationality">
                                    <option>Select country</option>
                                    @if(!empty($countries))
                                        @foreach($countries as $val=>$item)
                                            <option class="text-capitalize" <?php if(old('nationality') == $val) echo 'selected'; ?> value="{{$item}}">{{$item}}</option>
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
                                            <option class="text-capitalize" <?php if(old('sex') == $val) echo 'selected'; ?> value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>




                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ action('Applicant\AccountController@index') }}" class="btn btn-secondary"> Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


