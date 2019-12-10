@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">Person details
                    </div>

                    @include('partials.person',
                    [
                        'person' => $person,
                        'emails' => $emails,
                        'addresses' => $addresses,
                        'institutions' => $institutions,
                        'degrees' => $degrees,
                        'honors' => $honors,
                        'books' => $books,
                        'meetings' => $meetings,
                        'publications' => $publications
                    ])

                    <div class="col-lg-12" style="margin-top:30px;">
                        <a href="{{action('Applicant\PersonController@download', $person->id)}}" class="btn btn-primary">Download</a>
                        <a href="{{action('Applicant\PersonController@index') }}" class="btn btn-secondary">Go Back</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            });
    </script>
@endsection
