@extends('layout')

@section('title', 'Welcome')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1>Welcome to OneSchool</h1>
            <p>Your journey to knowledge begins here.</p>
            <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
        </div>
    </div>
</div>
@endsection
