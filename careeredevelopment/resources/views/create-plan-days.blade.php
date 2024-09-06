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


                    <form action="{{ route('store.plan.day') }}" method="POST">
                        @csrf
                        <label for="name">Plan Name</label>
                        <input name="name" type="text" class="form-control mt-1" required>

                        <label for="amount" class="mt-3">Amount</label>
                        <input name="amount" type="number" class="form-control mt-1" required>

                        <label for="currency" class="mt-3">Currency</label>
                        <input name="currency" type="text" class="form-control mt-1" required>
                        <div class="div" id="intervalCountNoneWhenSelect">
                            <label for="interval_count" class="mt-3">Interval Count</label>
                            <input name="interval_count" type="number" class="form-control mt-1">
                        </div>

                        <label for="billing_method" class="mt-3">Billing Period</label>
                        <select name="billing_method" class="form-select" aria-label="Default select example" required>
                            <option value="" selected>Open this select menu</option>
                            <option value="week">Weekly</option>
                            <option value="month">Monthly</option>
                            <option value="year">Yearly</option>
                            <option value="custom">Custom Duration</option>
                        </select>

                        <div id="custom-duration-container" class="mt-3" style="display: none;">
                            <label for="custom_duration">Custom Duration (days)</label>
                            <input name="custom_duration" type="number" class="form-control mt-1">
                        </div>

                        <input type="submit" class="btn btn-primary mt-3" value="Create Plan">
                    </form>

                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            $('select[name="billing_method"]').on('change', function() {
                                if ($(this).val() === 'custom') {
                                    $('#custom-duration-container').show();
                                    $("#intervalCountNoneWhenSelect").hide();
                                } else {
                                    $('#custom-duration-container').hide();
                                    $("#intervalCountNoneWhenSelect").show();

                                }
                            });
                        });


                        // document.querySelector('select[name="billing_method"]').addEventListener('change', function() {
                        //     if (this.value === 'custom') {
                        //         document.getElementById('custom-duration-container').style.display = 'block';
                        //     } else {
                        //         document.getElementById('custom-duration-container').style.display = 'none';
                        //     }
                        // });
                    </script>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection