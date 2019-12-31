@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of Persons
                        {{--<a href="{{action('Applicant\PersonController@create')}}"--}}
                           {{--class="display float-lg-right btn-primary px-2">Create Person</a>--}}
                    </div>
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                        </div><br/>
                    @elseif (\Session::has('wrong'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode( \Session::get('wrong'), ENT_HTML5) @endphp</p>
                        </div><br/>
                    @endif


                    <div class="card-body" style="overflow:auto;">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
