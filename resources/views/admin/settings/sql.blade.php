@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">SQL query for backup
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\SettingsController@backup') }}">
                            <div class="form-group">
                                @csrf
                                {{--<input name="_method" type="hidden" value="PATCH">--}}
                                @php
                                $old = old('sql');
                                        @endphp
                                <label for="sql" class="form-group col-lg-12">Create SQL query *:</label>
                                <textarea id="sql" name="sql" class="form-group col-lg-12" rows="6">@if(isset($old)){{$old}}@endif</textarea>

                            </div>
                            <button type="submit" class="btn btn-primary">Export</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
