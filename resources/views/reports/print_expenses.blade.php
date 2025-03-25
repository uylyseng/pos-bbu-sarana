<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Expense Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      .no-print {
        display: none;
      }
      /* Ensure the table footer is displayed only on the last printed page */
      tfoot {
        display: table-row-group;
      }
      /* Force header colors to print */
      thead {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      /* Remove container styling when printing */
      .print-container {
        background: none !important;
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
      }
    }
  </style>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="print-container max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
      <!-- Logo -->
      <div class="w-20">
        <img src="{{ asset('storage/' . $store->image) }}" alt="Store Logo" class="w-20 h-20 object-contain">
      </div>
      <!-- Report Title -->
      <div class="flex-1 text-center">
        <h2 class="text-2xl font-bold">Expense Report</h2>
      </div>
      <!-- Spacer -->
      <div class="w-20"></div>
    </div>

    <!-- Date Range -->
    <div class="text-center text-sm mb-4">
      <span class="font-semibold">From:</span> {{ $startDate->format('F d, Y') }} &nbsp;&nbsp;
      <span class="font-semibold">To:</span> {{ $endDate->format('F d, Y') }}
    </div>

    <!-- Expense Table -->
    <table class="w-full border border-gray-300 mt-4 text-sm">
      <thead class="bg-blue-500 text-white">
        <tr>
          <th class="border px-4 py-2">No.</th>
          <th class="border px-4 py-2">Date</th>
          <th class="border px-4 py-2">Reference</th>
          <th class="border px-4 py-2">Created By</th>
          <th class="border px-4 py-2">Description</th>
          <th class="border px-4 py-2">Payment Method</th>
          <th class="border px-4 py-2">Amount ($)</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($expenses as $index => $expense)
          <tr class="hover:bg-gray-100">
            <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
            <td class="border px-4 py-2 text-center">{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
            <td class="border px-4 py-2 text-center">{{ $expense->reference }}</td>
            <td class="border px-4 py-2 text-center">{{ $expense->created_by }}</td>
            <td class="border px-4 py-2 text-center">{{ $expense->description }}</td>
            <td class="border px-4 py-2 text-center">{{ $expense->payment_method }}</td>
            <td class="border px-4 py-2 text-center">${{ number_format($expense->total_amount, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot class="bg-gray-200">
        <tr>
          <td colspan="6" class="border px-4 py-2 text-right font-bold">Total</td>
          <td class="border px-4 py-2 text-center font-bold">${{ number_format($expenses->sum('total_amount'), 2) }}</td>
        </tr>
      </tfoot>
    </table>

    <!-- Buttons -->
    <div class="no-print text-center mt-4 space-x-4">
      <a href="{{ route('reports.expenses') }}" class="inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Back</a>
      <button onclick="window.print()" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Print</button>
    </div>
  </div>
</body>
</html>
