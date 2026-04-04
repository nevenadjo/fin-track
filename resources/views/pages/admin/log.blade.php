@extends('layouts.admin')

@section('content')
    <h4 class="heading text-center mb-4">Logs</h4>

    <form method="GET" class="mb-3 d-flex">
        <input type="text" class="form-control w-25" name="search" placeholder="Search logs..."
               value="{{ request('search') }}">

        <input type="date" class="form-control w-25 mx-3" name="date" value="{{ request('date') }}">

        <button type="submit" class="btn btn-yellow">Filter</button>
    </form>

    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Action</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
            </thead>

            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>

                    <td>
                        {{ optional($log->user)->name ?? 'System' }}
                    </td>

                    <td>{{ $log->action }}</td>

                    <td>{{ $log->message }}</td>

                    <td>{{ $log->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No logs found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $logs->withQueryString()->links() }}
    </div>

@endsection
