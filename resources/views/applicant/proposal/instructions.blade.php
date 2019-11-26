@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Instructions for proposal document
                        <a href="{{ action('Applicant\ProposalController@activeProposal') }}" class="display float-lg-right btn-box-tool"> Go Back</a>
                    </div>
                    <div style="margin:20px;">
                    {!! $competition->instructions !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

