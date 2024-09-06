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
    <h1>upgrade Plans</h1>
<h1>upgradeSubscription</h1>

    <a href="{{ route('cancelSubscription', ['subscriptionId' => 'I-0W13V1C32LNS']) }}" class="btn btn-danger">Activate Subscription</a>
    <form action="{{ route('upgradedPlan') }}" method="POST">
    @csrf
    <!-- old Subscription ID -->
    <input type="text" name="subscription_id" placeholder="Subscription ID" value="I-EK7N39HPPJ67">

    <!-- New Plan ID -->
    <input type="text" name="new_plan_id" placeholder="New Plan ID" value="P-77E543539M308535GM3EGQ2A">

    <button type="submit" class="btn btn-primary">Upgrade</button>
</form>



    <br>

  </div>
    
</body>
</html>