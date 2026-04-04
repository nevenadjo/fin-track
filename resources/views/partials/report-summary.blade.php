<p class="primary-text-color bold h5 mb-3 mt-2">Report Summary:</p>
@foreach($summary as $key => $value)
    @if($key !== 'expense_by_category')
        @if(is_numeric($value) && $key !== "savings_rate")
            <p class="mb-1 font-14 bold primary-text-color">
                {{Str::headline($key)}}
                <p class="mb-2 pb-2 border-bottom font-14 bold">{{number_format($value, 2, ',', ' ')}} {{optional(auth()->user()->currency)->code ?? ''}}
            </p>
        @elseif($key === "savings_rate")
            <p class="mb-1 font-14 bold primary-text-color">
                {{Str::headline($key)}}
                <p class="mb-2 pb-2 border-bottom font-14 bold">{{number_format($value, 2, ',', ' ')}}%
            </p>
        @else
            <p class="mb-1 font-14 bold primary-text-color">
                {{Str::headline($key)}}
                <p class="mb-2 border-bottom pb-2 font-14 bold">{{$value ?? "/"}}
            </p>
        @endif
    @endif
@endforeach
