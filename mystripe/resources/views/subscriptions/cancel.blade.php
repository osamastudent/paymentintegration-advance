@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
            @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                <div class="card">

                    <div class="card-body">
                    <ul>
                        <li>this is standard </li>
                        <li>this is standard </li>
                        <li>this is standard </li>
                        <li>this is standard </li>
                    </ul>
                    

              
        <form action="{{ route('cancel.plan') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-primary w-100 mx-auto">Cancel Plan</button>
        </form>


                    </div>
                </div>



            </div>
            @endsection