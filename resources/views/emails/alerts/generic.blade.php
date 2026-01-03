{{-- resources/views/emails/alerts/generic.blade.php --}}

<x-mail::message>
# {{ $alertTitle }}

{{ $alertMessage }}

@if(!empty($metadata))
<x-mail::panel>
**Additional Details:**

@foreach($metadata as $key => $value)
- **{{ ucwords(str_replace('_', ' ', $key)) }}:** {{ is_array($value) ? json_encode($value) : $value }}
@endforeach
</x-mail::panel>
@endif

<x-mail::button :url="$alertUrl" :color="$severity === 'critical' ? 'error' : 'primary'">
View in Dashboard
</x-mail::button>

---

**Alert Type:** {{ ucwords(str_replace('_', ' ', $alertType)) }}
**Severity:** {{ ucfirst($severity) }}
**Time:** {{ $createdAt }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
