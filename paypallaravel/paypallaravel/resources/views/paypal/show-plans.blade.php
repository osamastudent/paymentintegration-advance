<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>show-plans</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>

<body>
  @if(session('status'))
  {{session('status')}}
  @endif
  @if(session('success'))
  {{session('success')}}
  @endif
  @if(session('error'))
  {{session('error')}}
  @endif
  <div class="container mt-5">
    <h1>Show Plans</h1>

    <div class="row">
<div class="col-4">
<a href="{{ route('cancelSubscription', ['subscriptionId' => 'I-0W13V1C32LNS']) }}" class="btn btn-danger">Cancel Subscription</a>


</div>
      <div class="col">
        <div class="card p-2">
          <h5 class="car-title">Oppo</h5>
          <div class="card-body">
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Illo est totam a aliquam voluptatum quas reiciendis similique vitae dicta quod aliquid odio veritatis nam delectus, eum alias. Autem, odit natus.
            </p>
          </div>
          <a href="{{route('processTransactionPlan',['planId'=>'P-77E543539M308535GM3EGQ2A'])}}" class="btn btn-primary">Choose Plan</a>
        </div>
      </div>


      <div class="col">
        <div class="card p-2">
          <h5 class="car-title">Gold</h5>
          <div class="card-body">
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Illo est totam a aliquam voluptatum quas reiciendis similique vitae dicta quod aliquid odio veritatis nam delectus, eum alias. Autem, odit natus.
            </p>
          </div>
          <a href="{{route('processTransactionPlan',['planId'=>'P-4BV53907MN174204UM24JINY'])}}" class="btn btn-primary">Choose Plan</a>
        </div>
      </div>
      


      <div class="col">
        <div class="card p-2">
          <h5 class="car-title">Standard</h5>
          <div class="card-body">
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Illo est totam a aliquam voluptatum quas reiciendis similique vitae dicta quod aliquid odio veritatis nam delectus, eum alias. Autem, odit natus.
            </p>
          </div>
          <a href="{{route('processTransactionPlan',['planId'=>'P-7MA30801DR7862022M2VDSZI'])}}" class="btn btn-primary">Choose Plan</a>
        </div>
      </div>


      <div class="col">
        <div class="card p-2">
          <h5 class="car-title">Basic</h5>
          <div class="card-body">
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Illo est totam a aliquam voluptatum quas reiciendis similique vitae dicta quod aliquid odio veritatis nam delectus, eum alias. Autem, odit natus.
            </p>
          </div>
          <a href="{{route('processTransactionPlan',['planId'=>'P-2UC159430U368023VM2AUTZI'])}}" class="btn btn-primary">Choose Plan</a>

        </div>
      </div>




    </div>
  </div>
</body>

</html>