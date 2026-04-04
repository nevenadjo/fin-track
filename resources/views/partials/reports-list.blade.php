@foreach($reports as $report)
    <a
        href="{{ route('reports', ['$report'=> $report['start']]) }}"
        onclick="return loadReport(event, '{{ $report['start'] }}')"
        data-start="{{$report['start']}}"
        class="report-btn btn rounded-0 p-3 blue-border d-block @if(!empty($currentReport) && $report['start'] === $currentReport['start']) active-report @endif"
    >
        {{ $report['label'] }}
    </a>
@endforeach
