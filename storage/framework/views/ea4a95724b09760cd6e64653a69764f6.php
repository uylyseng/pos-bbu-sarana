<?php $__env->startSection('content'); ?>
<style>
    /* Dongrek font for Khmer text */
    @font-face {
        font-family: 'Dongrek';
        src: url('https://fonts.googleapis.com/css2?family=Dangrek&display=swap');
    }

    .khmer-font {
        font-family: 'Dongrek', 'Khmer OS', sans-serif;
    }
</style>

<div class="container mx-auto px-6 py-8">
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li><a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70 khmer-font"><?php echo e(__('expense_reports.home')); ?></a></li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary khmer-font"><?php echo e(__('expense_reports.expense_report')); ?></a>
            </li>
        </ol>
    </nav>

    <h2 class="text-2xl font-bold text-gray-800 mb-4 khmer-font"><?php echo e(__('expense_reports.expense_report')); ?></h2>

    <!-- Filter Form -->
    <form action="<?php echo e(route('reports.expenses')); ?>" method="GET" class="mb-4">
        <div class="flex space-x-4">
            <input type="date" name="start_date" value="<?php echo e(request('start_date', $startDate->toDateString())); ?>"
                class="form-input h-9">
            <input type="date" name="end_date" value="<?php echo e(request('end_date', $endDate->toDateString())); ?>"
                class="form-input h-9">
            <!-- User Filter -->
            <select name="user_id" class="input h-9">
                <option value="" class="khmer-font"><?php echo e(__('expense_reports.all_users')); ?></option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                    <?php echo e($user->name); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <!-- Payment Method Filter -->
            <select name="payment_method_id" class="input h-9">
                <option value="" class="khmer-font"><?php echo e(__('expense_reports.all_methods')); ?></option>
                <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($method->id); ?>" <?php echo e(request('payment_method_id') == $method->id ? 'selected' : ''); ?>>
                    <?php echo e($method->name); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <button type="submit" class="btn-blue px-6 py-2 rounded text-white khmer-font"><?php echo e(__('expense_reports.submit')); ?></button>
            <a href="<?php echo e(route('reports.print_expenses', [
            'start_date' => request('start_date', $startDate->toDateString()),
            'end_date' => request('end_date', $endDate->toDateString()),
            'user_id' => request('user_id'),
            'payment_method_id' => request('payment_method_id'),
        ])); ?>" class="btn-orange px-6 py-2 rounded text-white khmer-font"><?php echo e(__('expense_reports.print')); ?></a>
        </div>
    </form>

    <!-- Data Table -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="w-full border border-gray-200">
            <thead class="bg-green-700 text-white">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold khmer-font"><?php echo e(__('expense_reports.id')); ?></th>
                    <th class="px-6 py-3 text-left font-semibold khmer-font"><?php echo e(__('expense_reports.date')); ?></th>
                    <th class="px-6 py-3 text-left font-semibold khmer-font"><?php echo e(__('expense_reports.reference')); ?></th>
                    <th class="px-6 py-3 text-left font-semibold khmer-font"><?php echo e(__('expense_reports.created_by')); ?></th>
                    <th class="px-6 py-3 text-left font-semibold khmer-font"><?php echo e(__('expense_reports.description')); ?></th>
                    <th class="px-6 py-3 text-left font-semibold khmer-font"><?php echo e(__('expense_reports.payment_method')); ?></th>
                    <th class="px-6 py-3 text-right font-semibold khmer-font"><?php echo e(__('expense_reports.amount')); ?></th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="<?php echo e($index % 2 == 0 ? 'bg-gray-50' : 'bg-gray-100'); ?> border-b hover:bg-gray-200">
                    <td class="px-6 py-3"><?php echo e($loop->iteration); ?></td>
                    <td class="px-6 py-3"><?php echo e(\Carbon\Carbon::parse($expense->date)->format('d-m-Y')); ?></td>
                    <td class="px-6 py-3"><?php echo e($expense->reference); ?></td>
                    <td class="px-6 py-3"><?php echo e($expense->created_by); ?></td>
                    <td class="px-6 py-3"><?php echo e($expense->description); ?></td>
                    <td class="px-6 py-3"><?php echo e($expense->payment_method); ?></td>
                    <td class="px-6 py-3 text-center">$<?php echo e(number_format($expense->total_amount, 2)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot class="text-gray-900 font-semibold">
                <tr>
                    <td colspan="6" class="px-6 py-3 text-left khmer-font">📌 <?php echo e(__('expense_reports.total')); ?></td>
                    <td class="px-6 py-3 text-left">
                        $<?php echo e(number_format($expenses->sum('total_amount'), 2)); ?>

                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Pagination & Per-Page -->
    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center">
        <?php if($expenses->total() > 0): ?>
        <!-- Per-Page Dropdown -->
        <div class="flex items-center">
            <span class="mr-2 khmer-font"><?php echo e(__('expense_reports.per_page')); ?>:</span>
            <select id="per_page" class="input"
                onchange="window.location.href='<?php echo e($expenses->url(1)); ?>&per_page=' + this.value">
                <option value="2" <?php echo e(request('per_page') == 2  ? 'selected' : ''); ?>>2</option>
                <option value="4" <?php echo e(request('per_page') == 4  ? 'selected' : ''); ?>>4</option>
                <option value="5" <?php echo e(request('per_page') == 5  ? 'selected' : ''); ?>>5</option>
                <option value="10" <?php echo e(request('per_page') == 10 ? 'selected' : ''); ?>>10</option>
                <option value="20" <?php echo e(request('per_page') == 20 ? 'selected' : ''); ?>>20</option>
            </select>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if($expenses->onFirstPage()): ?>
            <span class="btn-disabled">←</span>
            <?php else: ?>
            <a href="<?php echo e($expenses->previousPageUrl()); ?>&per_page=<?php echo e(request('per_page')); ?>" class="btn-pagination">←</a>
            <?php endif; ?>

            <span class="btn-page"><?php echo e($expenses->currentPage()); ?></span>

            <?php if($expenses->hasMorePages()): ?>
            <a href="<?php echo e($expenses->nextPageUrl()); ?>&per_page=<?php echo e(request('per_page')); ?>" class="btn-pagination">→</a>
            <?php else: ?>
            <span class="btn-disabled">→</span>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <p class="text-center text-gray-600 dark:text-gray-300 khmer-font"><?php echo e(__('expense_reports.no_expenses')); ?></p>
        <?php endif; ?>
    </div>
</div>

<style>
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

th,
td {
    text-align: left;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

th {
    background: #3b82f2;
    /* Blue gradient */
    color: white;
    padding: 16px;
    font-weight: 600;
    text-align: left;
}

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/reports/expenses.blade.php ENDPATH**/ ?>