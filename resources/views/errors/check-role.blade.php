@extends('layouts.auth')


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                 <div class="card" >
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        <h3>You don't have permission to access this page. <br/> Please contact {{config('emails.webmaster')}}.</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
