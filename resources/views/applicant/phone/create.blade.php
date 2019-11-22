@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                <div class="card" >
                    <div class="card-header">Update phone numbers for {{$persons_name['first_name']}} {{$persons_name['last_name']}}
                        <a href="{{ action('Applicant\InfoController@index') }}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>

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
                        @if(!empty($phone_list))
                            <form method="post" action="{{ action('Applicant\PhoneController@update', $id) }}">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                <div class="col-lg-12 ">
                                    <div class="row">
                                        <label for="email"><b>Current phone numbers:</b></label>
                                        @foreach($phone_list as $phl)
                                            <div class="form-group col-lg-12 phones">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <input type="text" class="form-control col-lg-2"
                                                               name="country_code[]"
                                                               value="{{$phl['country_code']}}"
                                                               id="code">
                                                        <input type="text" class="form-control phone col-lg-8"
                                                               name="phone_list[]" value="{{$phl['number']}}"
                                                               id="phone">
                                                        <a href="{{action('Applicant\PhoneController@destroy', $phl['id'])}}"
                                                           class="btn-link col-lg-2">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        <input type="hidden" class="form-control"
                                                               name="phone_list_hidden[]"
                                                               value="{{$phl['id']}}"
                                                               id="email">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="form-group col-lg-12">
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                    <hr>
                    <div class="card-body card_body">
                        <p><b>Add New Phone Number</b></p>
                        <form method="post" action="{{ action('Applicant\PhoneController@store') }}">
                            @csrf
                            <div class="col-lg-12 ">
                                <div class="row">
                                    <div class="form-group col-lg-12 phones">
                                        <label for="phone">Phones:</label>
                                        <!-- <i class="fa fa-plus col-lg-10 col-lg-pull-2 add text-blue" style="cursor: pointer"></i> -->
                                        <div class="col-12">
                                            <div class="row">
                                                <input type="text" name="country_code[]"
                                                       class="form-control col-lg-2">

                                                <input type="text" class="form-control phone col-lg-8"
                                                       name="phone[]"
                                                       id="phone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <input type="hidden" class="form-control"
                                               name="phone_create_hidden"
                                               value="{{$id}}"
                                               id="email">
                                        <button type="submit" class="btn btn-primary">Add Phone Number(s)</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
