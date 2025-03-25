@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Purchase Report</a>
            </li>
        </ol>
    </nav>

    <h2 class="text-xl font-semibold mb-4">Purchase Report</h2>

    <!-- Filter Form -->
    <form action="{{ route('reports.purchases') }}" method="GET" class="mb-4">
    <div class="flex space-x-4">
        <!-- Supplier Dropdown -->
        <select name="supplier_id" class="form-input h-10">
            <option value="">Select a Supplier...</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>

        <!-- Start Date Input -->
        <input type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}" class="form-input h-10">
        
        <!-- End Date Input -->
        <input type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}" class="form-input h-10">
        
        <button type="submit" class="btn-blue px-6 py-2 text-white">Submit</button>
        <button 
            type="button"
            class="btn-orange px-6 py-2 rounded text-white" 
            onclick="window.location.href='{{ route('reports.purchases_print', [
                'start_date' => request('start_date'), 
                'end_date'   => request('end_date'), 
                'supplier_id'=> request('supplier_id'),
            ]) }}'"
        >
            Print
        </button>
    </div>
</form>




    <!-- Purchase Table -->
    <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns mt-6">
        <div class="dataTable-container">
            <table id="myTable1" class="whitespace-nowrap dataTable-table">
                <thead>
                    <tr>
                        <th data-sortable="" class="px-4 py-2">#</th>
                        <th data-sortable="" class="px-4 py-2">Date</th>
                        <th data-sortable="" class="px-4 py-2">Supplier</th>
                        <th data-sortable="" class="px-4 py-2">Reference No.</th>
                        <th data-sortable="" class="px-4 py-2">Status</th>
                        <th data-sortable="" class="px-4 py-2">Total</th>
                        <th data-sortable="" class="px-4 py-2">Action</th> 
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $index => $purchase)
                        <tr>
                            <td class="px-4 py-2">{{ $startRecord + $index }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2">{{ $purchase->supplier->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $purchase->reference }}</td>
                            <td class="px-4 py-2">{{ ucfirst($purchase->status) }}</td>
                            <td class="px-4 py-2">${{ number_format($purchase->total, 2) }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('reports.purchasepreview', $purchase->id) }}">
                                    <img src="{{ asset('icons/previews.png') }}" alt="Preview" class="w-6 h-6 cursor-pointer" />
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
               
                <tfoot>
                    <tr>
                        <td class="px-4 py-2 font-bold">📌Total:</td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2">
                            <div class="flex justify-start">
                                <span class="font-bold text-red-300">${{ number_format($purchases->sum('total'), 2) }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
   
        <!-- Pagination -->
        <div >
            {!! $purchases->links('layouts.pagination') !!}
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
       
        border-radius: 5px;
       
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
