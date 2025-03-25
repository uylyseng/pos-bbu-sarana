@extends('layouts.master')

@section('content')
<div class="container mx-auto px-6 py-8">
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Sale Report</a>
            </li>
        </ol>
    </nav>
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Sales Report</h2>

    <!-- Filter Form -->
   

    <form action="{{ route('reports.sales') }}" method="GET" class="mb-4">
    <div class="flex space-x-4">
        <input type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}" class="form-input h-9">
        <input type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}" class="form-input h-9">
        
        <button type="submit" class="btn-blue px-6 py-2 rounded text-white">Submit</button>
        <!-- Print Button -->
        <a href="{{ route('reports.print_sales', ['start_date' => request('start_date', $startDate->toDateString()), 'end_date' => request('end_date', $endDate->toDateString())]) }}" class="btn-orange px-6 py-2 rounded text-white">Print</a>
    </div>
</form>

    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="w-full border-collapse border border-gray-200">
            <thead class="bg-green-700 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">Sale Date</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Table</th>
                    <th class="px-6 py-3 text-right text-sm font-medium">Discount</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Payment Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Order Status</th>
                    <th class="px-6 py-3 text-right text-sm font-medium">Amount</th>
                    <th class="px-6 py-3 text-right text-sm font-medium">ChangeUSD</th>
                    <th class="px-6 py-3 text-right text-sm font-medium">Order Total</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($sales as $date => $salesPerDate)
                    <tr class="bg-gray-100">
                        <td colspan="8" class="px-6 py-3 text-center text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                    </tr>
                    @php
                        $totalDiscount = $salesPerDate->sum('total_discount');
                        $totalAmount = $salesPerDate->sum('amount');
                        $totalChangeUSD = $salesPerDate->sum('changeusd');
                        $totalOrderTotal = $salesPerDate->sum('order_total');
                    @endphp

                    @foreach($salesPerDate as $sale)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3 text-sm">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') }}</td>
                            <td class="px-6 py-3 text-sm">{{ $sale->table_name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-right text-sm">${{ number_format($sale->total_discount, 2) }}</td>
                           <!-- Payment Status -->
                                                        <!-- Payment Status -->
                            <td class="px-6 py-3 text-sm">
                                @php
                                    $paymentStatus = strtolower($sale->payment_status ?? 'N/A');
                                    $paymentClass = match($paymentStatus) {
                                        'completed' => 'status-completed',
                                        'pending' => 'status-pending',
                                        default => 'status-unknown'
                                    };
                                @endphp
                                <span class="status-badge {{ $paymentClass }}">
                                    {{ ucfirst($sale->payment_status ?? 'N/A') }}
                                </span>
                            </td>

                            <!-- Order Status -->
                            <td class="px-6 py-3 text-sm">
                                @php
                                    $orderStatus = strtolower($sale->order_status ?? 'N/A');
                                    $orderClass = match($orderStatus) {
                                        'complete' => 'status-completed',
                                        'hold' => 'status-pending',
                                        default => 'status-warning'
                                    };
                                @endphp
                                <span class="status-badge {{ $orderClass }}">
                                    {{ ucfirst($sale->order_status ?? 'N/A') }}
                                </span>
                            </td>

                            <td class="px-6 py-3 text-right text-sm">${{ number_format($sale->amount, 2) }}</td>
                            <td class="px-6 py-3 text-right text-sm">${{ number_format($sale->changeusd, 2) }}</td>
                            <td class="px-6 py-3 text-right text-sm">${{ number_format($sale->order_total, 2) }}</td>
                        </tr>
                    @endforeach

                    <tr class="font-bold">
                        <td colspan="2" class="px-6 py-3 text-left">Total for {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                        <td class="px-6 py-3 text-right">${{ number_format($totalDiscount, 2) }}</td>
                        <td colspan="4"></td>
                        <td class="px-6 py-3 text-right">${{ number_format($totalOrderTotal, 2) }}</td>
                    </tr>
                @endforeach
                <div class="mt-6 flex flex-col sm:flex-row justify-between items-center">
            
              
            </tbody>
        </table>
  
   
    
</div>
<div class="mt-4">
        {{ $salesPaginated->appends(request()->query())->links('layouts.pagination') }}
    </div>
<script>
    function printSaleItems() {
        var content = document.getElementById('filtered-content').innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = content; // Only display the filtered content for printing
        window.print(); // Trigger the print dialog

        document.body.innerHTML = originalContent; // Restore the original content after printing
    }
</script>

<style>
    .btn-orange {
    background-color: #078d1c;
    color: white;
    border-radius: 5px;
}

    .status-badge {
    padding: 4px 8px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
}

.status-completed {
    background-color: #d1fae5; /* green-100 */
    color: #065f46;           /* green-800 */
}

.status-pending {
    background-color: #fee2e2; /* red-100 */
    color: #991b1b;            /* red-700 */
}

.status-warning {
    background-color: #fef3c7; /* yellow-100 */
    color: #92400e;            /* yellow-800 */
}

.status-unknown {
    background-color: #e5e7eb; /* gray-100 */
    color: #4b5563;            /* gray-600 */
}

    .input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        outline: none;
        background-color: #ffffff;
        color: #333;
        transition: border-color 0.2s;
    }

    .input:focus {
        border-color: #3b82f6;
    }

    .btn-blue {
        background-color: #005ff5;
        color: white;
        padding: 8px 12px;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    .btn-blue:hover {
        background-color: darkblue;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        text-align: left;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    th {
        background: #3b82f2; /* Blue gradient */
        color: white;
        padding: 16px;
        font-weight: 600;
        text-align: left;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-pagination {
        background-color: #f3f4f6;
        padding: 0.6rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-pagination:hover {
        background-color: #e5e7eb;
    }

    .btn-disabled {
        background-color: #d1d5db;
        padding: 0.6rem 1rem;
        border-radius: 6px;
        color: gray;
    }

    .btn-page {
        font-weight: bold;
        padding: 0.6rem 1rem;
        background-color: #2563eb;
        color: white;
        border-radius: 6px;
    }
</style>
@endsection
