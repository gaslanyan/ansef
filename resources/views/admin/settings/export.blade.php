@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Download table content as csv or excel

                    <div class="card-body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> </i>&nbsp;<i class="text-blue">{{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\SettingsController@export') }}">
                            <div class="form-group">
                                @csrf
                                {{--<input name="_method" type="hidden" value="PATCH">--}}
                                @php
                                    $tables = getTableNames();
                                @endphp
                                <label for="name">Table Name *:</label>
                                <select class="form-control" name="name" id="name">
                                    <option value="">Select table</option>
                                    @foreach($tables as $table)
                                        <option value="{{$table}}" @if(old('name') == $table){{'selected'}} @endif>{{$table}}</option>
                                    @endforeach
                                </select>
                                <label for="type">Select by *:</label>
                                <select class="form-control" name="type" id="type">
                                    <option value="excel" @if(old('type') == 'excel'){{'selected'}} @endif>Excel</option>
                                    <option value="csv" @if(old('type') == 'csv'){{'selected'}} @endif>CSV</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Export</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
