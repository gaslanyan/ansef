@extends('layouts.auth')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                 <div class="card" >
                    <div class="card-header">Recommendation Letter </div>

                    <div class="card-body ">
                        @include('partials.status_bar')
                         Thanks you for adding recommendation.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
