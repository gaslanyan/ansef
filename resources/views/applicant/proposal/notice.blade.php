@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Create Proposal</div>
                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        @if (\Session::has('delete'))
                            <div class="alert alert-info">
                                <p>@php echo html_entity_decode(\Session::get('delete'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif

                        To add a proposal, you need to first add at least one person in the role of project participant.
                        This person is typically the Principal Investigator (PI) of the proposal.<br/><br/>
                        Go back to the Persons tab, and add a new person in the role of project participant.
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
