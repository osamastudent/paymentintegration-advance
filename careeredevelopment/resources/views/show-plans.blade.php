@extends('layouts.app')

@section('content')


<div class="container">
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

                <a href="{{route('checkout',$basic->plan_id)}}" class="btn btn-primary w-100">Choose Plan</a>
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

                <a href="{{route('checkout',$standard->plan_id)}}" class="btn btn-primary w-100">Choose Plan</a>
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

                <a href="{{route('checkout',$premium->plan_id)}}" class="btn btn-primary w-100">Choose Plan</a>
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
  @endsection
