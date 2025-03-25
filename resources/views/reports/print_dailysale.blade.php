<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Daily Sales Report</title>
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
        <h2>Daily Sales Report</h2>
    </div>

    <!-- Date Range -->
    <p class="date-range">
        <strong >From:</strong> {{ $startDate->format('F d, Y') }} &nbsp;
        <strong>To:</strong> {{ $endDate->format('F d, Y') }}
    </p>

    <!-- Sales Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Sale Date</th>
                <th>Total Quantity</th>
                <th>Total Sales ($)</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach($dailySales as $sale)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') }}</td>
                    <td>{{ $sale->total_quantity }}</td>
                    <td>${{ number_format($sale->total_sales, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td>{{ $dailySales->sum('total_quantity') }}</td>
                <td>${{ number_format($dailySales->sum('total_sales'), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Action Buttons -->
<div class="no-print" style="text-align: center; margin-top: 20px;">
    <a href="{{ route('reports.daily-sales') }}" class="btn" style="margin-right: 15px;">Back</a>
    <button onclick="window.print()" class="btn btn-green">Print</button>
</div>


</div>
</body>
</html>
