@extends('layouts.master')
<?php $user_id = getPersonIdByRole('admin');?>
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Change Password</div>

                    <div class="card-body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\PersonController@updatePassword') }}" class="row">
                            {{ csrf_field() }}
                            <div class="form-group col-lg-6">
                                <label for="oldpassword">Old Password:</label>
                                <input type="password" class="form-control" name="oldpassword" id="oldpassword" value="{{old('oldpassword')}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="newpassword">New Password</label>
                                <input type="password" class="form-control" name="newpassword" id="newpassword" value="{{old('newpassword')}}">

                                <label for="confirmpassword">Confirm Password</label>
                                <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" value="{{old('confirmpassword')}}">
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

