<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purchase Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      .no-print {
        display: none;
      }
      /* Prevent tfoot from repeating on each printed page */
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
        box-shadow: none !important;
        background: transparent !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
      }
    }
  </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

  <div class="print-container max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6 border mt-6">
    <!-- Header with Logo and Title -->
    <div class="flex items-center justify-between mb-4">
      <!-- Logo -->
      <div>
        <img src="{{ asset('storage/' . $store->image) }}" alt="Store Logo" class="w-20 h-20 object-contain">
      </div>

      <!-- Title -->
      <div class="text-center flex-1">
        <h1 class="text-2xl font-bold text-green-600">Purchase Report</h1>
        <p class="text-sm text-gray-600">From {{ $startDate->format('d-m-Y') }} to {{ $endDate->format('d-m-Y') }}</p>
      </div>
    </div>

    <!-- Table -->
    <table class="w-full border border-gray-300 mt-4 text-sm">
      <thead class="bg-blue-400 text-white">
        <tr>
          <th class="border px-4 py-2 text-left">Date</th>
          <th class="border px-4 py-2 text-left">Supplier</th>
          <th class="border px-4 py-2 text-left">Reference No.</th>
          <th class="border px-4 py-2 text-left">Status</th>
          <th class="border px-4 py-2 text-right">Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($purchases as $purchase)
        <tr class="hover:bg-gray-50">
          <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y') }}</td>
          <td class="border px-4 py-2">{{ optional($purchase->supplier)->name ?? 'N/A' }}</td>
          <td class="border px-4 py-2">{{ $purchase->reference }}</td>
          <td class="border px-4 py-2 capitalize">{{ $purchase->status }}</td>
          <td class="border px-4 py-2 text-right">${{ number_format($purchase->total, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr class="bg-gray-200 font-semibold">
          <td colspan="4" class="px-4 py-2 text-right border">Total:</td>
          <td class="px-4 py-2 text-right border text-green-600 font-bold">
            ${{ number_format($purchases->sum('total'), 2) }}
          </td>
        </tr>
      </tfoot>
    </table>

    <!-- Action Buttons -->
    <div class="no-print mt-6 text-center space-x-4">
      <button onclick="window.history.back()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-5 rounded">
        Back
      </button>
      <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-5 rounded">
        Print 
      </button>
    </div>
  </div>

</body>
</html>
