@extends('layouts.master')

@section('content')
<div class="container mx-auto px-6 py-8">
<nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Sales List</a>
            </li>
        </ol>
    </nav>
    <h2 class="text-xl font-bold mb-4">Sales List</h2>

    <!-- Search & Per-Page Form -->
    <div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-4">
        <!-- Search Form -->
        <form method="GET" class="flex items-center gap-4">
            <input type="text" name="search" placeholder="Search table, status, etc..."
                   value="{{ request('search') }}"
                   class="input w-64">
           
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 font-bold">
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Table</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Discount</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">P-Status</th>
                    <th class="px-4 py-2">Paid</th>
                    <th class="px-4 py-2">Change</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $sale->sale_date }}</td>
                    <td class="px-4 py-2">{{ $sale->table_name ?? 'N/A' }}</td>
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
                    <td class="px-4 py-2">${{ number_format($sale->discount, 2) }}</td>
                    <td class="px-4 py-2">${{ number_format($sale->total, 2) }}</td>
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
                    <td class="px-4 py-2">${{ number_format($sale->paid, 2) }}</td>
                    <td class="px-4 py-2">${{ number_format($sale->change_usd, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-3 text-center">No sales found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination & Per-Page Control -->
    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center">
        @if ($sales->total() > 0)
            <!-- Per-Page Dropdown -->
            <div>
                <select id="per_page" class="input p-2 border rounded"
                        onchange="window.location.href='{{ $sales->url(1) }}&per_page=' + this.value">
                    <option value="2" {{ request('per_page') == 2 ? 'selected' : '' }}>2</option>
                    <option value="4" {{ request('per_page') == 4 ? 'selected' : '' }}>4</option>
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                </select>
            </div>

            <!-- Simple Pagination -->
            <div class="pagination">
                @if ($sales->onFirstPage())
                    <span class="btn-disabled ">←</span>
                @else
                    <a href="{{ $sales->previousPageUrl() }}&per_page={{ request('per_page') }}" class="btn-pagination ">←</a>
                @endif

                <span class="btn-page ">{{ $sales->currentPage() }}</span>

                @if ($sales->hasMorePages())
                    <a href="{{ $sales->nextPageUrl() }}&per_page={{ request('per_page') }}" class="btn-pagination ">→</a>
                @else
                    <span class="btn-disabled ">→</span>
                @endif
            </div>
        @else
            <p class="text-center text-gray-600 dark:text-gray-300">No sales available.</p>
        @endif
    </div>
</div>
@endsection
<style>
    /* Input & Select Styling */
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

    /* Dark Mode */
    .dark .input {
        background-color: #1f2937;
        color: #f9fafb;
        border-color: #4b5563;
    }

    .dark .input:focus {
        border-color: #3b82f6;
    }

    /* Buttons */
    .btn-primary {
        background-color: #005ff5;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background-color: #1e40af;
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

    }

    th {
    background: #3b82f2; /* Blue gradient */
    color: white;
    padding: 16px;
    font-weight: 600;
    text-align: left;
}

    .dark .table thead {
        background: linear-gradient(90deg, #14532d, #1e3a8a);
    }

    /* Footer Total Row */
    .tfoot-total {
        background-color: #f3f4f6;
        font-weight: bold;
    }

    .dark .tfoot-total {
        background-color: #374151;
    }

    /* Pagination */
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
</style>
