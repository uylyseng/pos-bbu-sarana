<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Report</title>
  <style>
        body {
            font-family: Arial, sans-serif;
            padding: 10px;
            margin: 0;
            background: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 10px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
         
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .title {
            flex: 1;
            text-align: center;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #333;
        }
        .date-range {
            font-size: 14px;
            color: #555;
        }
        .separator {
            border-bottom: 2px solid #007bff;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        th {
            background: #007bff;
            color: white;
        }
        tfoot {
            font-weight: bold;
            background: #f8f9fa;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            margin: 10px;
            cursor: pointer;
            border-radius: 4px;
            border: none;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background: #6c757d;
        }
        .btn-back:hover {
            background: #5a6268;
        }
        .btn-orange {
    background-color: #078d1c;
    color: white;
    border-radius: 5px;
}
@media print {
    .btn {
        display: none;
    }
    th {
        background: #007bff !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    tfoot {
        display: table-row-group;
    }
}

    </style>

</head>
<body>

<div class="container">
  <!-- Store Logo -->
  <div class="header">
  <img src="{{ asset('storage/' . $store->image) }}" alt="Store Logo" class="logo">
  
  <div class="title">
    <h2>Sales Report</h2>
  </div>
</div>


  <!-- Date Range -->
  <p class="date-range" style="text-align: center;">
    <strong>From:</strong> {{ $startDate->format('F d, Y') }} &nbsp; 
    <strong>To:</strong> {{ $endDate->format('F d, Y') }}
</p>


  <!-- Sales Data Table -->
  <table id="salesTable">
    <thead>
      <tr>
        <th>No.</th>
        <th>Date</th>
        <th>Table</th>
        <th>Payment Status</th>
        <th>Order Status</th>
        <th>Amount</th>
        <th>Discount</th>
        <th>Change (USD)</th>
        <th>Order Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($sales as $index => $sale)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('F d, Y') }}</td>
          <td>{{ $sale->table_name ?? 'N/A' }}</td>
          <td>{{ $sale->payment_status ?? 'N/A' }}</td>
          <td>{{ $sale->order_status ?? 'N/A' }}</td>
          <td>${{ number_format($sale->amount, 2) }}</td>
          <td>${{ number_format($sale->total_discount, 2) }}</td>
          <td>${{ number_format($sale->changeusd, 2) }}</td>
          <td>${{ number_format($sale->order_total, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5">Total</td>
        <td>${{ number_format($sales->sum('amount'), 2) }}</td>
        <td>${{ number_format($sales->sum('total_discount'), 2) }}</td>
        <td>${{ number_format($sales->sum('changeusd'), 2) }}</td>
        <td>${{ number_format($sales->sum('order_total'), 2) }}</td>
      </tr>
    </tfoot>
  </table>

 

  <!-- Action Buttons -->
  <div style="text-align: center;">
    <a href="{{ route('reports.sales') }}" class="btn">Back</a>
    <button class="btn btn-orange" onclick="window.print()">Print</button>
  </div>
</div>



</body>
</html>
