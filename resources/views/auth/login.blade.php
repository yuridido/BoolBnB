@extends('layouts.app')

@section('content')
<div class="signup_container">
    <div class="signup login">
      <h1>Login</h1>
      <form method="POST" action="{{ route('login') }}"> 
        @csrf

        <!-- email -->
        <div>
          <label for="email">Email</label><br />
          <input id="emailL" type="email" name="email" placeholder="Enter your email"/>
          <p class="message-E">error</p>
        </div>

        <!-- password -->
        <div>
          <label for="password">Password</label><br />
          <div class="password">
            <input id="passwordL" type="password" name="password" placeholder="Enter your password" />
            <p class="message-E">error</p>
          </div>
        </div>

        <!-- Remember -->
        <div>
          <input name="remember" type="checkbox" class="checkbox" />
          <span>
            Remember Me
          </span>
        </div>

       <div>
            <button id="registerL" type="submit" class="signup_btn">{{ __('Login') }}</button>
          @if (Route::has('password.request'))
                {{-- <a class="forgot_password" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a> --}}
            @endif
       </div>
      </form>
    </div>
  </div>
@endsection
