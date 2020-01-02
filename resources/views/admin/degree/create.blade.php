@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Add a degree
                    </div>
                    <div class="card-body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> </i>&nbsp;<i class="text-blue">{{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\DegreeController@store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="degree">Degree name *:</label>
                                @php
                                    $old =  old('text');
                                @endphp
                                <textarea id="degree"  class="form-control" name="text">@if(isset($old)){{$old}} @endif</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href = "{{ action('Admin\DegreeController@index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
