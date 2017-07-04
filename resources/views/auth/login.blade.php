@extends('layouts.login')

@section('content')



        <div class="animate form login_form">
          <section class="login_content">
            <form role="form" method="POST" action="{{ route('login') }}">
              <h1>{{ __('messages.loginForm') }}</h1>
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="{{ __('messages.eMailAddress') }}">
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

                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('messages.rememberMe') }}
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-default submit">
                        {{ __('messages.login') }}
                    </button>
                    <a class="reset_pass" href="{{ route('password.request') }}">{{ __('messages.lostYourPassword') }}</a>
                </div>
                <div class="separator">
                    <p class="change_link">{{ __('messages.wantToRegister') }}
                        <a href="{{ route('register') }}" class="to_register"> {{ __('messages.createAnAccount') }} </a>
                    </p>

                    @include('layouts.brand')
                </div>
            </form>
          </section>
        </div>



@endsection
