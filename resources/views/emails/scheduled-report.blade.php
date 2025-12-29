<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .content {
            padding: 30px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .summary-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
        }
        .summary-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }
        .info-text {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 {{ $reportName }}</h1>
            <p>Automated {{ ucfirst($frequency) }} Report</p>
        </div>

        <div class="content">
            <p class="info-text">
                Your scheduled <span class="badge">{{ $reportType }}</span> report is ready.
            </p>

            @if(!empty($summary))
            <div class="summary-grid">
                @foreach($summary as $key => $value)
                <div class="summary-card">
                    <div class="summary-label">
                        {{ str_replace('_', ' ', $key) }}
                    </div>
                    <div class="summary-value">
                        @if(in_array($key, ['total_sales', 'avg_transaction']))
                            ₱{{ number_format($value, 2) }}
                        @else
                            {{ number_format($value) }}
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <p class="info-text">
                The full report is attached to this email as a <strong>{{ strtoupper($scheduledReport->format) }}</strong> file.
            </p>

            <p class="info-text">
                <strong>Generated:</strong> {{ $generatedAt }}
            </p>

            <a href="{{ url('/dashboard') }}" class="button">
                View Dashboard
            </a>
        </div>

        <div class="footer">
            <p>
                This is an automated report from {{ config('app.name') }}.
                <br>
                To manage your scheduled reports, visit your <a href="{{ url('/reports/scheduled') }}" style="color: #667eea;">account settings</a>.
            </p>
        </div>
    </div>
</body>
</html>
