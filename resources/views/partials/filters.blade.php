<button
    class="btn bold btn-blue w-75 my-2 p-2"
    data-bs-toggle="modal"
    data-bs-target="#globalModal"
    data-type="transaction"
    data-title="Add a Transaction"
>
    Add a Transaction
</button>
<a href="{{ route('reports.download', [
    'start' => $currentReport['start'],
    'end' => $currentReport['end']
]) }}" class="btn btn-blue bold w-75 d-block">
    Download PDF
</a>
<p class="small mb-1 mt-3 bold">Filter your transactions:</p>
<form id="transaction-filters" method="GET" action="{{ route('reports') }}">
    <select name="category" onchange="this.form.requestSubmit()" class="form-control w-75">
        <option class="font-14" value="">All Categories</option>
        @foreach($categories as $category)
            <option class="font-14" value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>

    <select name="type" onchange="this.form.requestSubmit()" class="form-control w-75 mt-2">
        <option class="font-14" value="">All Types</option>
        <option class="font-14" value="income" @selected(request('type') == 'income')>Income</option>
        <option class="font-14" value="expense" @selected(request('type') == 'expense')>Expense</option>
    </select>
</form>


