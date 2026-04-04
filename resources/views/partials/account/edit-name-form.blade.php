<p class="small primary-text-color fw-bold">Give your name a little refresh.</p>
<form method="POST" action="/account/update-name" class="text-center">
    @csrf

    @error('name')
    <div class="text-danger text-start w-100 small">{{ $message }}</div>
    @enderror
    <input
        class="form-control mb-3 @error('name') border-danger @enderror"
        name="name"
        value="{{ old('name', auth()->user()->name) }}"
    >
    <button class="btn btn-yellow">Save</button>
</form>
