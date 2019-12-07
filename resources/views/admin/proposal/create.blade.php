@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Create Comment for</div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

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

