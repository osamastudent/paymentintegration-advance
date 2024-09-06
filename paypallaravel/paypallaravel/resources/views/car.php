<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>show products</title>
</head>
<body>
    <div class="container mt-5">
    

    <table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @if($cartItems->count())
            @foreach($cartItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ $item->amount }}</td>
                    <td>${{ $item->subtotal }}</td>
                    <td class="d-none">{{ $total += $item['quantity'] * $item['amount'] }}</td>
                    
                </tr>
            @endforeach
            <tr>
                <td colspan="4"><strong>Total: ${{ $total }}</strong></td>
                <td>
                <a href="{{ route('checkout', ['amount' => $total]) }}" class="btn btn-success">Checkout</a>

                </td>
            </tr>
        @else
            <tr>
                <td colspan="5">Your cart is empty.</td>
            </tr>
        @endif
    </tbody>
</table>



    
    </div>
</body>
</html>