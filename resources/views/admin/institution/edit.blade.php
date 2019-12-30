@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Edit Institution
                    </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\InstitutionController@update', $id) }}"
                              class="row">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="form-group col-lg-12">
                                <label for="name">Institution Name *:</label>
                                <input type="text" class="form-control" name="name" id="name"
                                       value="{{$institution['content']}}">
                            </div>

                            <div class="col-lg-12 ">
                                <label>Address:</label>

                                <div class="row addresses">

                                    <div class="form-group col-lg-6">
                                        <label for="street">Street:</label>
                                        <input type="text" class="form-control" name="street" id="street"
                                               value="{{$address->street}}">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="provence">State/Municipality:</label>
                                        <input type="text" class="form-control" name="provence" id="provence"
                                               value="{{$address->province}}">
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label for="city">City *:</label>
                                        <input list="city" name="city" class="form-control"
                                               value="{{$address->city}}" id="city">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="country">Country *:</label>
                                        <select id="country" class="addr form-control" name="country">
                                            <option value="0">Select country</option>
                                            @if(!empty($countries))
                                                @foreach($countries as $val=>$item)
                                                    @if($address->country_id == $val)
                                                        <option class="text-capitalize" value="{{$val}}"
                                                                selected>{{$item['country_name']}}</option>
                                                    @else
                                                        <option class="text-capitalize"
                                                                value="{{$val}}">{{$item['country_name']}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ action('Admin\InstitutionController@index') }}" class="btn btn-secondary"> Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

