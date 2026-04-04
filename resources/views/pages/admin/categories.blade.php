@extends('layouts.admin')

@section('content')
    <h4 class="heading text-center">Categories</h4>

    <div class="border rounded p-3 mb-4">
        <p>Add Category</p>

        <form method="POST" action="{{ route('admin.categories.store') }}" class="d-flex">
            @csrf

            <input type="text" name="name" placeholder="Category name" required class="form-control w-25">

            <select name="type" class="form-control w-25 mx-3">
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>

            <button type="submit" class="btn btn-yellow">Add</button>
        </form>
    </div>

    <div>
        <form method="GET">
            <select name="type" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="income" @selected(request('type') === 'income')>Income</option>
                <option value="expense" @selected(request('type') === 'expense')>Expense</option>
            </select>
        </form>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>

                    <td colspan="2">
                        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="d-flex align-items-center">
                            @csrf
                            @method('PUT')

                            <input type="text" name="name" value="{{ $category->name }}" class="form-control me-2">

                            <select name="type" class="form-control me-2">
                                <option value="income" @selected($category->type === 'income')>Income</option>
                                <option value="expense" @selected($category->type === 'expense')>Expense</option>
                            </select>

                            <button type="submit" class="btn btn-yellow ms-4">Save</button>
                        </form>
                    </td>

                    <td>
                        <form method="POST"
                              action="{{ route('admin.categories.destroy', $category) }}"
                              class="d-inline-block">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="border-0 text-decoration-underline text-danger bg-transparent bold"
                                    onclick="return confirm('Delete this category?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No categories found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

@endsection
