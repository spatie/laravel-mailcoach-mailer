<x-mail::message>
    # Order Shipped

    Your order has been shipped!

    <x-mail::button url="https://spatie.be">
        View Order
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
