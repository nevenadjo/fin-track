<p class="small primary-text-color fw-bold">Choose a new password to keep your account safe and sound.</p>
<form method="POST" action="/account/update-password" class="text-center">
    @csrf

    @error('current_password')
    <div class="text-danger text-start w-100 small">{{ $message }}</div>
    @enderror
    <input type="password" name="current_password" class="form-control mb-2 @error('current_password') border-danger @enderror" placeholder="Current password">

    @error('password')
    <div class="text-danger text-start w-100 small">{{ $message }}</div>
    @enderror
    <input type="password" name="password" class="form-control mb-2 @error('password') border-danger @enderror" placeholder="New password">
    <input type="password" name="password_confirmation" class="form-control mb-3 @error('password') border-danger @enderror" placeholder="Confirm password">

    <button class="btn btn-yellow">Save</button>
</form>
