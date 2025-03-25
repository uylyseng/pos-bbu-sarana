@extends('layouts.master')

@section('content')
    <div class="container mx-auto px-6 py-8">
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Sales by Category Report</a>
            </li>
        </ol>
    </nav>
        <h2 class="text-xl font-semibold mb-4">Sales by Category Report</h2>

        <!-- Filter Form -->
        <form action="{{ route('reports.sales-by-category') }}" method="GET" class="mb-4">
            <div class="flex space-x-4">
                <!-- Start Date Input -->
                 
                <input type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}"
                    class="form-input h-9">

                <!-- End Date Input -->
                <input type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}"
                    class="form-input h-9">

                <button type="submit" class="btn-blue px-6 py-2 rounded text-white">Submit</button>
                 <!-- Print Button -->
                <a href="{{ route('reports.print_salebycategory', ['start_date' => request('start_date', $startDate->toDateString()), 'end_date' => request('end_date', $endDate->toDateString())]) }}"
                    
                    class="btn-orange px-6 py-2 rounded text-white">
                    Print
                </a>
            </div>
        </form>

        <!-- XDataTable Table -->
 
                        <div class="overflow-x-auto bg-white  dark-white shadow-md rounded-lg">
        <table class="w-full border border-gray-200">
        <thead class="bg-green-700 text-white">
                <tr class="bg-gradient-to-r from-green-500 to-blue-500 text-white">
                    <th class="px-6 py-3 text-left font-semibold">#</th>
                    <th class="px-6 py-3 text-left font-semibold">Category Name</th>
                    <th class="px-6 py-3 text-left font-semibold">Total Quantity Sold</th>
                    <th class="px-6 py-3 text-left font-semibold">Total Sales</th>
                </tr>
            </thead>
                        <tbody>
                            @foreach($salesByCategory as $index => $category)
                                <tr>
                                    <td>{{ $startRecord + $index  }}</td>
                                    <td>{{ $category->category_name }}</td>
                                    <td>{{ $category->total_quantity }}</td>
                                    <td>${{ number_format($category->total_sales, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="text-gray-900 font-semibold">
                            <tr class="tfoot-total-row">
                                <td colspan="2" class="py-2 px-4 text-left">📌Total</td>
                                <td class="py-2 px-4">{{ number_format($salesByCategory->sum('total_quantity'), 0) }}</td>
                                <td class="py-2 px-4">${{ number_format($salesByCategory->sum('total_sales'), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <div>

                    @if ($salesByCategory->total() > 0)
                        <div >
                            {{ $salesByCategory->links('layouts.pagination') }}
                        </div>
                    @else
                        <p>No salesByCategory available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Print Script -->
    <script>
        function printReport() {
            window.print();
        }
    </script>

    <style>
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
    .dataTable-table {
        width: 100%;
    }

    /* Pagination Style */
    .dataTable-pagination-list {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 10px;
    }

    .dataTable-pagination-list li {
        display: inline;
    }

    .dataTable-pagination-list li a {
        padding: 8px;
        background-color: #f4f4f4;
        border-radius: 4px;
        text-decoration: none;
        color: #000;
    }

    .dataTable-pagination-list li.active a {
        background-color: #007bff;
        color: white;
    }
    </style>

@endsection