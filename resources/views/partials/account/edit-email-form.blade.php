<p class="small primary-text-color fw-bold">Update your email address to keep everything safe.</p>
<form method="POST" action="/account/update-email" class="text-center">
    @csrf

    @error('email')
    <div class="text-danger text-start w-100 small">{{ $message }}</div>
    @enderror
    <input
        class="form-control mb-3 @error('email') border-danger @enderror"
        name="email"
        value="{{ old('email', auth()->user()->email) }}"
    >
    <button class="btn btn-yellow">Save</button>
</form>
