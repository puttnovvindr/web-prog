<x-guest-layout>
    <h2>Payment</h2>

    <form action="{{ route('payment.store') }}" method="POST">
        @csrf
        <div>
            <p>Your registration fee is <strong>{{ $registrationPrice }}</strong></p>
            <label for="payment_amount">Enter Payment Amount</label>
            <input type="number" name="payment_amount" required>
        </div>

        <button type="submit">Submit Payment</button>
    </form>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif
</x-guest-layout>
