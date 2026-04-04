@extends('layouts.admin')

@section('content')
    <h4 class="heading text-center">Analytics</h4>
    <p class="text-secondary text-center mt-3">All time stats:</p>
    <div class="d-flex justify-content-center">
        <div class="stat-card">Users: {{ $usersCount }}</div>
        <div class="stat-card mx-4">Logs: {{ $logsCount }}</div>
        <div class="stat-card">Transactions: {{ $transactionsCount }}</div>
    </div>

    <div class="w-75 mx-auto">
        <p class="text-secondary text-center mt-4">Users created in the past 7 days:</p>
        <canvas id="usersChart"></canvas>
        <p class="text-secondary text-center mt-4">Logs created in the past 7 days:</p>
        <canvas id="logsChart"></canvas>
        <p class="text-secondary text-center mt-4">Transactions created in the past 7 days:</p>
        <canvas id="transactionsChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new Chart(document.getElementById('usersChart'), {
                type: 'line',
                data: {
                    labels: @json($usersChart->keys()),
                    datasets: [{data: @json($usersChart->values()) }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                           display: false
                        },
                    }
                }
            });

            new Chart(document.getElementById('logsChart'), {
                type: 'line',
                data: {
                    labels: @json($logsChart->keys()),
                    datasets: [{data: @json($logsChart->values()) }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                    }
                }
            });

            new Chart(document.getElementById('transactionsChart'), {
                type: 'line',
                data: {
                    labels: @json($transactionsChart->keys()),
                    datasets: [{data: @json($transactionsChart->values()) }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                    }
                }
            });
        });
    </script>

@endsection
