@extends('layouts.app')

@section('content')
    <div class="d-flex mt-4">
        <div class="current-month w-75 p-2">
            <div class="current-month-balance d-flex justify-content-evenly mb-5 w-100">
                <div class="total-income">
                    @include('partials.balance-card', ['totalAmount' => $currentReport['income'], 'percentDifference' => $currentReport['incomeDiff'], 'title' => 'Income This Month'])
                </div>
                <div class="total-expense">
                    @include('partials.balance-card', ['totalAmount' => $currentReport['expenses'], 'percentDifference' => $currentReport['expensesDiff'], 'title' => 'Expenses This Month'])
                </div>
            </div>
            <div class="balance-chart w-100">
                <select id="balance-range-select" class="w-25 ms-5 form-control">
                    <option value="3">Last 3 Months</option>
                    <option value="6">Last 6 Months</option>
                    <option value="12">Last 12 Months</option>
                </select>
                <div>
                    <canvas id="balanceChart"></canvas>
                </div>
            </div>
        </div>
        <div class="categories-chart d-flex flex-column align-items-center w-25 ms-4 p-2">
            <p class="bold mt-2">Top 5 Expense Categories</p>
            <select id="category-range-select" class="w-75 form-control d-block mb-4">
                <option value="1m">This Month</option>
                <option value="6m">Last 6 Months</option>
                <option value="12m">Last 12 Months</option>
            </select>
            <div class="w-100">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let categoryChart = null;
            let incomeExpenseChart = null;

            const categoryCanvas = document.getElementById('categoryChart');
            const incomeExpenseCanvas = document.getElementById('balanceChart');

            const categorySelect = document.getElementById('category-range-select');
            const incomeExpenseSelect = document.getElementById('balance-range-select');

            function loadCategoryChart(range = '1m') {
                fetch(`/dashboard/category-chart?range=${range}`)
                    .then(res => res.json())
                    .then(data => {

                        if (categoryChart) {
                            categoryChart.destroy();
                        }

                        categoryChart = new Chart(categoryCanvas.getContext('2d'), {
                            type: 'doughnut',
                            data: data,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.label + ': ' + context.raw + '%';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
            }

            function loadIncomeExpenseChart(range = '6m') {
                fetch(`/dashboard/balance-chart?range=${range}`)
                    .then(res => res.json())
                    .then(data => {

                        if (incomeExpenseChart) {
                            incomeExpenseChart.destroy();
                        }

                        incomeExpenseChart = new Chart(incomeExpenseCanvas.getContext('2d'), {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grace: '5%',
                                    }
                                }
                            }
                        });
                    });
            }

            loadCategoryChart(categorySelect?.value || '1m');
            loadIncomeExpenseChart(incomeExpenseSelect?.value || '6m');

            categorySelect?.addEventListener('change', function () {
                loadCategoryChart(this.value);
            });

            incomeExpenseSelect?.addEventListener('change', function () {
                loadIncomeExpenseChart(this.value);
            });
        });
    </script>
@endsection
