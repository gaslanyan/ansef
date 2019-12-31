@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Dashboard</div>

                    <div class="card-body" style="overflow:auto;">
                        {{ Auth::guard('admin')->user() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
