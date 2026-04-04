<!DOCTYPE html>
<html lang="en">
<head lang="en">
    @include('partials.head')
</head>
<body>

@include('partials.header')
@if (session('success'))
    <div id="flash-message" class="alert alert-success alert-dismissible fade show text-center position-absolute py-2 px-5 border-0">
        {{ session('success') }}
    </div>
@endif
<div id="js-flash-message-error" style="display: none;" class="alert alert-danger alert-dismissible fade show text-center position-absolute py-2 px-5 border-0"></div>

<div id="js-flash-message" style="display: none;" class="alert alert-success alert-dismissible fade show text-center position-absolute py-2 px-5 border-0"></div>

<main class="container">
    @yield('content')
</main>

@include('partials.modal')
@include('partials.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('js/app.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
