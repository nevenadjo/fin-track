<h5 id="currentReport" class="mb-3 mt-2 heading text-center" data-start="{{$currentReport['start']}}" data-end="{{$currentReport['end']}}">Transactions for {{formatDate($currentReport['start'])}} - {{formatDate($currentReport['end'])}}</h5>
@if($transactions->isEmpty()) <p class="text-center mt-5 text-secondary">No transactions to display.</p> @endif
<div class="@if(!$transactions->isEmpty()) border @endif p-2 rounded" id="transaction-container">
    @foreach($transactions as $transaction)
       <div id="transaction-{{$transaction->id}}" class="transaction px-3 py-2 d-flex align-items-center justify-content-between rounded">
           <div>
               <p class="mb-1 @if($transaction->category->is_archived) text-secondary @else primary-text-color bold @endif">{{$transaction->category->name}} <span class="fst-italic small">@if($transaction->category->is_archived) --deleted @endif </span>
                   <span class="small ms-2 text-secondary fw-normal font-12">{{formatDate($transaction->date)}}</span>
               </p>
               <p class="mb-0"><span class="bold @if($transaction->category->type === "expense") expense @else income @endif">{{number_format($transaction->amount, 2, ',', ' ')}}</span> {{optional(auth()->user()->currency)->code ?? ''}}</p>
           </div>
           <div>
               <button
                   class="btn bold btn-yellow px-3 py-2"
                   data-bs-toggle="modal"
                   data-bs-target="#globalModal"
                   data-type="edit-transaction"
                   data-id="{{$transaction->id}}"
                   data-category="{{$transaction->category_id}}"
                   data-date="{{$transaction->date}}"
                   data-amount="{{$transaction->amount}}"
                   data-title="Edit Transaction"
               >
                   Edit
               </button>
               <button onclick="deleteTransaction({{$transaction->id}})" class="border-0 bg-transparent ms-2">X</button>
           </div>
       </div>
    @endforeach
    <div class="mt-2 my-pagination">{{ $transactions->links() }}</div>
</div>
