<x-guest-layout>
    <h2>Payment Result</h2>

    @if (isset($overpaidAmount))
        <p class="text-green-500">Sorry, you overpaid {{ $overpaidAmount }} coins. Would you like to enter a balance?</p>

        <form method="POST" action="{{ route('wallet.update') }}">
            @csrf
            <input type="hidden" name="overpaid_amount" value="{{ $overpaidAmount }}">
            <button type="submit" name="action" value="yes">Yes, enter balance</button>
            <button type="submit" name="action" value="no">No, try again</button>
        </form>
    @endif
</x-guest-layout>
