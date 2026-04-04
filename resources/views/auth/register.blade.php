@extends('layouts.app')

@section('content')
    <div class="register-container d-flex align-items-center justify-content-around">
{{--        <div class="register-image">--}}
{{--            <img src="{{asset('register-image.png')}}" alt="welcome image" />--}}
{{--        </div>--}}
        <div class="register">
            <h2 class="text-center mt-3 heading">Create a new account</h2>
            <form action="{{ route('register') }}" method="POST">
                @csrf

                @error('email')
                <div class="text-danger w-100 small">{{ $message }}</div>
                @enderror
                <label for="email"><i class="fas fa-envelope"></i></label>
                <input id="email" type="text" class="@error('email') border-danger @enderror" name="email" placeholder="Email" value="{{ old('email') }}" required>

                @error('firstName')
                <div class="text-danger w-100 small">{{ $message }}</div>
                @enderror
                <label for="firstName"><i class="fas fa-user"></i></label>
                <input type="text" name="firstName" class="@error('firstName') border-danger @enderror" placeholder="First name" value="{{ old('firstName') }}" id="firstName" required>

                @error('password')
                <div class="text-danger w-100 small">{{ $message }}</div>
                @enderror
                <label for="password"><i class="fas fa-lock"></i></label>
                <input id="password" type="password" class="@error('password') border-danger @enderror" name="password" placeholder="Password" required>

                <label for="password"><i class="fas fa-lock"></i></label>
                <input id="password" type="password" class="@error('password') border-danger @enderror" name="password_confirmation" placeholder="Repeat password" required>

                <a href="{{ route('login') }}">Already have an account? Log in.</a>
                <input type="submit" value="Register">
            </form>
        </div>
    </div>
@endsection

