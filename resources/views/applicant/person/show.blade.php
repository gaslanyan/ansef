@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Person details
                    <a href="{{URL::previous()}}" class="display float-lg-right btn-box-tool">Go Back</a>
                </div>

                <div class=card-body>
                    @include('partials.person',
                    [
                    'person' => $person,
                    'phones' => $phonenums,
                    'emails' => $emails,
                    'addresses' => $addresses,
                    'institutions' => $institutions,
                    'degrees' => $degrees,
                    'honors' => $honors,
                    'books' => $books,
                    'meetings' => $meetings,
                    'publications' => $publications,
                    'showdownloads' => true
                    ])

                    <div class="col-lg-12" style="margin-top:30px;">
                        <a href="{{action('Applicant\PersonController@download', $person->id)}}"
                            class="btn btn-primary">Download</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {});

</script>
@endsection
