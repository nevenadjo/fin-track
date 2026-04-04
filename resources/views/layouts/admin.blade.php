<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin Panel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
        >
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
    @if (session('success'))
        <div id="flash-message" class="alert alert-success alert-dismissible fade show text-center position-absolute py-2 px-5 border-0">
            {{ session('success') }}
        </div>
    @endif
    <div class="d-flex w-100">
        <div class="admin-sidebar">
            <div class="position-sticky admin-nav">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.users.index') }}">Users</a>
                <a href="{{ route('admin.categories.index') }}">Categories</a>
                <a href="{{ route('admin.logs.index') }}">Logs</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-link">Logout</button>
                </form>
            </div>
        </div>

        <div class="admin-dashboard-content">
            @yield('content')
        </div>
    </div>

    @include('partials.modal')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/app.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </body>
</html>
