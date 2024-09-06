<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- bootstrap cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    

<div class="container">
@if(session('status'))
                        <div class="alert alert-warning">
                            {{ session('status') }}
                        </div>
                    @endif
<div class="row">
    <div class="col-4">
        <div class="card p-3">
            <h5 class="card-title">{{$basic->name}}</h5>
            <div class="card-body">
                <ul>
                    <li>this is basic plan</li>
                    <li>this is basic plan</li>
                    <li>this is basic plan</li>
                    <li>this is basic plan</li>
                    <li>this is basic plan</li>
                    <li>this is basic plan</li>
                </ul>

                <a href="{{route('first.register',$basic->plan_id)}}" class="btn btn-primary w-100">Choose Plan</a>
            </div>
        </div>
    </div>



    <div class="col-4">
        <div class="card p-3">
            <h5 class="card-title">{{$standard->name}}</h5>
            <div class="card-body">
                <ul>
                    <li>this is standard plan</li>
                    <li>this is standard plan</li>
                    <li>this is standard plan</li>
                    <li>this is standard plan</li>
                    <li>this is standard plan</li>
                    <li>this is standard plan</li>
                </ul>

                <a href="{{route('first.register',$standard->plan_id)}}" class="btn btn-primary w-100">Choose Plan</a>
            </div>
        </div>
    </div>

    








    <div class="col-4">
        <div class="card p-3">
            <h5 class="card-title">{{$premium->name}}</h5>
            <div class="card-body">
                <ul>
                    <li>this is premium plan</li>
                    <li>this is premium plan</li>
                    <li>this is premium plan</li>
                    <li>this is premium plan</li>
                    <li>this is premium plan</li>
                    <li>this is premium plan</li>
                </ul>

                <a href="{{route('first.register',$premium->plan_id)}}" class="btn btn-primary w-100">Choose Plan</a>
            </div>
        </div>
    </div>



    <div class="card p-3">
    <h5 class="card-title">Premium</h5>
    <div class="card-body">
        <ul>
            <li>This is a premium plan</li>
            <li>This is a premium plan</li>
            <li>This is a premium plan</li>
            <li>This is a premium plan</li>
            <li>This is a premium plan</li>
            <li>This is a premium plan</li>
        </ul>
        <a href="{{ route('start.free.trial') }}" class="btn btn-primary w-100">Get Free Start for 10 Days</a>
    </div>
</div>





</div>

</div>


</body>
</html>