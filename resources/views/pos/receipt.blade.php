<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Receipt</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      margin: 0;
      padding: 20px;
    }

    .receipt-container {
      width: 360px;
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

    /* Details and totals */
    .details, .totals {
      font-size: 0.9rem;
      margin-top: 10px;
      text-align: left;
    }

    /* Totals container for splitting left/right */
    .totals {
      display: flex;
      justify-content: space-between;
    }

    .totals-left {
      flex: 1;
      text-align: left;
    }

    .totals-right {
      flex: 1;
      text-align: right;
    }

    /* Ensure all paragraphs in totals are bold */
    .totals p {
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 0.85rem;
    }

    th, td {
      padding: 6px;
      border-bottom: 1px solid #ddd;
      text-align: right;
    }

    th:first-child, td:first-child {
      text-align: left;
    }

    .print-btn {
      display: block;
      width: 100%;
      padding: 8px;
      background: white;
      color: blue;
      border: 1px solid blue;
      border-radius: 10px;
      cursor: pointer;
      margin-top: 10px;
    }

    .back-btn {
      display: block;
      width: 100%;
      padding: 8px;
      background: white;
      color: red;
      border: 1px solid red;
      border-radius: 10px;
      cursor: pointer;
      margin-top: 10px;
    }

    .separator {
      border-bottom: 1px dotted #000;
      margin: 10px 0;
    }

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

    /* Print styles */
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
    <h2>{{ $store->receipt_header ?? 'Store Name' }}</h2>
    <div class="separator"></div>
    <p><strong>RECEIPT</strong></p>

    <div class="details">
      <p><strong>Date:</strong> {{ $order->created_at->format('d-m-Y h:i A') }}</p>
      <p><strong>Table:</strong> {{ $order->table->name ?? 'N/A' }}</p>
      <p><strong>Cashier:</strong> {{ $order->cashier->name ?? 'N/A' }}</p>
      <p><strong>Customer:</strong> {{ $order->customer->name ?? 'General Customer' }}</p>
    </div>

    <div class="separator"></div>

    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Dis</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $item)
          <tr>
            <td>{{ $item->product->name_en }} / {{ $item->product->name_kh }}</td>
            <td>{{ $item->quantity }}</td>
            <td>${{ number_format($item->unit_price, 2) }}</td>
            <td>{{ $item->product_discount }}</td>
            <td>${{ number_format($item->subtotal, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="separator"></div>

    <div class="totals" style="display: flex; justify-content: space-between;">
      <!-- Left Column -->
      <div class="totals-left" style="flex: 1; text-align: left;">
        <p><strong>Total (USD): </strong> ${{ number_format($order->total, 2) }}</p>
        <p><strong>Total (KHR): </strong> ៛{{ number_format($order->total * $exchangeRate, 2) }}</p>
        <p><strong>Discount: </strong> ${{ number_format($order->discount, 2) }}</p>
      </div>

      <!-- Right Column -->
      <div class="totals-right" style="flex: 1; text-align: right;">
        <p><strong>Received: </strong> ${{ number_format($payment->amount, 2) }}</p>
        <p><strong>Change (USD): </strong> ${{ number_format($payment->changeUSD ?? 0, 2) }}</p>
        <p><strong>Change (Riel): </strong> ៛{{ number_format($payment->changeRiel ?? 0, 2) }}</p>
      </div>
    </div>

    <div class="separator"></div>
    <!-- Footer -->
    <div class="receipt-footer">
      <p><strong>{{ $store->receipt_footer ?? 'THANK YOU' }}</strong></p>
      <p class="footer-date">{{ \Carbon\Carbon::now()->format('F d, Y • h:i A') }}</p>
    </div>

    <button class="print-btn" onclick="window.print()">PRINT</button>
    <button class="back-btn" onclick="goBack()">Back to POS</button>
  </div>

  <script>
  function goBack() {
    window.history.back();
    setTimeout(function() {
      window.location.reload();
    }, 100);
  }
</script>

</body>
</html>
