@extends('layouts.app')

@section('content')
    <h2 class="mb-3 text-center heading">{{ $user->name }}'s Settings</h2>
    <div class="d-flex justify-content-evenly">
        <div class="account-section w-50 d-flex flex-column align-items-center">
            <h5 class="heading mb-4">Personal Info</h5>
            <div class="btn-group-vertical w-75" role="group" aria-label="Vertical button group">
                <button
                    class="btn btn-yellow account-button"
                    data-bs-toggle="modal"
                    data-bs-target="#globalModal"
                    data-type="name"
                    data-title="Edit Name"
                >
                    <span class="bold">Edit Name</span><br/><span class="small">{{$user->name}}</span>
                </button>
                <button
                    class="btn btn-yellow account-button"
                    data-bs-toggle="modal"
                    data-bs-target="#globalModal"
                    data-type="email"
                    data-title="Change Email Address"
                >
                    <span class="bold">Change Email</span><br/><span class="small">{{$user->email}}</span>
                </button>
                <button
                    class="btn bold btn-yellow account-button"
                    data-bs-toggle="modal"
                    data-bs-target="#globalModal"
                    data-type="password"
                    data-title="Change Password"
                >
                    Change Password
                </button>
            </div>
        </div>
        <div class="account-section w-50 d-flex flex-column align-items-center">
            <h5 class="heading mb-4 d-flex flex-column align-items-center">Preferences</h5>
            <div class="w-75 preferences-border">
                <form method="POST" action="/account/update-preferences" class="d-flex flex-column">
                    @csrf

                    <div class="d-flex justify-content-evenly">
                        <div class="w-45">
                            <label for="month_start" class="bold">Month Start Date
                                <span class="my-tooltip">
                                    <i class="fa-solid fa-circle-info">
                                        <span class="tooltip-text font-10 ms-1">Set the first day of your financial month for more accurate tracking and reports. If the selected day doesn’t exist in a given month, the last day of that month will be used.</span>
                                    </i>
                                </span>
                            </label>
                            <select name="month_start" id="month_start" class="form-control mb-1"
                                    data-init="{{ auth()->user()->month_start }}">
                                @for ($i = 1; $i <= 31; $i++)
                                    <option
                                        value="{{ $i }}" {{ old('month_start', auth()->user()->month_start) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="w-45">
                            <label for="currency" class="bold">Currency</label>
                            <select name="currency" id="currency" class="form-control mb-2"
                                    data-init="{{ auth()->user()->currency_id }}">
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}"
                                        {{ $user->currency_id == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-yellow align-self-center">Save</button>
                </form>
            </div>
            <button
                class="btn bold btn-yellow w-75 mt-3 p-2"
                data-bs-toggle="modal"
                data-bs-target="#globalModal"
                data-type="category"
                data-title="Edit Categories"
            >
                Edit Your Categories
            </button>
        </div>
    </div>

    {{--Hidden modal content--}}
    <div class="d-none" id="template-name">
        @include('partials.account.edit-name-form')
    </div>

    <div class="d-none" id="template-password">
        @include('partials.account.edit-password-form')
    </div>

    <div class="d-none" id="template-email">
        @include('partials.account.edit-email-form')
    </div>

    <div class="d-none" id="template-category">
        @include('partials.account.edit-categories-form')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let modal = new bootstrap.Modal(document.getElementById('globalModal'));
            let modalContent = document.getElementById('modalContent');
            let modalTitle = document.getElementById('modalTitle');

            @if ($errors->any() && !$errors->has('currency') && !$errors->has('month_start'))
                @if ($errors->has('name'))
                modalTitle.innerText = "Edit Name";
            modalContent.innerHTML = document.getElementById('template-name').innerHTML;
            @elseif ($errors->has('email'))
                modalTitle.innerText = "Change Email";
            modalContent.innerHTML = document.getElementById('template-email').innerHTML;
            @elseif ($errors->has('current_password') || $errors->has('password'))
                modalTitle.innerText = "Change Password";
            modalContent.innerHTML = document.getElementById('template-password').innerHTML;
            @elseif ($errors->has('category-name'))
                modalTitle.innerText = "Edit Categories";
            modalContent.innerHTML = document.getElementById('template-category').innerHTML;
            @endif

            modal.show();
            @endif
        });
    </script>
@endsection
