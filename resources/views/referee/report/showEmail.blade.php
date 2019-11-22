@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Send Email</div>

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

                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Send Email About {{$title->title}} </h3>
                            </div>
                            <div class="box-body col-md-12">
                                <form method="post"
                                      action="{{ action('Referee\SendEmailController@sendEmail', $p_id->proposal_id) }}"
                                      class="row">
                                    @csrf
{{--                                    <input name="_method" type="hidden" value="PATCH">--}}
                                    <div class="form-group col-lg-12">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label for="public">Comment:</label>
                                                <textarea rows="4" class="form-control" name="comment"
                                                          id="public"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <button type="submit" class="btn btn-primary">
                                            Send Comment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
