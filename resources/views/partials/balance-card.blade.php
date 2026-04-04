<div class="card">
    <div class="card-header bold secondary-bg-color">
        {{$title}}
    </div>
    <div class="card-body">
        <h5 class="card-title">{{number_format($totalAmount, 2, ',', ' ')}} {{optional(auth()->user()->currency)->code ?? ''}}</h5>
        @if($percentDifference)
            <p class="card-text pe-5 small bold @if($percentDifference > 0) higher-than @else lower-than @endif">
                @if($percentDifference > 0) <i class="fas fa-arrow-trend-up"></i>
                @else <i class="fas fa-arrow-trend-down"></i> @endif
                {{$percentDifference}}% vs same period last month
            </p>
        @endif
    </div>
</div>
