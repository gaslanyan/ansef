@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">View Person
                        <a href="{{URL::previous()}}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>

                <div class="card-body card_body" style="overflow:auto;">

                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">{{$person->first_name}} {{$person->last_name}}</h4>
                            </div>

                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Birthdate:</strong>
                                            <p>{{$person->birthdate}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Birthplace:</strong>
                                            <p>{{$person->birthplace}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Sex:</strong>
                                            <p>{{$person->sex}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Nationality:</strong>
                                            <p>{{$person->nationality}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Location:</strong>
                                            <p>{{$person->state}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Role:</strong>
                                            <p>{{$person->type}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Specializations:</strong>
                                            @if(empty($person->specialization) || $person->specialization=='')
                                            <span>None provided</span>
                                            @else
                                            <span>{{$person->specialization}}</span>
                                            @endif
                                    </div>
                                </div>
                                <br/>
                                @if(!empty($addresses) && count($addresses)>0)
                                <div class="row" >
                                    <h4 style="color:#777;">Addresses</h4>
                                </div>
                                @foreach($addresses as $address)
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Street:</strong>
                                            <span>{{$address->street}}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>City:</strong>
                                            <span>{{$address->city}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Municipality/State:</strong>
                                            <span>{{$address->province}}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Country:</strong>
                                            <span>{{$address->country->country_name}}</span>
                                    </div>
                                </div>
                                <hr>
                                @endforeach
                                @else
                                <div class="row" >
                                    <h6 style="color:#777;">No addresses provided</h6>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

