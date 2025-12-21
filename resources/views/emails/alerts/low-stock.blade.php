<x-mail::message>
# Low Stock Alert

Hello **{{ $alert->user->name ?? 'User' }}**,

A product is running low on stock:

<x-mail::panel>
**Product:** {{ $alert->related->product->name ?? 'Unknown Product' }}

**Branch:** {{ $alert->related->branch->name ?? 'Unknown Branch' }}

**Current Stock:** {{ $alert->related->quantity ?? 0 }} units

**Threshold:** {{ $alert->related->low_stock_threshold ?? 0 }} units
</x-mail::panel>

<x-mail::button :url="config('app.url')">
View Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
