@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
            <!-- @foreach($plans as $plan)
                        <a class="d-block text-dark text-decoration-none" href="{{route('checkout',['plan'=>$plan->slug])}}">{{$plan->title}}</a>
                    
                    @endforeach -->
                <div class="card-body">
                    <h5 class="card-title">Basic</h5>
                    <ul>
                        <li>this is basic </li>
                        <li>this is basic </li>
                        <li>this is basic </li>
                        <li>this is basic </li>
                    </ul>
                    <button class="btn btn-primary w-100 mx-auto">Choose plan</button>
                </div>
                
            </div>
            </div>
            

<!-- plans.blade.php -->
@foreach($plans as $plan)
    <div>
        <h3>{{ $plan->title }}</h3>
        <p>{{ $plan->description }}</p>
        <p>{{ $plan->price }}</p>
        <p>trial days{{ $plan->trial_dayspa }}</p>
        <form action="{{ route('subscriptions.startTrial') }}" method="POST">
            @csrf
            <input type="text" name="plan_id" value="{{ $plan->id }}">
            <button type="submit">Start Trial</button>
        </form>
    </div>
@endforeach


             @foreach($plans as $plan)
            
            <div class="col-md-4">
                <div class="card">

                    <div class="card-body">
                    <h5 class="card-title">{{$plan->title}}</h5>
                    <h5 class="card-title">${{number_format($plan->price,2)}}</h5>
                    <ul>
                        <li>this is standard </li>
                        <li>this is standard </li>
                        <li>this is standard </li>
                        <li>this is standard </li>
                    </ul>
                    <form action="{{route('checkout')}}" method="get">
                        @csrf
                        <input type="text" name="stripe_id" value="{{$plan->stripe_id}}" class="form-control">
                    <button type="submit" class="btn btn-primary w-100 mx-auto">Choose plan</button>

                    </form>
                    </div>
                </div>
                </div>



                @endforeach
            </div>
            @endsection