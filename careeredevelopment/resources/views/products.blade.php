<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Stripe Checkout Example - Webappfix</title>
    <!-- bootstrap cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>

<body>

    <div class="container">
        <h1 class="text-center mb-5 mt-5">Laravel Stripe Checkout Example - Webappfix</h1>




        <!-- <div class="dropdown">
            <a href="{{route('show.cart.items')}}" class="btn btn-primary position-relative" class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                cart
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{$showCarts}}
                    <span class="visually-hidden">unread messages</span>
                </span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
        </div> -->

        <a href="{{route('show.cart.items')}}" class="btn btn-primary position-relative">
            cart
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{$showCarts}}
                <span class="visually-hidden">unread messages</span>
            </span>
</a>


        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
            Session::forget('success');
            @endphp
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-warning">
            {{ Session::get('error') }}
            @php
            Session::forget('error');
            @endphp
        </div>
        @endif

        @if(session()->has('session_id'))
    <p>Session ID: {{ session('session_id') }}</p>
@endif

<form action="{{ route('stripe.cancel') }}" method="POST">
    @csrf
    <input type="hidden" name="session_id" value="{{ session('session_id') }}">
    <button type="submit" class="btn btn-danger">Cancel</button>
</form>


        <div class="row">
            @foreach($plans as $plan)
            <div class="col-md-4">
                <div class="card" style="width:18rem;">
                    <img src="https://dummyimage.com/300x200/000/fff" class="card-img-top">
                    <div class="card-body">
                        <h5 id="productId">{{$plan->id}}</h5>
                        <h5 class="card-title" id="name">{{$plan->name}}</h5>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        </p>
                        <h5 class="">{{$plan->price}}</h5>

                        <!-- <a class="addtocartbtn" href="{{ route('stripe.checkout',['price' => $plan->price,'product' => $plan->name]) }}">Add to cart</a> -->
                        <a class="addtocartbtn" data-id="{{$plan->id}}" data-price="{{$plan->price}}" data-product="{{$plan->name}}" href="#">Add to cart</a>

                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".addtocartbtn").on("click", function(event) {
                event.preventDefault();

                // console.log($(this).closest(".card").find(".card-title").text());
                // let name=$(this).closest(".card").find(".card-title").text();
                var productId = $(this).data("id");
                var name = $(this).data("product");
                var price = $(this).data("price");

                console.log(price + " " + name);
                // console.log("name here===>", name);
                $.ajax({
                    url: "{{route('addtocart.ajax.store')}}",
                    type: "POST",
                    data: {
                        id: productId,
                        name: name,
                        price: price,
                        contentType: false,
                        processData: false,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.data) {
                            console.log(response.data.name);
                            console.log(response.data.price);
                            console.log(response.data.user_id);
                            console.log(response.data.status);
                            console.log(response.data.quantity);
                            console.log(response.data.plan_id);
                            localStorage.setItem("showcart", "true");
                            location.reload();

                        }
                    },
                    error: function(response) {
                        console.log(response);

                    },

                });


                // show cart items
                $.ajax({
                    url: "{{route('show.Cart')}}",
                    type: "GET",

                    success: function(response) {
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