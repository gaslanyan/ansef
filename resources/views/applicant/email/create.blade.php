@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header">Update emails for {{$persons_name['first_name']}} {{$persons_name['last_name']}}
                        <a href="{{action('Applicant\AccountController@index')}}"
                           class="display float-lg-right btn-box-tool"> Go Back</a>
                    </div>
                    <div class="card-body" style="overflow:auto;">
                    <div class="card-body" style="overflow:auto;">
                        <p><b>Add New Email</b></p>
                        <form method="post" action="{{action('Applicant\EmailController@store') }}">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group col-lg-10 emails">
                                        <label for="email">Email <span style="color:#777;"></span>:</label>
                                        <input type="text" class="form-control email" name="email[]"
                                               id="email">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" name="email_creare_hidden"
                                       value="{{$id}}"
                                       id="email">
                                <button type="submit" class="btn btn-primary">Add new email</button>
                            </div>
                        </form>
                    </div>
                    @include('partials.status_bar')

                    <hr>
                        @if(!empty($email_list))
                            <form method="post" action="{{action('Applicant\EmailController@update', $id) }}">
                                <div class="form-group">
                                    @csrf
                                    <input name="_method" type="hidden" value="PATCH">

                                    <label for="email"><b>Current emails:</b></label><br/><br/>
                                    @foreach($email_list as $el)
                                        <div class="form-group col-lg-12 emails">
                                            <div class="row">
                                                <input type="text" class="form-control email col-lg-10"
                                                       name="email_list[]"
                                                       value="{{$el['email']}}"
                                                       id="email_list">
                                                <a href="{{action('Applicant\EmailController@destroy', $el['id'])}}"
                                                   class="btn-link col-lg-2">
                                                    <i class="fa fa-trash"></i></a>
                                                <input type="hidden" class="form-control" name="email_list_hidden[]"
                                                       value="{{$el['id']}}"
                                                       id="email">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
