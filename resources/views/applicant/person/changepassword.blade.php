@extends('layouts.master')
<?php $user_id = getPersonIdByRole(null); ?>
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Change Your Password
                        <a href="{{ action('Applicant\ApplicantController@index') }}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Applicant\PersonController@updatePassword') }}" class="row">
                            {{ csrf_field() }}
                            <div class="form-group col-lg-6">
                                <label for="oldpassword">Old Password:</label>
                                <input type="password" class="form-control" name="oldpassword" id="oldpassword">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="newpassword">New Password</label>
                                <input type="password" class="form-control" name="newpassword" id="newpassword">

                                <label for="confirmpassword">Confirm Password</label>
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

