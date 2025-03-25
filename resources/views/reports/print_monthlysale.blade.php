<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Monthly Sales Report</title>
  <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0;  /* Remove padding */
            margin: 0;   /* Remove margin */
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin: 0;
            color: #333;
            flex: 1;
        }

        .date-range {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tfoot th {
            background-color: transparent;
            color: #333;
        }

        /* General Button Style */
.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;  /* Blue color for the Back button */
    color: white;
    text-decoration: none;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Green Print Button */
.btn-green {
    background-color: #2cc317;
    color: white;
    border: aquamarine;
}

/* Hover effects */
.btn:hover {
    background-color: #0056b3;  /* Darker blue on hover for Back button */
}

.btn-green:hover {
    background-color: #057f13;  /* Darker green on hover for Print button */
}

        .no-print {
            text-align: center;
            margin-top: 20px;
        }

        @media print {
            /* Set paper size to A4 */
            @page {
                size: A4;
                margin: 0;  /* Remove margin to push content to the top */
            }

            body {
                margin-top: 0 !important; /* Ensure no margin at top */
                padding-top: 0 !important; /* Ensure no padding at top */
            }

            .container {
                margin-top: 0 !important; /* Ensure container starts at top */
            }

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

  <!-- Header Section -->
  <div class="header">
    <img src="{{ asset('storage/' . $store->image) }}" alt="Store Logo" class="logo">
    <h2>Monthly Sales Report</h2>
  </div>

  <!-- Date Range -->
  <div class="date-range">
    <p><strong>Month:</strong> {{ $selectedMonth->format('F Y') }}</p>
  </div>

  <!-- Sales Table -->
  <table>
    <thead>
      <tr>
        <th>No.</th>
        <th>Month</th>
        <th>Total Quantity</th>
        <th>Total Sales</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($sales as $index => $row)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $row->sale_month)->format('F Y') }}</td>
          <td>{{ $row->total_quantity }}</td>
          <td>${{ number_format($row->total_sales, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">Total</td>
        <td>{{ $sales->sum('total_quantity') }}</td>
        <td>${{ number_format($sales->sum('total_sales'), 2) }}</td>
      </tr>
    </tfoot>
  </table>

  <!-- Action Buttons -->
  <div class="no-print">
    <a href="{{ route('reports.monthly-sales') }}" class="btn">Back</a>
    <button class="btn btn-green" onclick="window.print()">Print</button>
  </div>

</div>
</body>
</html>
