@extends('layouts.app')

@section('content')

<div class="card shadow">
    <div class="card-body">
        <h3>Welcome, {{ Auth::user()->name }}</h3>
        <p>You are logged in successfully.</p>
    </div>
</div>

@endsection