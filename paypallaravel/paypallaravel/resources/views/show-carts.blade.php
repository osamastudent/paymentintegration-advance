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
    <th>Index</th>
    <th>Name</th>
    <th>price</th>
    <th>Quantity</th>
    <th>subotal</th>
</thead>
<tbody>
    @php $total=0;   @endphp
@foreach($showCarts as $showCart)

<tr>
<td>{{$loop->iteration}}</td>
<td>{{$showCart->name}}</td>
<td>{{$showCart->amount}}</td>
<td>{{$showCart->quantity}}</td>
<td>{{$showCart->quantity * $showCart->amount}}</td>
<td class="d-none">{{$total+=$showCart->quantity * $showCart->amount}}</td>
</tr>
@endforeach


<tr>
<td>
    <strong>Total:{{isset($total)? $total : 0}}</strong>
</td>

<td colspan="3" class="text-end">
        <a href="{{route('checkout',['amount'=>$total])}}" class="btn btn-success">Checkout</a>
    </td>
</tr>



</tbody>
    </table>
    

    
    </div>
</body>
</html>