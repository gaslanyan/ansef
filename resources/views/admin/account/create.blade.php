@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">Add a person
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')

                        <form method="post" action="{{action('Admin\AccountController@store')}}" class="row">
                            @csrf
                            <div class="col-lg-12 ">
                                <div class="row">
                                    <div class="form-group col-lg-6 emails">
                                        <label for="role">Role:</label>
                                        <select id="role" class="form-control" name="role" >
                                            <option >Select role</option>
                                            @if(!empty($roles))
                                                @foreach($roles as $role)
                                                    <option class="text-capitalize"
                                                            @php
                                                               $old = old('role');
                                                            @endphp
                                                     @if(isset($old) && $role->id == $old) {{'selected'}}  @endif    value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 emails">
                                        <label for="email">Email *:</label>
                                        <input type="text" class="form-control email" name="email" value="{{old('email')}}" id="email">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Add Person</button>
                                <a href = "{{ action('Admin\AdminController@index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

