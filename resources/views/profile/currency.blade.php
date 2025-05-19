@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Currency Settings</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success mb-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <p>Select your preferred currency for displaying prices and billing information.</p>
                    
                    <form method="POST" action="{{ route('settings.currency.update') }}">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label for="currency_id" class="form-label">Currency</label>
                            <select name="currency_id" id="currency_id" class="form-control @error('currency_id') is-invalid @enderror">
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" 
                                        {{ $currentCurrency->id === $currency->id ? 'selected' : '' }}>
                                        {{ $currency->name }} ({{ $currency->symbol }})
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group mt-4">
                            <p><strong>Note:</strong> Changing your currency doesn't affect the actual amount in your account. It only changes how prices are displayed.</p>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Save Currency Preference</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Current Exchange Rates</h5>
                </div>
                <div class="card-body">
                    <p>For reference, here are the current exchange rates relative to the Ghanaian Cedi (GHS):</p>
                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Currency</th>
                                <th>Code</th>
                                <th>Symbol</th>
                                <th>Exchange Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currencies as $currency)
                                <tr>
                                    <td>{{ $currency->name }}</td>
                                    <td>{{ $currency->code }}</td>
                                    <td>{{ $currency->symbol }}</td>
                                    <td>
                                        @if($currency->code === 'GHS')
                                            1.0000 (Base Currency)
                                        @else
                                            {{ number_format($currency->exchange_rate, 4) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection