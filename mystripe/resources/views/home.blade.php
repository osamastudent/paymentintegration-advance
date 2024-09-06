@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <a href="{{route('plans')}}">Plans</a>
                    <a href="{{route('cancel')}}">cancel</a>
                    <a href="{{route('resume')}}">resume</a>


                    <br><br><br>
                    
                    @if(auth()->user()->subscribed())

                    @if(!auth()->user()->subscription('default')->canceled())
                    <a href="{{route('cancel')}}">cancel</a>
@endif
@endif

                    @if(auth()->user()->subscribed())

                    @if(auth()->user()->subscription('default')->canceled())
                    <a href="{{route('resume')}}">resume</a>
@endif
@endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
