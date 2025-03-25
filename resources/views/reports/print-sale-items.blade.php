<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Sale Items</title>
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
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Section -->
    <div class="header">
        <!-- Store Logo (Left) -->
        <img src="{{ asset('storage/' . $store->image) }}" alt="Store Image" class="logo">

        <!-- Title & Date Range (Centered) -->
        <div class="title">
            <h2>Sale Items Report</h2>
            <div class="date-range">
                <p><strong>From:</strong> {{ $startDate->format('F d, Y') }} &nbsp; 
                   <strong>To:</strong> {{ $endDate->format('F d, Y') }}</p>
            </div>
        </div>
    </div>


    <!-- Sale Items Table -->
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Date</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Base Price</th>
                <th>Sale Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($saleItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->sale_date }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>${{ number_format($item->base_price, 2) }}</td>
                    <td>${{ number_format($item->sale_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;">Total</td>
                <td>{{ $saleItems->sum('qty') }}</td>
                <td>${{ number_format($saleItems->sum('base_price'), 2) }}</td>
                <td>${{ number_format($saleItems->sum('sale_price'), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Buttons -->
    <div style="text-align: center;">
        <a href="{{ route('reports.sale-items') }}" class="btn btn-back">Back</a>
        <button class="btn" onclick="window.print()">Print</button>
    </div>
</div>

</body>
</html>
