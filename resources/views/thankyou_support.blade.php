@extends('layouts.auth')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Recommendation Letter </div>

                    <div class="card-body ">
                        @if (session('status'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                        @endif
                         Thanks you for adding recommendation.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
