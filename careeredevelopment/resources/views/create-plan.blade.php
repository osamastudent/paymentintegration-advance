@extends('layouts.app')

@section('content')
<style>
    .StripeElement {
        background-color: white;
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid transparent;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Plan') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif


                    <form action="{{route('store.plan')}}" method="POST">
                        @csrf
                        <label for="">Plan Name</label>
                        <input name="name" type="text" class="form-control mt-1">

                        <label for="" class="mt-3">Amount</label>
                        <input name="amount" type="number" class="form-control mt-1">

                        <label for="" class="mt-3">Curerncy</label>
                        <input name="currency" type="text" class="form-control mt-1">

                        <label for="" class="mt-3">Interval Count</label>
                        <input name="interval_count" type="number" class="form-control mt-1">


                        <label for="" class="mt-3">Billing Period</label>

                        <select name="billing_method" class="form-select" aria-label="Default select example">
                            <option value="" selected>Open this select menu</option>
                            <option value="week">weekly</option>
                            <option value="month">monthly</option>
                            <option value="year">yearly</option>
                        </select>

                        <input type="submit" class="btn btn-primary mt-3" value="Create Plan">
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection