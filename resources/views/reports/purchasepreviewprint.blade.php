<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purchase Preview Print</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      .no-print {
        display: none;
      }
      /* Force header colors to print */
      thead {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      /* Ensure the table footer appears only at the last printed page */
      tfoot {
        display: table-row-group;
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
<body class="bg-gray-50 text-gray-800">
  <div class="print-container max-w-3xl mx-auto p-6 bg-white shadow rounded">
    <!-- Header Section -->
    <div class="text-center mb-6">
      <h2 class="text-2xl font-bold">Purchase Item Report</h2>
      <p class="text-sm"><strong>Reference:</strong> {{ $purchase->reference }}</p>
      <p class="text-sm"><strong>Date:</strong> {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('F d, Y') }}</p>
    </div>

    <!-- Supplier Details -->
    <div class="mb-6">
      <h3 class="text-lg font-semibold mb-2">Supplier Details</h3>
      <p class="text-sm"><strong>Name:</strong> {{ optional($purchase->supplier)->name ?? 'N/A' }}</p>
      <p class="text-sm"><strong>Email:</strong> {{ optional($purchase->supplier)->email ?? 'N/A' }}</p>
      <p class="text-sm"><strong>Phone:</strong> {{ optional($purchase->supplier)->phone ?? 'N/A' }}</p>
    </div>

    <!-- Purchase Items Table -->
    <div class="mb-6">
      <h3 class="text-lg font-semibold mb-2">Purchase Items</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-300">
          <thead class="bg-blue-500 text-white">
            <tr>
              <th class="px-4 py-2">#</th>
              <th class="px-4 py-2">Product</th>
              <th class="px-4 py-2">Quantity</th>
              <th class="px-4 py-2">Unit Price</th>
              <th class="px-4 py-2">Total Price</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($purchase->purchaseItems as $index => $item)
              <tr class="border-b">
                <td class="px-4 py-2">{{ $index + 1 }}</td>
                <td class="px-4 py-2">{{ optional($item->product)->name ?? 'N/A' }}</td>
                <td class="px-4 py-2">{{ $item->quantity }}</td>
                <td class="px-4 py-2">${{ number_format($item->unit_price, 2) }}</td>
                <td class="px-4 py-2">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot class="bg-gray-100">
            <tr>
              <td colspan="4" class="px-4 py-2 text-right font-semibold">Total:</td>
              <td class="px-4 py-2">
                ${{ number_format($purchase->purchaseItems->sum(function($item) {
                  return $item->quantity * $item->unit_price;
                }), 2) }}
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Footer Section -->
    <div class="text-center mt-6">

    </div>
  </div>

  <!-- Print Button (Screen Only) -->
  <div class="no-print text-center mt-4 flex justify-center space-x-4">
  <button onclick="window.history.back()" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
 Back
  </button>
  <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
    Print
  </button>
</div>

</body>
</html>
