@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-6">
  <!-- Breadcrumb Navigation -->
  <nav aria-label="breadcrumb" class="mb-4 flex">
    <ol class="flex text-gray-500 font-semibold dark:text-white">
      <li>
        <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
      </li>
      <li class="before:content-[''] before:block before:w-1 before:h-1 before:rounded-full before:bg-primary before:mx-4">
        <a href="{{ route('expenses.index') }}" class="text-primary">Expenses</a>
      </li>
      <li class="before:content-[''] before:block before:w-1 before:h-1 before:rounded-full before:bg-primary before:mx-4">
        <a href="javascript:;" class="text-primary">Trash</a>
      </li>
    </ol>
  </nav>

  <!-- Flash Message -->
  @if(session('success'))
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 5000
      });
    });
  </script>
  @endif

  <!-- Header & Back Button -->
  <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
    <h2 class="text-xl font-semibold dark:text-white">Trashed Expenses</h2>
    <a href="{{ route('expenses.index') }}" class="btn-gray inline-flex items-center px-4 py-2 rounded">
      <i class="fa-solid fa-arrow-left mr-2"></i> Back to Expenses
    </a>
  </div>

  <!-- Trashed Expenses Table -->
  <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
    <table class="w-full whitespace-nowrap shadow-sm">
      <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Expense Type</th>
          <th class="px-4 py-2">Payment Method</th>
          <th class="px-4 py-2">Reference</th>
          <th class="px-4 py-2">Date</th>
          <th class="px-4 py-2">Amount</th>
          <th class="px-4 py-2">Description</th>
          <th class="px-4 py-2 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($expenses as $expense)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
          <td class="px-4 py-2 dark:text-white">{{ $expense->id }}</td>
          <td class="px-4 py-2 dark:text-white">{{ optional($expense->expenseType)->name ?? 'N/A' }}</td>
          <td class="px-4 py-2 dark:text-white">{{ optional($expense->paymentMethod)->name ?? 'N/A' }}</td>
          <td class="px-4 py-2 dark:text-white">{{ $expense->reference }}</td>
          <td class="px-4 py-2 dark:text-white">{{ $expense->expense_date }}</td>
          <td class="px-4 py-2 dark:text-white">{{ $expense->amount }}</td>
          <td class="px-4 py-2 dark:text-white">{{ $expense->description }}</td>
          <td class="px-4 py-2 text-center">
            <div class="flex justify-center space-x-2">
              <!-- Restore Button -->
              <form action="{{ route('expenses.restore', $expense->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn-green inline-flex items-center px-3 py-1 rounded">
                  <i class="fa-solid fa-rotate-left mr-1"></i> Restore
                </button>
              </form>
              <!-- Permanent Delete Button -->
              <form action="{{ route('expenses.forceDelete', $expense->id) }}" method="POST" onsubmit="return confirm('Permanently delete this expense?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-red inline-flex items-center px-3 py-1 rounded">
                  <i class="fa-solid fa-trash mr-1"></i> Delete Permanently
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex justify-center mt-4">
    {{ $expenses->links() }}
  </div>
</div>
@endsection
