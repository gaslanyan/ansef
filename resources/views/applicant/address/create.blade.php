@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="offset-md-2 col-md-10">
            <div class="card" style="margin-top:20px;">
                <div class="card-header">Add A New Person</div>

                <div class="card-body card_body">
                    @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                    </div><br />
                    @elseif (\Session::has('wrong'))
                    <div class="alert alert-success">
                        <p>{{ \Session::get('wrong') }}</p>
                    </div><br />
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div><br />
                    @endif

                    {{$person->first_name}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection