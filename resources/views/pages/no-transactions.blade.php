@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-center">
        <img src="{{asset('chart-image.jpg')}}" alt="illustrative image" class="w-50 d-block" />
        <div class="text-center">
            <p class="heading">You have no transactions yet.</p>
            <button
                class="btn bold btn-blue w-75 my-2 p-2"
                data-bs-toggle="modal"
                data-bs-target="#globalModal"
                data-type="transaction"
                data-title="Add a Transaction"
            >
                Add a Transaction
            </button>
        </div>

    </div>

    <div class="d-none" id="template-transaction">
        @include('partials.account.transaction-form', ['edit' => 'false', 'refresh' => 'true'])
    </div>
@endsection
