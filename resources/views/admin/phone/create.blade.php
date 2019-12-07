@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                        @include('partials.status_bar')
                    <div class="card-header">Add a phone number</div>
                    <div class="card-body card_body" style="overflow:auto;">
                        <form method="post" action="{{ action('Admin\PhoneController@store') }}">
                            @csrf
                            <div class="col-lg-12 ">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="form-group col-lg-12 phones">
                                            <label for="phone">Phones:</label>
                                            <i class="fa fa-plus pull-right add text-blue"
                                               style="cursor: pointer"></i>
                                            <div class="col-12">
                                                <div class="row">
                                                    <input type="text" name="country_code[0]"
                                                           class="form-control col-lg-2">

                                                    <input type="text" class="form-control phone col-lg-10"
                                                           name="phone[0]"
                                                           id="phone">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <a href = "{{ action('Admin\PhoneController@index') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
