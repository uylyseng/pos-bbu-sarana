@extends('layouts.master')

@section('content')
<div class="container mx-auto px-6 py-8">
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Sale Items Report</a>
            </li>
        </ol>
    </nav>

    <h2 class="text-2xl font-bold text-gray-800 mb-4">Sale Items Report</h2>

    <!-- Filter Form -->
    <!-- Filter Form -->
<form action="{{ route('reports.sale-items') }}" method="GET" class="mb-4">
    <div class="flex space-x-4">
        <input type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}" class="form-input h-9">
        <input type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}" class="form-input h-9">
        <button type="submit" class="btn-blue px-6 py-2 rounded text-white">Submit</button>
        <!-- Print Button -->
        <a href="{{ route('reports.print-sale-items', ['start_date' => request('start_date', $startDate->toDateString()), 'end_date' => request('end_date', $endDate->toDateString())]) }}" class="btn-orange px-6 py-2 rounded text-white">Print</a>
    </div>
</form>


    <!-- Data Table -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="w-full border border-gray-200">
            <thead class="bg-green-700 text-white">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">#</th>
                    <th class="px-6 py-3 text-left font-semibold">Sale Date</th>
                    <th class="px-6 py-3 text-left font-semibold">Product Name</th>
                    <th class="px-6 py-3 text-left font-semibold">Quantity</th>
                    <th class="px-6 py-3 text-left font-semibold">Discount</th>
                    <th class="px-6 py-3 text-left font-semibold">Base Price</th>
                    <th class="px-6 py-3 text-left font-semibold">Sale Price</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @php
                    $groupedSales = $saleItems->groupBy('sale_date');
                    $grandTotalQty = 0;
                    $grandTotalDiscount = 0;
                    $grandTotalSalePrice = 0;
                @endphp

                @foreach($groupedSales as $date => $sales)
                    @php
                        $dailyTotalQty = $sales->sum('qty');
                        $dailyTotalDiscount = $sales->sum('item_discount');
                        $dailyTotalSalePrice = $sales->sum('sale_price');

                        $grandTotalQty += $dailyTotalQty;
                        $grandTotalDiscount += $dailyTotalDiscount;
                        $grandTotalSalePrice += $dailyTotalSalePrice;
                    @endphp

                    <tr class="bg-gray-300">
                        <td colspan="7" class="px-6 py-3 font-bold text-gray-800">{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                    </tr>

                    @foreach($sales as $index => $item)
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-gray-100' }} border-b hover:bg-gray-200">
                            <td class="px-6 py-3">{{ $loop->iteration }}</td>
                            <td class="px-6 py-3"></td> <!-- Empty because sale date is already shown -->
                            <td class="px-6 py-3">{{ $item->product_name }}</td>
                            <td class="px-6 py-3">{{ $item->qty }}</td>
                            <td class="px-6 py-3">{{ $item->item_discount }}$</td>
                            <td class="px-6 py-3">${{ number_format($item->base_price, 2) }}</td>
                            <td class="px-6 py-3">${{ number_format($item->sale_price, 2) }}</td>
                        </tr>
                    @endforeach

                    <!-- Subtotal Row for Each Date -->
                    <tr class="bg-yellow-100 font-bold">
                        <td colspan="3" class="px-6 py-3 text-right">Subtotal for {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}:</td>
                        <td class="px-6 py-3">{{ $dailyTotalQty }}</td>
                        <td class="px-6 py-3">{{ number_format($dailyTotalDiscount, 2) }}$</td>
                        <td class="px-6 py-3"></td> <!-- Empty for base price -->
                        <td class="px-6 py-3">${{ number_format($dailyTotalSalePrice, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>

            <!-- Grand Total Row -->
            <tfoot class="bg-green-200 font-bold">
                <tr>
                    <td colspan="3" class="px-6 py-3 text-left">Grand Total</td>
                    <td class="px-6 py-3">{{ $grandTotalQty }}</td>
                    <td class="px-6 py-3">{{ number_format($grandTotalDiscount, 2) }}$</td>
                    <td class="px-6 py-3"></td> <!-- Empty for base price -->
                    <td class="px-6 py-3">${{ number_format($grandTotalSalePrice, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>


    <!-- Pagination -->
    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center">
        @if ($saleItems->total() > 0)
            <div>
                <select id="per_page" class="input"
                    onchange="window.location.href='{{ $saleItems->url(1) }}&per_page=' + this.value">
                    <option value="2"  {{ request('per_page') == 2  ? 'selected' : '' }}>2</option>
                    <option value="4"  {{ request('per_page') == 4  ? 'selected' : '' }}>4</option>
                    <option value="5"  {{ request('per_page') == 5  ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                </select>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                @if ($saleItems->onFirstPage())
                    <span class="btn-disabled">←</span>
                @else
                    <a href="{{ $saleItems->previousPageUrl() }}&per_page={{ request('per_page') }}" class="btn-pagination">←</a>
                @endif

                <span class="btn-page">{{ $saleItems->currentPage() }}</span>

                @if ($saleItems->hasMorePages())
                    <a href="{{ $saleItems->nextPageUrl() }}&per_page={{ request('per_page') }}" class="btn-pagination">→</a>
                @else
                    <span class="btn-disabled">→</span>
                @endif
            </div>
        @else
            <p class="text-center text-gray-600 dark:text-gray-300">No sale items available.</p>
        @endif
    </div>
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
