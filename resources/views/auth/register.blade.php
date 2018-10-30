@extends('layouts.login')

@section('content')



        <div class="animate form login_form">
          <section class="login_content">
            <form role="form" method="POST" action="{{ route('register') }}">
              <h1>{{ __('messages.createAccount') }}</h1>
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">

                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="{{ __('messages.name') }}">

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif

                </div>

                <div class="form-group{{ $errors->has('lastName') ? ' has-error' : '' }}">

                        <input id="lastName" type="text" class="form-control" name="lastName" value="{{ old('lastName') }}" required autofocus placeholder="{{ __('messages.lastName') }}">

                        @if ($errors->has('lastName'))
                            <span class="help-block">
                                <strong>{{ $errors->first('lastName') }}</strong>
                            </span>
                        @endif

                </div>

                <div class="form-group{{ $errors->has('company') ? ' has-error' : '' }}">

                        <input id="company" type="text" class="form-control" name="company" value="{{ old('company') }}" autofocus placeholder="{{ __('messages.company') }}">

                        @if ($errors->has('company'))
                            <span class="help-block">
                                <strong>{{ $errors->first('company') }}</strong>
                            </span>
                        @endif

                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="{{ __('messages.eMailAddress') }}">

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
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="{{ __('messages.confirmPassword') }}">
                </div>
                {!! NoCaptcha::renderJs() !!}
                <div class="form-group" align="center">
                {!! NoCaptcha::display() !!}
                @if ($errors->has('g-recaptcha-response'))
                    <span class="help-block">
                        <strong><font color="red">{{ $errors->first('g-recaptcha-response') }}</font></strong>
                    </span>
                @endif
                </div>
                <div class="form-group">

                        <button type="submit" class="btn btn-default submit">
                            {{ __('messages.register') }}
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
