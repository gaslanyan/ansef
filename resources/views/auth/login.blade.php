@extends('layouts.auth')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                 <div class="card" >
                    <div class="card-header" style="font-size:22px;">ANSEF Portal Login</div>

                    <div class="card-body ">
                        @include('partials.status_bar')
                        <form method="POST" action="{{ route('login.'.$url) }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    {{--                                    <div class="form-check">--}}
                                    {{--                                        <label for="remember" class="label form-check-label"><span class="remember_span">{{ __('Remember Me') }}</span>--}}
                                    {{--                                            <input type="checkbox" class="form-control form-check-input" name="remember"--}}
                                    {{--                                                   id="remember" {{ old('remember') ? 'checked' : '' }}>--}}
                                    {{--                                            <span class="checkmark"></span>--}}
                                    {{--                                        </label>--}}
                                    @if (Route::has('password.request'))
                                        <a class=" remember_a" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                    {{--                                    </div>--}}
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">

                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                    @if(get_role_cookie() !== "superadmin")
                                        <a href="/register/{{ get_role_cookie() }}"
                                           class="btn btn-primary">{{ __('Register')}}</a>
                                    @endif
                                </div>
                            </div>

                            <div class="row col-12" style="font-size:16px;color:#999;margin:20px;">
                                <p>If you have used the ANSEF portal before 2019, you will need to reset
                                    your password to access your account on our new portal. Click on <b>Forgot Your Password?</b>
                                    above to reset your password.
                                </p>
                                <p>If you are new to the ANSEF portal, you will need to register first.
                                    Click on <b>Register</b> above to start the registration process.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

