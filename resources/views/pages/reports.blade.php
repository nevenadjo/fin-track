@extends('layouts.app')

@section('content')
    <div class="reports-container d-flex justify-content-center">
        <div class="w-25 mt-5 d-flex flex-column align-items-center">
            <div class="view-switch mb-4 mt-1 w-100">
                <a href="{{ route('reports', array_merge(request()->query(), ['view' => 'transactions'])) }}"
                   class="{{ $view === 'transactions' ? 'active' : '' }} pe-2 ps-3 py-2 rounded-start-pill border font-12 bold" data-view="transactions">
                    <span>Transactions view</span>
                </a>
                <a href="{{ route('reports', array_merge(request()->query(), ['view' => 'analytics'])) }}"
                   class="{{ $view === 'analytics' ? 'active' : '' }} pe-3 ps-2 py-2 rounded-end-pill border font-12 bold" data-view="analytics">
                    <span>Analytics view</span>
                </a>
            </div>
            <div id="reportsList" class="reportsList overflow-auto rounded w-100">
                @include('partials.reports-list', compact('reports', 'currentReport'))
            </div>
        </div>

        <div id="main-content" class="w-50 ps-4">
            @if($view === 'transactions')
                @include('partials.transactions', compact('transactions'))
            @else
                @include('partials.analytics', compact('summary'))
            @endif
        </div>

        <div id="side-content" class="w-25 mt-5 ps-4">
            @if($view === 'transactions')
                @include('partials.filters', compact('categories'))
            @else
                @include('partials.report-summary', compact('summary'))
            @endif
        </div>
    </div>

    {{--Hidden modal content--}}
    <div class="d-none" id="template-transaction">
        @include('partials.account.transaction-form', ['edit' => 'false', 'refresh' => 'false'])
    </div>
    <div class="d-none" id="template-edit-transaction">
        @include('partials.account.transaction-form', ['edit' => 'true', 'refresh' => 'false'])
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reportContainer = document.getElementById('main-content');
            const sideContent = document.getElementById('side-content');
            const url = new URL(window.location.href);
            if(url.searchParams.get('view') === 'analytics') {
                initExpenseChart();
            }

            reportContainer.addEventListener('click', function (e) {
                const link = e.target.closest('.pagination a');

                if (link) {
                    e.preventDefault();

                    fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => res.json())
                        .then(html => {
                            reportContainer.innerHTML = html.main;
                        });
                }
            });
            document.querySelectorAll('.view-switch a').forEach(link => {
                link.addEventListener('click', e => {
                    e.preventDefault();

                    const url = new URL(window.location.href);
                    url.searchParams.set('view', link.dataset.view);

                    fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(res => res.json())
                        .then(html => {
                            document.getElementById('main-content').innerHTML = html.main;
                            document.getElementById('side-content').innerHTML = html.side;

                            if(url.searchParams.get('view') === 'analytics') {
                                initExpenseChart();
                            }
                            document.querySelectorAll('.view-switch a').forEach(btn => {
                                btn.classList.remove('active');
                            });
                            e.target.closest('a').classList.add('active');

                            window.history.pushState({}, '', url);
                        });
                });
            });
            sideContent.addEventListener('submit', function (e) {
                e.preventDefault();
                const form = document.getElementById('transaction-filters');

                const url = new URL(window.location.href);
                const formData = new FormData(form);
                const newParams = new URLSearchParams(formData);

                newParams.forEach((value, key) => {
                    url.searchParams.set(key, value);
                });

                fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                    .then(res => res.json())
                    .then(html => {
                        reportContainer.innerHTML = html.main;
                        window.history.pushState({}, '', url);
                    });
            });
        });
    </script>
@endsection
