@extends('layouts.login')

@section('content')




    <div class="animate form login_form">
      <section class="login_content">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
            <h1>{{ __('messages.resetPassword') }}</h1>
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="{{ __('messages.eMailAddress') }}">
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif

            </div>

            <div class="form-group">

                    <button type="submit" class="btn btn-primary">
                        {{ __('messages.sendPasswordResetLink') }}
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
