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
    <h1><a href="{{route('show.carts')}}">cart</a></h1>
    <div class="row">
    @foreach($showProducts as $showProduct)
        <div class="col-4 mt-3">
            <div class="card p-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $showProduct->name }}</h5>
                    <p>{{ $showProduct->description }}</p>
                    <h6>${{ $showProduct->price }}</h6>
                </div>
                <form action="{{ route('addToCart') }}" method="POST">
                    @csrf
                    <input type="hidden" name="name" value="{{ $showProduct->name }}">
                    <input type="hidden" name="amount" value="{{ $showProduct->price }}">
                    <input type="hidden" name="product_id" value="{{ $showProduct->id }}">
                    <input type="submit" class="btn btn-primary" value="Add to Cart">
                </form>
            </div>
        </div>
    @endforeach
</div>

    </div>
</body>
</html>