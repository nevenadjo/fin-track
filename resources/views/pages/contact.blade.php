@extends('layouts.app')

@section('content')
    <div class="container mt-2 w-75">
        <h2 class="mb-2 text-center heading">Contact Us</h2>
        <p class="mb-2 small text-center primary-text-color">Leave us a message. We'll get to you shortly.</p>

        <form action="{{ route('contact.send') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="firstName" class="form-label">Name</label>
                <input
                    type="text"
                    name="firstName"
                    id="firstName"
                    class="form-control @error('firstName') is-invalid @enderror"
                    value="{{ old('firstName') }}"
                    required
                >
                @error('firstName')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea
                    name="message"
                    rows="5"
                    id="message"
                    class="form-control @error('message') is-invalid @enderror"
                    required
                >{{ old('message') }}</textarea>
                @error('message')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-blue">
                Send message
            </button>
        </form>
    </div>
@endsection
