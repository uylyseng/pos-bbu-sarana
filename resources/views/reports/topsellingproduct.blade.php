@extends('layouts.master')

@section('content')
<div class="container mx-auto px-6 py-8">
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li
                class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Top Selling Products Report</a>
            </li>
        </ol>
    </nav>
    <h2 class="text-xl font-semibold mb-4">Top Selling Products Report </h2>

    <!-- Filter Form -->
    <form action="{{ route('reports.topsellingproduct') }}" method="GET" class="mb-4">
    <div class="flex space-x-4">
        <!-- Start Date Input -->
        <input type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}"
            class="form-input h-9 px-3 border rounded-md  ">

        <!-- End Date Input -->
        <input type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}"
            class="form-input h-9 px-3 border rounded-md  ">

        <!-- Search Input -->
        <input type="text" name="search" placeholder="Search..." value="{{ $searchQuery }}"
            class="dataTable-input border rounded-md px-4 h-9  ">

        <!-- Submit Button -->
        <button type="submit" class="btn-blue px-6 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700">
            Submit
        </button>

        <!-- Print Button -->
        <a href="{{ route('reports.print_topselling', [
            'start_date' => request('start_date', $startDate->toDateString()),
            'end_date' => request('end_date', $endDate->toDateString()),
            'search' => request('search', $searchQuery),
        ]) }}" 
        class="btn-orange px-6 py-2 rounded-md text-white bg-orange-500 hover:bg-orange-600" 
      >
            Print
        </a>
    </div>
</form>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="w-full border-gray-200">
            <thead class="bg-green-700 text-white">
                <tr>
                    <th data-sortable="" class="px-4 py-2">#</th>
                    <th data-sortable="" class="px-4 py-2">Product Name</th>
                    <th data-sortable="" class="px-4 py-2">Quantity Sold</th>
                    <th data-sortable="" class="px-4 py-2">Total Sales</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topSellingProducts as $index => $product)
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-2">{{ $startRecord + $index  }}</td>
                    <td class="px-4 py-2">{{ $product->name_en }}</td>
                    <td class="px-4 py-2">{{ $product->total_quantity }}</td>
                    <td class="px-4 py-2">${{ number_format($product->total_sales, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="text-gray-900 font-semibold">
                <tr class="font-bold">
                    <td colspan="3" class="py-2 px-4 text-left">📌Total</td>
                    <td class="px-4 py-2 text-red-500">${{ number_format($topSellingProducts->sum('total_sales'), 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Pagination -->

    <div>
        {!! $topSellingProducts->links('layouts.pagination') !!}

    </div>
</div>
</div>
<script>
    function printTopSelling() {
        var content = document.getElementById('top-selling-content').innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = content; // Show only the top-selling section
        window.print(); // Trigger browser print dialog
        document.body.innerHTML = originalContent; // Restore original content after printing
    }
</script>

<style>
/* Button Styling */
.btn-orange {
    background-color: #078d1c;
    color: white;
    border-radius: 5px;
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

td.px-4.py-2.text-red-500 {
    color: green;
}

/* Table Styling */
table {
    border-collapse: collapse;
    width: 100%;
}

th,
td {
    text-align: left;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

th {
    background: #3b82f2;
    /* Blue gradient */
    color: white;
    padding: 16px;
    font-weight: 600;
    text-align: left;
}

/* Search Input Styling */
.dataTable-search input {
    padding: 5px;
    width: 200px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* DataTable Wrapper Styling */
</style>
@endsection