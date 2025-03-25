<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Top Selling Products</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      .no-print {
        display: none;
      }
      /* Ensure the table footer appears only at the last page */
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
<body class="bg-gray-100 text-gray-800 font-sans">
  <div class="print-container max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6 border mt-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-4">
      <!-- Logo -->
      <div class="w-20">
        <img src="{{ asset('storage/' . $store->image) }}" alt="Store Logo" class="w-20 h-20 object-contain">
      </div>
      <!-- Title -->
      <div class="flex-1 text-center">
        <h2 class="text-2xl font-bold text-green-600">Top Selling Products Report</h2>
      </div>
      <!-- Spacing (empty div) -->
      <div class="w-20"></div>
    </div>

    <!-- Date Range -->
    <p class="text-center text-sm text-gray-600 mb-4">
      <strong>From:</strong> {{ $startDate->format('d-m-Y') }} &nbsp;
      <strong>To:</strong> {{ $endDate->format('d-m-Y') }}
    </p>

    <!-- Data Table -->
    <table class="w-full border border-gray-300 mt-4 text-sm">
      <thead class="bg-blue-400 text-white">
        <tr>
          <th class="border px-4 py-2">#</th>
          <th class="border px-4 py-2">Product</th>
          <th class="border px-4 py-2">Total Quantity</th>
          <th class="border px-4 py-2">Total Sales ($)</th>
        </tr>
      </thead>
      <tbody>
        @php
          $totalQuantity = 0;
          $totalSales = 0;
        @endphp
        @foreach($topSellingProducts as $index => $product)
          @php
            $totalQuantity += $product->total_quantity;
            $totalSales += $product->total_sales;
          @endphp
          <tr class="hover:bg-gray-50">
            <td class="border px-4 py-2 ">{{ $index + 1 }}</td>
            <td class="border px-4 py-2 ">{{ $product->name_en }}</td>
            <td class="border px-4 py-2 text-center">{{ $product->total_quantity }}</td>
            <td class="border px-4 py-2 text-center">{{ number_format($product->total_sales, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot class="bg-gray-200 font-semibold">
        <tr>
          <td colspan="2" class="border px-4 py-2 ">Total:</td>
          <td class="border px-4 py-2 font-bold text-green-500 text-center">{{ $totalQuantity }}</td>
          <td class="border px-4 py-2 font-bold  text-green-500 text-center">${{ number_format($totalSales, 2) }}</td>
        </tr>
      </tfoot>
    </table>

    <!-- Action Buttons -->
    <div class="no-print mt-6 text-center space-x-4">
      <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-5 rounded">
        Print
      </button>
      <a href="{{ route('reports.topsellingproduct') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-5 rounded inline-block">
        Back
      </a>
    </div>
  </div>
</body>
</html>
