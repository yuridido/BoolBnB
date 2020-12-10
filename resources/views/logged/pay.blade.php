@extends('layouts.app')
@section('content')
<div class="container-center">
  
  <div class="payment-checkout">
    
		<form method="post" id="payment-form" class="payment-checkout-form" action="{{  route('logged.checkout',$apartment->id) }}"">
			@csrf
			@method('post')
        <section>
          <label for="amount">
              <div>
                 <input id="amount" name="amount" type="tel" min="1" placeholder="Amount" value="{{$price}}" style="display:none">
              </div>
          </label>
          <div class="bt-drop-in-wrapper">
             <div id="bt-dropin"></div>
          </div>
        </section>

			<input id="client_token" name="token" type="hidden" value="{{ $token }}"/>
			<input id="nonce" name="payment_method_nonce" type="hidden" />
      <input id="sponsor_plan" name="sponsor_plan" type="hidden" value="{{$id_sponsor}}" />
      
			<div class="pay-button">
        <button class="btn-succes" type="submit">
				  <span>Paga e avvia la sponsorizzazione</span>
			</button>
      </div>
		</form>
	</div>
</div>
        <!-- Load the PayPal Checkout component. -->
<script src="https://js.braintreegateway.com/web/dropin/1.22.1/js/dropin.min.js"></script>
<script src="{{ asset('js/sponsor.js') }}"></script>
@endsection