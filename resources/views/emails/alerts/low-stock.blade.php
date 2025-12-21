<x-mail::message>
# {{ $urgencyLevel === 'CRITICAL' ? '🚨 CRITICAL' : '⚠️' }} Low Stock Alert

Hello **{{ $userName }}**,

A product is running low on stock and requires immediate attention:

<x-mail::panel>
**Product:** {{ $productName }}
**SKU:** {{ $sku }}
**Category:** {{ $category->name ?? 'N/A' }}

---

**Branch:** {{ $branchName }}
**Current Stock:** {{ $currentStock }} units
**Threshold:** {{ $threshold }} units
**Stock Level:** {{ $stockPercentage }}%

@if($urgencyLevel === 'CRITICAL')
⚠️ **CRITICAL: Stock is below 50% of threshold!**
@else
⚠️ **WARNING: Stock is running low**
@endif
</x-mail::panel>

Please restock this product as soon as possible to avoid stockouts.

<x-mail::button :url="$alertUrl" color="primary">
View Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
