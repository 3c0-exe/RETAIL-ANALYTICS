<x-mail::message>
# {{ $severity === 'critical' ? '🚨' : '⚠️' }} {{ $severity === 'critical' ? 'Critical' : 'Warning' }} Forecast Deviation Alert

Hello **{{ $userName }}**,

We've detected a significant variance between forecasted and actual sales for **{{ $branchName }}** on **{{ $date }}**.

<x-mail::panel>
<div style="text-align: center; margin-bottom: 20px;">
<h2 style="margin: 0 0 8px 0; color: #111827; font-size: 16px; font-weight: 600;">📊 Performance Summary</h2>
<p style="margin: 0; color: #6B7280; font-size: 13px;">{{ $date }} • {{ $branchName }}</p>
</div>

<table style="width: 100%; border-collapse: separate; border-spacing: 0; margin: 20px 0;">
<tbody>
<tr style="border-bottom: 1px solid #E5E7EB;">
<td style="padding: 12px 0; color: #6B7280; font-size: 14px;">Forecasted Sales</td>
<td style="padding: 12px 0; text-align: right; color: #111827; font-weight: 600; font-size: 15px;">₱{{ number_format($forecastedSales, 2) }}</td>
</tr>
<tr style="border-bottom: 1px solid #E5E7EB;">
<td style="padding: 12px 0; color: #6B7280; font-size: 14px;">Actual Sales</td>
<td style="padding: 12px 0; text-align: right; color: #111827; font-weight: 600; font-size: 15px;">₱{{ number_format($actualSales, 2) }}</td>
</tr>
<tr style="border-bottom: 2px solid #E5E7EB;">
<td style="padding: 12px 0; color: #6B7280; font-size: 14px;">Variance</td>
<td style="padding: 12px 0; text-align: right; color: {{ $actualSales < $forecastedSales ? '#DC2626' : '#059669' }}; font-weight: 600; font-size: 15px;">
{{ $actualSales < $forecastedSales ? '-' : '+' }}₱{{ number_format(abs($actualSales - $forecastedSales), 2) }}
</td>
</tr>
<tr>
<td style="padding: 16px 0 8px 0; color: #374151; font-weight: 600; font-size: 15px;">Deviation</td>
<td style="padding: 16px 0 8px 0; text-align: right;">
<span style="display: inline-block; padding: 6px 12px; background: {{ $actualSales < $forecastedSales ? '#FEE2E2' : '#D1FAE5' }}; color: {{ $actualSales < $forecastedSales ? '#991B1B' : '#065F46' }}; border-radius: 6px; font-weight: 700; font-size: 16px;">
{{ number_format($deviationPercentage, 1) }}% {{ $actualSales < $forecastedSales ? '⬇️' : '⬆️' }}
</span>
</td>
</tr>
</tbody>
</table>

@if($actualSales < $forecastedSales)
<div style="background: linear-gradient(to right, #FEF2F2, #FEE2E2); border-left: 4px solid #DC2626; padding: 16px; margin-top: 20px; border-radius: 8px;">
<div style="display: flex; align-items: flex-start; gap: 12px;">
<span style="font-size: 24px; line-height: 1;">⚠️</span>
<div>
<strong style="color: #991B1B; font-size: 15px; display: block; margin-bottom: 4px;">Underperformance Detected</strong>
<span style="color: #7F1D1D; font-size: 14px; line-height: 1.5;">
Sales are <strong>{{ number_format($deviationPercentage, 1) }}% below forecast</strong>. Immediate investigation recommended.
</span>
</div>
</div>
</div>
@else
<div style="background: linear-gradient(to right, #F0FDF4, #D1FAE5); border-left: 4px solid #059669; padding: 16px; margin-top: 20px; border-radius: 8px;">
<div style="display: flex; align-items: flex-start; gap: 12px;">
<span style="font-size: 24px; line-height: 1;">🎉</span>
<div>
<strong style="color: #065F46; font-size: 15px; display: block; margin-bottom: 4px;">Exceptional Performance!</strong>
<span style="color: #064E3B; font-size: 14px; line-height: 1.5;">
Sales exceeded forecast by <strong>{{ number_format($deviationPercentage, 1) }}%</strong>. Analyze success drivers for replication.
</span>
</div>
</div>
</div>
@endif
</x-mail::panel>

---

## 📋 Recommended Actions

@if($actualSales < $forecastedSales)
<div style="background: #F9FAFB; padding: 16px; border-radius: 8px; border: 1px solid #E5E7EB;">

**Immediate steps to investigate underperformance:**

