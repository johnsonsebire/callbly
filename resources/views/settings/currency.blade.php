<div class="card-title">@extends('layouts.master')

@section('content')
<div class="currency-settings">
    <h1>Currency Settings</h1>
    <form action="{{ route('currency.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="currency">Select Currency:</label>
            <select name="currency" id="currency" class="form-control">
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
                <!-- Add more currencies as needed -->
            </select>
        </div><div class="card-title"></div><div class="card-title"></div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div><div class="card-title"></div></div>
@endsection