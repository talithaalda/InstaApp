@extends('partials.layout')
@section('container')
<div class="container mx-auto max-w-xl pt-10">
    @if(session('success'))
    <div class="alert alert-success max-w-xl">
        {{ session('success') }}
    </div>
    @endif

    @if(session('danger'))
    <div class="alert alert-error max-w-xl">
        {{ session('danger') }}
    </div>
    @endif
    <h1 class="text-center text-3xl font-bold ">Login</h1>
    <form action="{{ route('login') }}" method="POST" class="mt-5 gap-5 flex flex-col">
        @csrf

        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required
            class="input input-bordered w-full" />
        @error('email')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <input type="password" name="password" placeholder="Password" required class="input input-bordered w-full" />
        @error('password')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn btn-primary w-full">Login</button>
        <p class="text-center">Don't have an account? <a href="/register" class="text-blue-500">Register</a></p>
    </form>
    @endsection