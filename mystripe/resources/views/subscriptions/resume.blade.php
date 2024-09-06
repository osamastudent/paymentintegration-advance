@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

        
        <form action="{{ route('resume.plan') }}" method="post" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-primary w-100 mx-auto">reusme Plan</button>
        </form>               
                  
                </div>
                </div>



            </div>
            @endsection