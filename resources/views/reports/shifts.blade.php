@extends('layouts.master')

@section('content')
    <div class="container mx-auto px-6 py-8">
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Shift Report</a>
            </li>
        </ol>
    </nav>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Shift Report</h2>

        <!-- Filter Form -->
        <form action="{{ route('reports.shifts') }}" method="GET"
            class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex flex-wrap items-end gap-4">
                <!-- Start Date -->
                <div class="flex-1 min-w-[200px]">
                 
                    <input type="date" id="start_date" name="start_date" class="input" value="{{ request('start_date') }}">
                </div>

                <!-- End Date -->
                <div class="flex-1 min-w-[200px]">
                   
                    <input type="date" id="end_date" name="end_date" class="input" value="{{ request('end_date') }}">
                </div>

                <!-- User Filter -->
                <div class="flex-1 min-w-[200px]">
               
                    <select id="user_id" name="user_id" class="input">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Keep per_page if set -->
                @if(request()->has('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif

                <!-- Submit Button -->
                <div class="flex items-center">
                    <button type="submit" class="btn-primary"> Filter</button>
                </div>
            </div>
        </form>

        <!-- Shift Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md ">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Time Open</th>
                        <th>Time Close</th>
                        <th>User</th>
                        <th>Cash In Hand</th>
                        <th>Total Cash</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($shifts as $shift)
                        <tr>
                            <td>{{ $shift->id }}</td>
                            <td>{{ $shift->time_open }}</td>
                            <td>{{ $shift->time_close }}</td>
                            <td>{{ optional($shift->user)->name ?? 'N/A' }}</td>
                            <td>${{ number_format($shift->cash_in_hand, 2) }}</td>
                            <td>${{ number_format($shift->total_cash, 2) }}</td>
                            <td>
  <a href="{{ route('shift.print', ['shiftId' => $shift->id, 'userId' => $shift->user_id]) }}">
    <img src="{{ asset('icons/prints.png') }}" class="w-6 h-6" alt="Print Icon">
  </a>
</td>



                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="tfoot-total">
                        <td colspan="4">📌 Total</td>
                        <td>${{ number_format($shifts->sum('cash_in_hand'), 2) }}</td>
                        <td>${{ number_format($shifts->sum('total_cash'), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination & Per-Page Dropdown -->
        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center">
            @if ($shifts->total() > 0)
                <!-- Per-Page Dropdown -->
                <div>
                    
                    <select id="per_page" class="input"
                        onchange="window.location.href='{{ $shifts->url(1) }}&per_page=' + this.value">
                        <option value="2" {{ request('per_page') == 2 ? 'selected' : '' }}>2</option>
                        <option value="4" {{ request('per_page') == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </div>

                <!-- Simple < Page > Pagination -->
                <div class="pagination">
                    @if ($shifts->onFirstPage())
                        <span class="btn-disabled">←</span>
                    @else
                        <a href="{{ $shifts->previousPageUrl() }}&per_page={{ request('per_page') }}" class="btn-pagination">←</a>
                    @endif

                    <span class="btn-page">{{ $shifts->currentPage() }}</span>

                    @if ($shifts->hasMorePages())
                        <a href="{{ $shifts->nextPageUrl() }}&per_page={{ request('per_page') }}" class="btn-pagination">→</a>
                    @else
                        <span class="btn-disabled">→</span>
                    @endif
                </div>
            @else
                <p class="text-center text-gray-600 dark:text-gray-300">No shifts available.</p>
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
</style>