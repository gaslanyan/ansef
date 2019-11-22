@extends('layouts.master')

@section('content')
    <div class="container">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
            </div><br/>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                    <div class="card-header">Add an institution
                        <br>
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                        @endif
                        <form method="post" action="{{ action('Admin\InstitutionController@store') }}" class="row">
                            @csrf
                            <div class="form-group col-lg-12">
                                <label for="name">Institution name *:</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}">
                            </div>

                            <div class="col-lg-12 ">
                                <label>Address:</label>

                                <div class="row addresses">
                                     <div class="form-group col-lg-6">
                                        <label for="street">Street:</label>
                                        <input type="text" class="form-control" name="street" id="street" value="{{old('street')}}">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="provence">Municipality/State:</label>
                                        <input type="text" class="form-control"
                                               name="provence" id="provence" value="{{old('provence')}}">
                                    </div>
                                   <div class="form-group col-lg-6">
                                        <label for="city">City *:</label>

                                        <datalist id="city" name="city">
                                            <option data-value="0" value="Select City"></option>

                                        </datalist>
                                        <input list="city" name="city" class="form-control"
                                    value="{{old('city')}}" id="_city">
                                        <input type="hidden" name="city_id" value=""
                                               id="city_id">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="addr">Country *:</label>
                                        <select id="addr" class="addr form-control" name="countries">
                                            <option value="">Select country</option>
                                            @if(!empty($countries))
                                                @foreach($countries as $val=>$item)
                                                    <option class="text-capitalize" @if(old('countries') == $val) {{'selected'}} @endif
                                                            value="{{$val}}">{{$item}}</option>
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

