@extends('layouts.app')

@section('content')
<style>
.loader {
  width: fit-content;
  font-weight: bold;
  font-family: monospace;
  font-size: 30px;
  clip-path: inset(0 100% 0 0);
  animation: l5 2s steps(11) infinite;
}
.loader:before {
  content:"Loading..."
}
@keyframes l5 {to{clip-path: inset(0 -1ch 0 0)}}
/* stripe css */
    .StripeElement {
        background-color: white;
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid transparent;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }
    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }
    .StripeElement--invalid {
        border-color: #fa755a;
    }
    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }

</style>
<div class="container">
<div id="loader" class="d-flex justify-content-center d-none align-items-center min-vh-100">
        <div class="loader"></div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    
                    <span>Your subscription is:</span> (<b>{{$plans->name}}</b>)
                    <span class="float-end">${{$plans->price}}</span>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    
                    <form action="{{route('process.plan')}}" method="POST" id="subscribe-form">
                    <input type="hidden" name="plan_id" value="{{ $plans->plan_id }}">
                    @csrf        
    <div class="form-group">
        
    </div>
    <!-- <label for="amount">Amount</label>
    <input id="amount" name="amount" type="number" value="" class="form-control mt-1"> -->
    <label for="card-holder-name" class="mt-3">Card Holder Name</label>
    <input id="card-holder-name" type="text" class="form-control mt-1">
    
    <div class="form-row mt-3">
        <label for="card-element">Credit or debit card</label>
        <div id="card-element" class="form-control">
        </div>
        <!-- Used to display form errors. -->
        <div id="card-errors" role="alert"></div>
    </div>
    <div class="stripe-errors"></div>
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        {{ $error }}<br>
        @endforeach
    </div>
    @endif
    <div class="form-group  mt-3">
        <button  id="card-button" data-secret="{{ $intent->client_secret }}" class="btn  btn-success btn-block">Process Subscription</button>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('myscripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe("{{ env('STRIPE_KEY') }}");
    var elements = stripe.elements();
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
    var card = elements.create('card', {hidePostalCode: true,
        style: style});
    card.mount('#card-element');
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const loader = document.getElementById('loader');
    const clientSecret = cardButton.dataset.secret;
    cardButton.addEventListener('click', async (e) => {
        e.preventDefault();
        loader.style.display="block";
        console.log("attempting");
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: { name: cardHolderName.value }
                }
            }
            );
        if (error) {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
        } else {
            paymentMethodHandler(setupIntent.payment_method);
        }
    });
    function paymentMethodHandler(payment_method) {
        var form = document.getElementById('subscribe-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'payment_method');
        hiddenInput.setAttribute('value', payment_method);
        form.appendChild(hiddenInput);
        form.submit();
    const loader = document.getElementById('loader');
loader.classList.remove('d-none');
    }
</script>

@endsection
