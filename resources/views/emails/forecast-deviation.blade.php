<x-mail::message>
# 🚨 Critical Forecast Deviation Alert

Hello **{{ $userName }}**,

We've detected a significant variance between forecasted and actual sales for **{{ $branchName }}** on **{{ $date }}**.

<x-mail::panel>
## 📊 Performance Summary

| Metric | Value |
|:-------|------:|
| **Forecasted Sales** | ₱{{ number_format($forecastedSales, 2) }} |
| **Actual Sales** | ₱{{ number_format($actualSales, 2) }} |
| **Variance** | ₱{{ number_format(abs($actualSales - $forecastedSales), 2) }} |
| **Deviation** | **{{ number_format($deviationPercentage, 1) }}%** {{ $actualSales < $forecastedSales ? '⬇️ Below' : '⬆️ Above' }} |

@if($actualSales < $forecastedSales)
<div style="background: #FEF2F2; border-left: 4px solid #DC2626; padding: 12px; margin-top: 16px; border-radius: 4px;">
<strong style="color: #991B1B;">⚠️ Underperformance Detected</strong><br>
<span style="color: #7F1D1D; font-size: 14px;">Sales are {{ number_format($deviationPercentage, 1) }}% below forecast.</span>
</div>
@else
<div style="background: #F0FDF4; border-left: 4px solid #059669; padding: 12px; margin-top: 16px; border-radius: 4px;">
<strong style="color: #065F46;">✅ Overperformance Detected</strong><br>
<span style="color: #064E3B; font-size: 14px;">Sales exceeded forecast by {{ number_format($deviationPercentage, 1) }}%.</span>
</div>
@endif
</x-mail::panel>

---

## 📋 Recommended Actions

@if($actualSales < $forecastedSales)
**Immediate steps to investigate underperformance:**

1. **Review inventory levels** and product availability
2. **Check for competitor promotions** or market changes
3. **Analyze customer traffic** and conversion rates
4. **Verify pricing accuracy** across all channels
5. **Investigate operational issues** on that day (staffing, systems, etc.)
@else
**Capitalize on this success:**

1. **Analyze what drove the increase** (promotions, events, etc.)
2. **Ensure adequate inventory** to sustain demand
3. **Review staffing levels** for peak periods
4. **Document winning strategies** for future forecasts
5. **Consider scaling** successful tactics to other branches
@endif

---

<x-mail::button :url="$dashboardUrl" color="primary">
View Full Analytics Dashboard
</x-mail::button>

<div style="margin-top: 24px; padding: 16px; background: #F9FAFB; border-radius: 8px; font-size: 13px; color: #6B7280;">
<strong style="color: #374151;">💡 Quick Tip:</strong> Forecast deviations above 10% warrant immediate investigation. Use the analytics dashboard to drill down into product-level performance and identify specific drivers.
</div>

---

<div style="font-size: 12px; color: #9CA3AF; margin-top: 20px;">
This is an automated alert from your Retail Analytics Platform.<br>
To adjust notification preferences, visit your <a href="{{ route('notifications.preferences') }}" style="color: #3B82F6;">account settings</a>.
</div>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
