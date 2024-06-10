@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/pay.css')}}">
@endsection

@section('content')
<div class="pay-form">
    <h2 class="pay-form__heading">Stripe Checkout</h2>
    <div class="pay-form__inner">
        <input type="number" id="amount" placeholder="Enter amount" class="pay-form__input">
        <button id="checkout-button" class="checkout-button">Checkout</button>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51PPzg5JvM44vjeYX1QhElTKCCS4igdX472GfFKQwn0JK8f0h9KAsnaZTBkTTRNY8Rv2gl0AHvp7B7BnuZ6YCsgv500wWm3KfZM');

    document.getElementById('checkout-button').addEventListener('click', function() {
        var amount = document.getElementById('amount').value;

        if (amount && amount > 0) {
            fetch('/create-checkout-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        amount: amount
                    })
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(session) {
                    return stripe.redirectToCheckout({
                        sessionId: session.id
                    });
                })
                .then(function(result) {
                    if (result.error) {
                        console.error(result.error);
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                });
        } else {
            alert('Please enter a valid amount.');
        }
    });
</script>
@endsection('content')