<table style="width: 100%; margin-top: 12px;">
<tbody>
<tr>
<td style="padding: 8px 0; vertical-align: top; width: 32px;">
<span style="display: inline-block; width: 24px; height: 24px; background: #EF4444; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-weight: 700; font-size: 12px;">1</span>
</td>
<td style="padding: 8px 0; color: #374151; font-size: 14px;">
<strong>Review inventory levels</strong> and product availability
</td>
</tr>
<tr>
<td style="padding: 8px 0; vertical-align: top;">
<span style="display: inline-block; width: 24px; height: 24px; background: #EF4444; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-weight: 700; font-size: 12px;">2</span>
</td>
<td style="padding: 8px 0; color: #374151; font-size: 14px;">
<strong>Check for competitor promotions</strong> or market changes
</td>
</tr>
<tr>
<td style="padding: 8px 0; vertical-align: top;">
<span style="display: inline-block; width: 24px; height: 24px; background: #EF4444; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-weight: 700; font-size: 12px;">3</span>
</td>
<td style="padding: 8px 0; color: #374151; font-size: 14px;">
<strong>Analyze customer traffic</strong> and conversion rates
</td>
</tr>
<tr>
<td style="padding: 8px 0; vertical-align: top;">
<span style="display: inline-block; width: 24px; height: 24px; background: #EF4444; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-weight: 700; font-size: 12px;">4</span>
</td>
<td style="padding: 8px 0; color: #374151; font-size: 14px;">
<strong>Verify pricing accuracy</strong> across all channels
</td>
</tr>
<tr>
<td style="padding: 8px 0; vertical-align: top;">
<span style="display: inline-block; width: 24px; height: 24px; background: #EF4444; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-weight: 700; font-size: 12px;">5</span>
</td>
<td style="padding: 8px 0; color: #374151; font-size: 14px;">
<strong>Investigate operational issues</strong> on that day (staffing, systems, etc.)
</td>
</tr>
</tbody>
</table>

</div>
@else
<div style="background: #F0FDF4; padding: 16px; border-radius: 8px; border: 1px solid #BBF7D0;">

**Capitalize on this success:**

<table style="width: 100%; margin-top: 12px;">
<tbody>
<tr>
<td style="padding: 8px 0; vertical-align: top; width: 32px;">
<span style="display: inline-block; width: 24px; height: 24px; background: #10B981; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-weight: 700; font-size: 12px;">1</span>
</td>
<td style="padding: 8px 0; color: #065F46; font-size: 14px;">
<strong>Analyze what drove the increase</strong> (promotions, events, etc.)
</td>
</tr>
<tr>
<td style="padding: 8px 0; vertical-align: top;">
<span style="display: inline-block; width: 24px; height: 24px; background: #10B981; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-weight: 700; font-size: 12px;">2</span>
</td>
<td style="padding: 8px 0; color: #065F46; font-size: 14px;">
<strong>Ensure adequate inventory</strong> to sustain demand
</td>
</tr>
<tr>
<td style="padding: 8px 0; vertical-align: top;">
<span style="display: inline-block; width: 24px; height: 24px; background: #10B981; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-weight: 700; font-size: 12px;">3</span>
</td>
<td style="padding: 8px 0; color: #065F46; font-size: 14px;">
<strong>Review staffing levels</strong> for peak periods
</td>
</tr>
<tr>
<td style="padding: 8px 0; vertical-align: top;">
<span style="display: inline-block; width: 24px; height: 24px; background: #10B981; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-weight: 700; font-size: 12px;">4</span>
</td>
<td style="padding: 8px 0; color: #065F46; font-size: 14px;">
<strong>Document winning strategies</strong> for future forecasts
</td>
</tr>
<tr>
<td style="padding: 8px 0; vertical-align: top;">
<span style="display: inline-block; width: 24px; height: 24px; background: #10B981; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-weight: 700; font-size: 12px;">5</span>
</td>
<td style="padding: 8px 0; color: #065F46; font-size: 14px;">
<strong>Consider scaling</strong> successful tactics to other branches
</td>
</tr>
</tbody>
</table>

</div>
@endif

---

<x-mail::button :url="$dashboardUrl" color="primary">
📈 View Full Analytics Dashboard
</x-mail::button>

<div style="margin-top: 24px; padding: 16px; background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); border-radius: 8px;">
<div style="display: flex; align-items: flex-start; gap: 12px;">
<span style="font-size: 20px;">💡</span>
<div>
<strong style="color: white; font-size: 14px; display: block; margin-bottom: 4px;">Pro Tip</strong>
<span style="color: rgba(255, 255, 255, 0.95); font-size: 13px; line-height: 1.5;">
Forecast deviations above 10% warrant immediate investigation. Use the analytics dashboard to drill down into product-level performance and identify specific drivers.
</span>
</div>
</div>
</div>

---

<div style="text-align: center; font-size: 12px; color: #9CA3AF; margin-top: 24px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
<p style="margin: 0 0 8px 0;">This is an automated alert from your <strong style="color: #6B7280;">Retail Analytics Platform</strong></p>
<p style="margin: 0;">
To adjust notification preferences, visit your <a href="{{ route('notifications.preferences') }}" style="color: #3B82F6; text-decoration: none; font-weight: 500;">account settings</a>
</p>
</div>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
