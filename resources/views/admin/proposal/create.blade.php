@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Create Comment for</div>

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
                        <form method="post" action="{{ action('Admin\ProposalController@update',\Illuminate\Support\Facades\Session::get('p_id')) }}" class="row">
                            @csrf
                            <div class="form-group">
                                <input name="_method" type="hidden" value="PATCH">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="title">{{$title->title}}:</label>
                            </div>

                            <div class="form-group col-lg-12">
                                <label for="comments">Proposal Comment:</label>
                                <textarea rows="4" class="form-control" name="comment" id="comments"></textarea>
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Add Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

