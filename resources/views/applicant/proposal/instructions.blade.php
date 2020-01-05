@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Instructions for proposal document
                    <a href="{{URL::previous()}}" class="display float-lg-right btn-box-tool"> Go Back</a>
                </div>
                <div style="margin:20px;">
                    {!! $competition->instructions !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
