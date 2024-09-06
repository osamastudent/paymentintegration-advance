<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>showcart</title>
    <!-- bootstrap cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>

<body>
    <div class="container mt-5">
        <a href="#" onclick="goBack()" id="goBackButton">Go Back</a>
        @if(Session::has('status'))
        <div class="alert alert-status">
            {{ Session::get('status') }}
            @php
            Session::forget('status');
            @endphp
        </div>
        @endif
        <table class="table">
            <tr>
                <thead>
                    <th>Index</th>
                    <th>Name</th>
                    <th>price</th>
                    <th>quantity</th>
                    <th>subtotal</th>
                </thead>
            </tr>
            <tbody>
                @php $total = 0; @endphp
                @php $total = 0; @endphp
                @foreach($showCarts as $showCart)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $showCart->status }}</td>
                    <td>{{ $showCart->price }}</td>
                    <td class="w-25">
                        <input type="number" data-id="{{ $showCart->id }}" data-price="{{ $showCart->price }}" data-status="{{ $showCart->status }}" value="{{ $showCart->quantity }}" min="1" class="w-75 form-control updateCart">
                    </td>
                    <td>{{ $showCart->quantity * $showCart->price }}</td>
                    <td class="d-none">{{ $total += $showCart->quantity * $showCart->price }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="text-end" colspan="4">
                        <h5>Total {{ $total }}</h5>
                    </td>
                </tr>
                <tr>
                    <td class="text-end" colspan="5">
                        <a href="#" class="btn btn btn-success">Continue Shopping</a>
                        <a href="{{ route('stripe.checkout', ['price' => $total,'product' => 'gold']) }}">Checkout</a>
                    </td>
                </tr>
            </tbody>

        </table>

    </div>




    <script>
        $(document).ready(function() {
            $("#goBackButton").click(function() {
                window.history.back();
            });

            // update Cart
            $(".updateCart").on("change", function(event) {
                event.preventDefault();
                // console.log($(this).val());
                // console.log($(this).data('status'));
                let id = $(this).data('id');
                let quantity = $(this).val();
                let price = $(this).data('price');
                // let amount = parseInt(quantity) * parseInt(price);
                $.ajax({
                    url: "{{route('update.cart.items')}}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        quantity: quantity,
                        price: price,
                    },
                    success: function(response) {
                        // console.log("osama success", response.data);
                        // console.log("osama id", response.id);
                        location.reload();
                    },
                    error: function(response) {
                        console.log(response);
                    },
                });

            });


        });
    </script>
</body>

</html>