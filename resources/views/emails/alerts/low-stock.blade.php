<x-mail::message>
# @if($urgencyLevel === 'CRITICAL') 🚨 CRITICAL @else ⚠️ WARNING @endif: Low Stock Alert

Hello **{{ $userName }}**,

We're writing to inform you that a product at **{{ $branchName }}** is running low on stock and requires your immediate attention.

<x-mail::panel>
## Product Details

**Product:** {{ $productName }}
**SKU:** {{ $sku }}
**Category:** {{ $category }}
**Branch:** {{ $branchName }}

---

**Current Stock:** {{ $currentStock }} units
**Threshold:** {{ $threshold }} units
**Stock Level:** {{ $stockPercentage }}% of threshold

@if($urgencyLevel === 'CRITICAL')
⚠️ **This is a critical alert.** Stock has fallen below 50% of the threshold.
@endif
</x-mail::panel>

## Recommended Actions

@if($urgencyLevel === 'CRITICAL')
1. **Reorder immediately** to prevent stockouts
2. Check with suppliers for fastest delivery options
3. Consider temporary stock transfers from other branches
@else
1. Review sales trends for this product
2. Plan reorder within the next 3-5 days
3. Monitor stock levels daily
@endif

<x-mail::button :url="$alertUrl" color="primary">
View Product Inventory
</x-mail::button>

---

*This is an automated alert from your Retail Analytics Platform.*
*Alert generated on {{ now()->format('M d, Y \a\t g:i A') }}*

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
