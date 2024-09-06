@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white fw-bold">{{ __('Checkout') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
<div class="alert">
    <b>Price:</b>(<span>{{$find->price}}</span>)
    <b>plane:</b>(<span>{{$find->title}}</span>)
</div>
                    <form id="form" action="{{ route('payment') }}" method="post">
                        @csrf

                        <input type="text" name="plan" value="{{$find->id}}"><br>
                        <label for="card-holder-name">Name on card</label>
                        <input type="text" id="card-holder-name" name="name" class="form-control mt-3" required>

                        <label for="card-element" class="mt-3">Card details</label>
                        <div id="card-element" class="mt-2"></div>
                        <div id="card-errors" role="alert"></div>

                        <button  id="card-btn" data-secret="{{ $intent->client_secret }}" class="btn btn-dark float-end mt-3">Pay</button>
                    </form>


                    <!-- {{config('cashier.key')}} -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3"></script>

<script>
    const stripe = Stripe(`{{config('cashier.key')}}`);
    // const stripe = Stripe('{{ config('cashier.key')}}');
    const elements = stripe.elements();
    const cardElement = elements.create('card',{
        hidePostalCode: true 
    });
    cardElement.mount('#card-element');




//     var card = elements.create('card', {
//     hidePostalCode: true // Correct syntax to hide the postal code field
// });
// card.mount('#card-element');

// card.addEventListener('change', function(event) {
//     var displayError = document.getElementById('card-errors');
//     if (event.error) {
//         displayError.textContent = event.error.message;
//     } else {
//         displayError.textContent = '';
//     }
// });




    const form = document.getElementById('form');
    const cardBtn = document.getElementById('card-btn');
    const cardHolderName = document.getElementById('card-holder-name');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        // cardBtn.disabled = true;

        const {
            setupIntent,
            error
        } = await stripe.confirmCardSetup(
            cardBtn.dataset.secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: cardHolderName.value
                    }
                }
            }
        )
        console.log(setupIntent.payment_method); // This should log the Stripe ID

        if (error) {
            cardBtn.disabled = false;
        } else {
            // console.log(setupIntent);
            let token = document.createElement('input');
token.setAttribute('type', 'hidden');
token.setAttribute('name', 'token');
token.setAttribute('value', setupIntent.payment_method);
form.appendChild(token);

            form.submit();
        }
    });
</script>
@endsection