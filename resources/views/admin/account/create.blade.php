@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Create a person<br>
                        <i class="fa fa-info text-blue all"> * {{Lang::get('messages.required_all')}}</i>
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
                        <form method="post" action="{{action('Admin\AccountController@store')}}" class="row">
                            @csrf
                            <div class="col-lg-12 ">
                                <div class="row">
                                    <div class="form-group col-lg-6 emails">
                                        <label for="role">Role *:</label>
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
                                        <label for="email">Emails *:</label>
                                        <i class="fa fa-plus pull-right add  text-blue"></i>
                                        <input type="text" class="form-control email" name="email[]" value="{{old('email[0]')}}"
                                               id="email">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Add Person</button>
                                <a href = "{{ action('Admin\PersonController@index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

