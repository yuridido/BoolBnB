@extends('layouts.app')

@section('content')
<div class="signup_container">
    <div class="signup">
      <p class="register-title">Sign Up for an Account</p>
      <form method="POST" id="creazione" action="{{ route('register') }}" > 
        @csrf
        <div class="name_inputs">
          <!-- first name -->
          <div class="input-row">
            <label for="name">First Name</label><br />
            <input id="firstnameR" type="text" name="name" placeholder="Your first name" class="fname" />
            <p class="message-E">error</p>
          </div>

          <!-- last name -->
          <div>
            <label for="lastname">Last Name</label><br />
            <input id="lastnameR" type="text" name="lastname" placeholder="Your last name" class="lname" />
            <p class="message-E">error</p>
          </div>
        </div>

        <!-- email -->
        <div>
          <label for="email">Email</label><br />
          <input id="emailR" type="email" name="email" placeholder="Enter your email" />
          <p class="message-E">error</p>
        </div>

        <!-- password -->
        <div>
          <label for="password">Password</label><br />
          <div class="password">
            <input id="passwordR" type="password" name="password" placeholder="Enter your password" />
            <p class="message-E">error</p>
          </div>
        </div>

        <div>
          <label for="password-confirm">Confirm Password</label><br />
          <div class="password">
            <input id="password-confirmR" type="password" name="password_confirmation" placeholder="Confirm your password" />
            <p class="message-E">error</p>
          </div>
        </div>

        <!-- Date of birth -->
        <div class="date">
          <label for="birth">Date Of Birth</label><br />
          <div class="password">
            <input id="dateR" type="date" name="date_of_birth" placeholder="Confirm your password" />
            <p class="message-E">error</p>
          </div>
        </div>
        <button id="registerR" type="submit" class="signup_btn">{{ __('Register') }}</button>
      </form>

      
      
    </div>
  </div>
@endsection
