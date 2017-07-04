@extends('layouts.login')

@section('content')

        <div class="animate form login_form">
          <section class="login_content">
            @if (session('status'))
            <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
                <h1>{{ __('messages.resetPassword') }}</h1>
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                        <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus placeholder="{{ __('messages.eMailAddress') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                        <input id="password" type="password" class="form-control" name="password" required placeholder="{{ __('messages.password') }}">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif

                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="{{ __('messages.confirmPassword') }}">

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif

                </div>

                <div class="form-group">

                        <button type="submit" class="btn btn-primary">
                            {{ __('messages.resetPassword') }}
                        </button>

                </div>
                <div class="clearfix"></div>

                <div class="separator">
                    <p class="change_link">{{ __('messages.wantToLogin') }}
                        <a href="{{ route('login') }}" class="to_register"> {{ __('messages.login') }} </a>
                    </p>
                    @include('layouts.brand')
                </div>
            </form>
          </section>
        </div>

@endsection
