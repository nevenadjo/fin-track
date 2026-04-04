@extends('layouts.app')

@section('content')
<div class="container" id="author-info">
    <h4 class="text-center mb-4 heading">About Us</h4>
    <p class="mb-4 w-50 mx-auto text-secondary text-center">Personal finance tracker to help you manage your income, expenses, and savings. Track your budget, generate reports, and take control of your finances.</p>
    <div class="justify-content-center d-flex align-items-center">
        <div class="w-25 me-4">
            <img src="{{asset('author.jpg')}}" class="d-block w-100 mb-4 mb-md-0" alt="author"/>
        </div>
        <div class="w-25 info-text">

            <p class="bold primary-text-color-light">Ime i prezime:</p>
            <p>Nevena Đošan</p>

            <p class="bold primary-text-color-light">Broj indeksa:</p>
            <p>13/22</p>

            <p class="bold primary-text-color-light">Studijski program:</p>
            <p>Internet tehnologije</p>

        </div>
    </div>
</div>
@endsection
