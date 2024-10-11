@extends('partials.layout')
@section('container')
<div class="container mx-auto">
    <h1 class="text-center text-3xl font-bold mt-10">Register</h1>
    <form action="{{ route('register') }}" method="POST" class="mt-5 gap-5 flex flex-col">
        @csrf
        <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" required
            class="input input-bordered w-full" />
        @error('username')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required
            class="input input-bordered w-full" />
        @error('email')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <input type="password" name="password" placeholder="Password" required class="input input-bordered w-full" />
        @error('password')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <input type="password" name="password_confirmation" placeholder="Confirm Password" required
            class="input input-bordered w-full" />
        @error('password_confirmation')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
        <button type="submit" class="btn btn-primary w-full">Register</button>
    </form>
    @endsection