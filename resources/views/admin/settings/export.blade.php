@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Download table content as csv or excel
                        <br>
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i></div>

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
                        @if (\Session::has('delete'))
                            <div class="alert alert-info">
                                <p>@php echo html_entity_decode(\Session::get('delete'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
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
