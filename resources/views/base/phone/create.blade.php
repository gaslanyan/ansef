@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                <div class="card">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                        </div><br />
                    @elseif (\Session::has('wrong'))
                        <div class="alert alert-success">
                            <p>{{ \Session::get('wrong') }}</p>
                        </div><br/>
                    @endif
                    <div class="card-header">Create Emails</div>

                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                        @endif
                        <form method="post" action="{{ action('Base\PhoneController@store') }}">
                            @csrf
                            <div class="col-lg-12 ">
                                <div class="row">
                                    <div class="form-group">

                                        <div class="form-group col-lg-12 phones">
                                            <label for="phone">Phones:</label>
                                            <i class="fa fa-plus pull-right add text-blue"
                                               style="cursor: pointer"></i>
                                            <div class="col-12">
                                                <div class="row">
                                                    <input type="text" name="country_code[]"
                                                           class="form-control col-lg-2">

                                                    <input type="text" class="form-control phone col-lg-10"
                                                           name="phone[]"
                                                           id="phone">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Phone</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
