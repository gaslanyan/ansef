@extends('layouts.auth')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                 <div class="card" >
                    <div class="card-header"> {{ isset($url) ? ucwords($url) : ""}} {{ __('Register') }}</div>

                    <div class="card-body ">
                        @include('partials.status_bar')
                        @isset($url)
                            <form method="POST" action='{{ url("register/$url") }}' aria-label="{{ __('Register') }}">
                                @else
                                    <form method="POST" action="{{ route('register') }}"
                                          aria-label="{{ __('Register') }}">
                                        @endisset
                                        @csrf

                                        <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                            <div class="col-md-6">
                                                <input id="email" type="email" class="form-control" name="email"
                                                       value="{{ old('email') }}" required>

                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="password" class="col-md-4 control-label">Password</label>

                                            <div class="col-md-6">
                                                <input id="password" type="password" class="form-control"
                                                       name="password"
                                                       required>

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <label for="password-confirm" class="col-md-4 control-label">Confirm
                                                Password</label>

                                            <div class="col-md-6">
                                                <input id="password-confirm" type="password" class="form-control"
                                                       name="password_confirmation"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6 col-md-offset-4">
                                                <button type="submit" class="btn btn-primary">
                                                    Register
                                                </button>
                                            </div>
                                        </div>
                                        {!! Recaptcha::render() !!}
                                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
