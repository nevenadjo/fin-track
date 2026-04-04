@extends('layouts.app')

@section('content')
    <div class="register-container d-flex align-items-center justify-content-around">
        <div class="register-image">
            <img src="{{asset('register-image.png')}}" alt="welcome image" />
        </div>
        <div class="register">
            <h2 class="text-center mt-3 heading">Log in</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf

                @error('email')
                <div class="text-danger w-100 small">{{ $message }}</div>
                @enderror
                <label for="email"><i class="fas fa-envelope"></i></label>
                <input class="@error('email') border-danger @enderror" id="email" type="text" name="email" value="{{old('email')}}" placeholder="Email" required>

                <label for="password"><i class="fas fa-lock"></i></label>
                <input id="password" class="@error('email') border-danger @enderror" type="password" name="password" placeholder="Password" required>

                <a href="{{ route('register') }}">Don`t have an account? Create new one.</a>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
@endsection
