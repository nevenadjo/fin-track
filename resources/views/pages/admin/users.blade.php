@extends('layouts.admin')

@section('content')
    <h4 class="heading text-center">Users</h4>

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" placeholder="Search users..." class="form-control w-25"
               value="{{ request('search') }}">

        <button type="submit" class="btn btn-yellow ms-3">Search</button>
    </form>

    <div>
        <table class="table table-striped table-bordered user-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>

                    <td>
                        <form method="POST"
                              action="{{ route('admin.users.change-role', $user) }}">
                            @csrf
                            @method('PATCH')

                            <select name="role" onchange="this.form.submit()">
                                <option value="2" @selected(!$user->isAdmin())>User</option>
                                <option value="1" @selected($user->isAdmin())>Admin</option>
                            </select>
                        </form>
                    </td>

                    <td>
                        @if($user->is_blocked)
                            <span style="color: red;">Blocked</span>
                        @else
                            <span style="color: green;">Active</span>
                        @endif
                    </td>

                    <td>
                        <form method="POST"
                              action="{{ route('admin.users.toggle-block', $user) }}">
                            @csrf
                            @method('PATCH')

                            <button type="submit" class="link-danger border-0 bg-transparent ps-2 text-decoration-underline">
                                {{ $user->is_blocked ? 'Unblock' : 'Block' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No users found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->withQueryString()->links() }}
    </div>

@endsection
