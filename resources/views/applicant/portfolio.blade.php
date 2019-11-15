@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body card_body">
                        {{ Auth::guard('admin')->user() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
