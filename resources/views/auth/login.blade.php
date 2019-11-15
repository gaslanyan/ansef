@extends('layouts.auth')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body ">
                        @if (session('status'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                        @endif
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
                                    @if(get_Cookie() !== "superadmin")
                                        <a href="/register/{{ get_Cookie() }}"
                                           class="btn btn-primary">{{ __('Register')}}</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

