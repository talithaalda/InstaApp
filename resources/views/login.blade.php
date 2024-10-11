@extends('partials.layout')
@section('container')
 <div class="container mx-auto">
    <h1 class="text-center text-3xl font-bold mt-10">Login</h1>
    <form action="{{ route('login') }}" method="POST" class="mt-5 gap-5 flex flex-col">
        @csrf

        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required class="input input-bordered w-full" />
        @error('email')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <input type="password" name="password" placeholder="Password" required class="input input-bordered w-full" />
        @error('password')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn btn-primary w-full">Login</button>
    </form>
@endsection
