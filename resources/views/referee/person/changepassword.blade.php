@extends('layouts.master')
<?php $user_id = \Auth::guard('referee')->user()->id;?>
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Change Password
                        <br>
                        <i class="fas fa-question-circle text-blue all">{{Lang::get('messages.required_all')}}</i>

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
                        <form method="post" action="{{ action('Referee\PersonController@updatePassword') }}"
                              class="row">
                            {{ csrf_field() }}
                            <div class="form-group col-lg-6">
                                <label for="oldpassword">Old Password *:</label>
                                <input type="password" class="form-control" name="oldpassword" id="oldpassword">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="newpassword">New Password *:</label>
                                <input type="password" class="form-control" name="newpassword" id="newpassword">
                                <br>
                                <label for="confirmpassword">Confirm Password *:</label>
                                <input type="password" class="form-control" name="confirmpassword" id="confirmpassword">
                            </div>

                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

