@extends('layouts.app')

@section('content')


<style>
    /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h5>Your Subscriptions</h5>

            @if(count($subscriptions) > 0)
            <table class="table table-hover">
                <tr>
                    <thead>
                        <th>Index</th>
                        <th>Plan Name</th>
                        <th>Subs Type</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Trial Start At</th>
                        <th>Auto renew</th>
                    </thead>
                </tr>

                @foreach($subscriptions as $subscription)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$subscription->plan->name}}</td>
                    <td>{{$subscription->type}}</td>
                    <td>{{$subscription->plan->price}}</td>
                    <td>{{$subscription->quantity}}</td>
                    <td>{{$subscription->created_at}}</td>
                    <td>
<!-- Rounded switch -->
<label class="switch">
  @if($subscription->ends_at == null)
    <input type="checkbox" id="mycheckbox" checked value="{{$subscription->type}}">
  @else
    <input type="checkbox" id="mycheckbox" value="{{$subscription->type}}">
  @endif
  <span class="slider round"></span>
</label>

                    </td>
                </tr>
                @endforeach
            </table>
            @else
            <div class="alert bg-success">
                You are not subscribed to any plan
            </div>
            @endif
        </div>
    </div>


</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("#mycheckbox").on('click', function(){
            let subscriptionName = $(this).val();
            if (this.checked) {
                console.log("Checked: " + subscriptionName);
               
                $.ajax({
                    url: "{{ route('subscription.cancel') }}",
                    data: { subscriptionName: subscriptionName },
                    type: "GET",
                    success: function(response) {
                        console.log(response); 
                    },
                    error: function(response) {
                        console.log("Error:", response);
                    }
                });
               
               

            } else {
                console.log("Not checked.");
                $.ajax({
                    url: "{{ route('subscription.resume') }}",
                    data: { subscriptionName: subscriptionName },
                    type: "GET",
                    success: function(response) {
                        console.log(response);
                        
                    },
                    error: function(response) {
                        console.log("Error:", response);
                    }
                });

            }
        });
    });
</script>



@endsection