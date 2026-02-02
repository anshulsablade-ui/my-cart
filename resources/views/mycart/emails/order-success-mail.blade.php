<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacOSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            color: #2d9cdb;
            margin-bottom: 8px;
        }
        h4 {
            margin-top: 24px;
            color: #444;
        }
        .highlight {
            font-weight: bold;
            color: #2d9cdb;
        }
        .label {
            font-weight: 600;
            min-width: 140px;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .total-row {
            font-weight: bold;
            background: #f1faff;
        }
        .footer {
            margin-top: 32px;
            font-size: 0.95em;
            color: #666;
        }
    </style>
</head>
<body>

    <h2>Thank you for your order! 🎉</h2>

    <p>Hello <strong>{{ $order->user->name }}</strong>,</p>

    <p>Your order has been successfully placed.</p>

    <h4>Order Details</h4>
    <p>
        <span class="label">Order Number:</span>
        <span class="highlight">{{ $order->order_no }}</span>
    </p>
    <p>
        <span class="label">Order Date:</span>
        {{ $order->created_at->format('d M Y, h:i A') }}
    </p>
    <p>
        <span class="label">Payment Method:</span>
        {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
    </p>
    <p>
        <span class="label">Payment Status:</span>
        <strong style="color: {{ $order->payment_status === 'paid' ? '#28a745' : '#dc3545' }}">
            {{ ucfirst($order->payment_status) }}
        </strong>
    </p>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 24px 0;">

    <h4>Items Ordered</h4>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>₹{{ number_format($item->unit_price ?? $item->product->final_price ?? 0, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->subtotal ?? ($item->quantity * ($item->unit_price ?? $item->product->final_price ?? 0)), 2) }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="3" style="text-align:right">Grand Total</td>
                <td>₹{{ number_format($order->grand_total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if ($order->payment_method === 'cod')
        <p style="color: #e67e22; font-weight: 500;">
            Please keep ₹{{ number_format($order->grand_total, 2) }} ready in cash when our delivery.
        </p>
    @endif

    <p class="footer">
        Thank you for shopping with us!<br><br>
        <strong>{{ config('app.name') }} Team</strong>
    </p>

</body>
</html>