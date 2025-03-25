<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Print Shift</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    /* General Styles */
    body {
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      margin: 0;
      padding: 20px;
    }
    .receipt-container {
      width: 320px;
      margin: 0 auto;
      background: #fff;
      padding: 15px;
      border: 1px solid #ccc;
      box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    .logo {
      width: 80px;
      margin: 0 auto 10px;
    }
    h2 {
      margin: 0;
      font-size: 1.2rem;
      font-weight: bold;
    }
    .separator {
      border-bottom: 1px solid #000;
      margin: 10px 0;
    }
    .details {
      margin-top: 10px;
    }
    .details p {
      display: flex;
      justify-content: space-between;
      margin: 5px 0;
      font-size: 0.9rem;
    }
    .details p strong {
      min-width: 120px;
      text-align: left;
    }
    /* Tables */
    .totals-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 0.85rem;
      border: 1px solid #ddd;
    }
    .totals-table th,
    .totals-table td {
      padding: 8px;
      border: 1px solid #ddd;
      text-align: right;
    }
    .totals-table th:first-child,
    .totals-table td:first-child {
      text-align: left;
    }
    .totals-table th {
      background-color: #f4f4f4;
      font-weight: bold;
    }
    /* Footer */
    .receipt-footer {
      width: 98%;
      background-color: #f3f3f3;
      padding: 3px;
      text-align: center;
      border-radius: 5px;
      margin-top: 15px;
    }
    .footer-text {
      font-size: 14px;
      font-weight: bold;
      color: #333;
    }
    .footer-date {
      font-size: 12px;
      color: #666;
      margin-top: 2px;
    }
    /* Buttons */
    .print-btn,
    .back-btn {
      display: block;
      width: 100%;
      padding: 8px;
      border-radius: 10px;
      cursor: pointer;
      margin-top: 10px;
      text-align: center;
      font-size: 1rem;
      text-decoration: none;
      border: 1px solid;
    }
    .print-btn {
      background: #fff;
      color: blue;
      border-color: blue;
    }
    .print-btn:hover {
      background: #e6f0ff;
    }
    .back-btn {
      background: #fff;
      color: red;
      border-color: red;
    }
    .back-btn:hover {
      background: #ffe6e6;
    }
    /* Print Styles */
    @media print {
      @page {
        size: A4;
        margin: 20mm;
      }
      body {
        background: #fff;
        padding: 0;
      }
      .receipt-container {
        width: auto;
        margin: 0;
        padding: 0;
        border: none;
        box-shadow: none;
      }
      .print-btn,
      .back-btn {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="receipt-container">
    <img src="{{ asset('storage/' . $store->image) }}" alt="Store Image" class="logo">
    <div class="separator"></div>
    <p><strong>CLOSE SHIFT</strong></p>
    <div class="details">
      <p>
        <strong>User:</strong>
        <span>{{ optional($shift->user)->name ?? 'N/A' }}</span>
      </p>
      <p>
        <strong>Time Open:</strong>
        <span>{{ $shift->time_open ?? 'N/A' }}</span>
      </p>
      <p>
        <strong>Time Close:</strong>
        <span>{{ $shift->time_close ?? 'N/A' }}</span>
      </p>
      <p>
        <strong>Cash In Hand:</strong>
        <span>${{ number_format($shift->cash_in_hand ?? 0, 2) }}</span>
      </p>
      <p>
        <strong>Total Cash:</strong>
        <span>${{ number_format($shift->total_cash ?? 0, 2) }}</span>
      </p>
    </div>
    <!-- Items Table -->
    <table class="totals-table">
      <thead>
        <tr>
          <th>No.</th>
          <th>Product</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orderItems as $index => $item)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td  class="border px-4 py-2 text-left">{{ $item->product->name_en }}</td>
            <td>{{ $item->quantity }}</td>
            <td>${{ number_format($item->unit_price, 2) }}</td>
            <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align:center;">No items found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <!-- Totals Summary Table -->
    <table class="totals-table" style="margin-top: 15px;">
      <tbody>
        <tr>
          <th>Total Qty</th>
          <td>{{ $orderItems->sum('quantity') }}</td>
        </tr>
        <tr>
          <th>Total Discount</th>
          <td>${{ number_format($orders->sum('discount'), 2) }}</td>
        </tr>
        <tr>
          <th>Total Price</th>
          <td>${{ number_format($orderItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
          }), 2) }}</td>
        </tr>
        <tr>
          <th>Total Amount</th>
          <td>${{ number_format($payments->sum('amount'), 2) }}</td>
        </tr>
        <tr>
          <th>Total Change</th>
          <td>${{ number_format($payments->sum('changeUSD'), 2) }}</td>
        </tr>
      </tbody>
    </table>
    <!-- Footer -->
    <div class="receipt-footer">
      <p class="footer-text"><strong>THANK YOU</strong></p>
      <p class="footer-date">{{ \Carbon\Carbon::now()->format('F d, Y • h:i A') }}</p>
    </div>
    <button class="print-btn" onclick="window.print()">PRINT</button>
    <button class="back-btn" onclick="window.history.back()">Back</button>
  </div>
</body>
</html>
