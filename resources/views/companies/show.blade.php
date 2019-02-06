@extends('app')

@section('content')

<div class="company">
    <h2><img src="{{ $company->logo }}" /> {{ $company->name }}</h2>
    <div class="invesments">Total raised: ${{ number_format($totalInvestments, 2) }}</div>
</div>

@endsection
