@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Communicate with Program Officer</div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')


                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Communicate with Program Officer about proposal: {{$tag}} </h3>
                            </div>
                            <div class="box-body col-md-12">
                                <form method="post"
                                      action="{{ action('Referee\SendEmailController@sendEmail', $pid) }}"
                                      class="row">
                                    @csrf
{{--                                    <input name="_method" type="hidden" value="PATCH">--}}
                                    <div class="form-group col-lg-12">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label for="public">Message:</label>
                                                <textarea rows="4" class="form-control" name="comment"
                                                          id="public"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <button type="submit" class="btn btn-primary">
                                            Send Message
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
