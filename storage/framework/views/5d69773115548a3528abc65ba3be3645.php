<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8 bg-white shadow-md rounded-lg">
 <!-- Action Buttons -->
 <div class="mb-6 ">
    <a href="<?php echo e(url()->previous()); ?>" class="btn-back inline-block px-4 py-2 rounded hover:bg-gray-700">
        &#8592; Back
    </a>
    <a href="<?php echo e(route('reports.purchasepreviewprint', $purchase->id)); ?>" class="btn-print inline-block px-4 py-2 rounded hover:bg-blue-700">
        Print
    </a>
</div>

    <!-- Invoice Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-semibold">Purchase Invoice Preview</h2>
            <p class="text-sm text-gray-500">Date: <?php echo e(\Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y')); ?></p>
        </div>
        <h4 class="font-bold text-xl">Invoice #: <?php echo e($purchase->reference); ?></h4>
    </div>

    <!-- Supplier Information -->
    <div class="mb-6">
        <h5 class="font-bold text-lg mb-2">Supplier Information</h5>
        <p><strong>Name:</strong> <?php echo e($purchase->supplier->name ?? 'N/A'); ?></p>
        <p><strong>Address:</strong> <?php echo e($purchase->supplier->address ?? 'N/A'); ?></p>
        <p><strong>Contact:</strong> <?php echo e($purchase->supplier->contact_info ?? 'N/A'); ?></p>
    </div>

    <!-- Purchase Details -->
    <div class="mb-6">
        <h5 class="font-bold text-lg mb-2">Purchase Details</h5>
        <p><strong>Date:</strong> <?php echo e(\Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y')); ?></p>
        <p><strong>Status:</strong> <?php echo e(ucfirst($purchase->status)); ?></p>
        <p><strong>Payment Method:</strong> <?php echo e($purchase->paymentMethod->name ?? 'N/A'); ?></p>
        <p><strong>Details:</strong> <?php echo e($purchase->details ?? 'No additional details'); ?></p>
    </div>

    <!-- Purchase Items Table -->
    <div class="overflow-x-auto mb-6">
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2">S.NO</th>
                    <th class="px-4 py-2">Item</th>
                    <th class="px-4 py-2">Qty</th>
                    <th class="px-4 py-2">Unit Price</th>
                    <th class="px-4 py-2">Amount</th>
                </tr>
            </thead>
            <tbody class="bg-gray-100">
                <?php $__currentLoopData = $purchase->purchaseItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?php echo e($index + 1); ?></td>
                    <td class="px-4 py-2"><?php echo e($item->product->name_en ?? 'N/A'); ?></td>
                    <td class="px-4 py-2"><?php echo e($item->quantity); ?> <?php echo e($item->purchaseUnit->name ?? 'N/A'); ?></td>
                    <td class="px-4 py-2">$<?php echo e(number_format($item->unit_price, 2)); ?></td>
                    <td class="px-4 py-2">$<?php echo e(number_format($item->quantity * $item->unit_price, 2)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Total Section -->
    <div class="flex justify-end">
        <div class="w-1/2 bg-gray-100 p-4 rounded-lg">
            <div class="flex justify-between mb-2">
                <span class="font-bold">Subtotal:</span>
                <span>$<?php echo e(number_format($purchase->subtotal, 2)); ?></span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Discount:</span>
                <span>-$<?php echo e(number_format($purchase->discount, 2)); ?></span>
            </div>
            <div class="flex justify-between border-t pt-2 mt-2">
                <span class="font-bold text-xl">Grand Total:</span>
                <span class="text-xl font-semibold text-red-600">$<?php echo e(number_format($purchase->total, 2)); ?></span>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<style>
     /* Custom Button Styles */
  .btn-back {
      background-color: #6c757d; /* Gray background */
      color: white;
      padding: 0.5rem 1rem; /* Approximately 8px 16px */
      border-radius: 0.375rem; /* Rounded corners */
      text-decoration: none;
      transition: background-color 0.3s ease;
  }
  .btn-back:hover {
      background-color: #5a6268;
  }
  .btn-print {
      background-color: #007bff; /* Blue background */
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      text-decoration: none;
      transition: background-color 0.3s ease;
  }
  .btn-print:hover {
      background-color: #0056b3;
  }
    table th, table td { padding: 10px; text-align: left;  }
    table th { background-color: #007bff; color: white; }
</style>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/reports/purchasepreview.blade.php ENDPATH**/ ?>