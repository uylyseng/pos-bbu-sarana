<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toast = window.Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                padding: '2em',
            });

            toast.fire({
                icon: 'success',
                text: "<?php echo e(session('success')); ?>",
                padding: '2em',
            });
        });
    </script>
<?php endif; ?>

<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70 dongrek-font"><?php echo e(__('currencies.home')); ?></a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary dongrek-font"><?php echo e(__('currencies.currencies')); ?></a>
            </li>
        </ol>
    </nav>


    <!-- Add New Currency Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 ">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white dongrek-font"><?php echo e(__('currencies.currencies')); ?></h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold dongrek-font"><?php echo e(__('currencies.add_new')); ?></span>
        </button>
    </div>

    <!-- Currencies Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr>
                    <th class="px-4 py-2 dongrek-font"><?php echo e(__('currencies.id')); ?></th>
                    <th class="px-4 py-2 dongrek-font"><?php echo e(__('currencies.code')); ?></th>
                    <th class="px-4 py-2 dongrek-font"><?php echo e(__('currencies.name')); ?></th>
                    <th class="px-4 py-2 dongrek-font"><?php echo e(__('currencies.symbol')); ?></th>
                    <th class="px-4 py-2 dongrek-font"><?php echo e(__('currencies.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-2"><?php echo e($currency->id); ?></td>
                    <td class="px-4 py-2"><?php echo e($currency->code); ?></td>
                    <td class="px-4 py-2"><?php echo e($currency->name); ?></td>
                    <td class="px-4 py-2"><?php echo e($currency->symbol); ?></td>
                    <td class="px-4 py-2 text-center dongrek-font">
                        <button class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded mr-2" onclick="openEditModal(<?php echo e(json_encode($currency)); ?>)">
                            <i class="fa-solid fa-pen-to-square" style="color: blue;"></i> Edit
                        </button>
                        <button class="text-red-500 hover:text-red-700 px-3 py-1 border border-red-500 rounded" onclick="confirmDelete('<?php echo e(route('currencies.destroy', $currency->id)); ?>')">
                            <i class="fa-solid fa-trash" style="color: red;"></i> Delete
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        <?php echo e($currencies->links()); ?>

    </div>
</div>

<!-- MODAL BACKDROP (Blur Effect) -->
<div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-start justify-center transition-opacity duration-300">
    <!-- Create/Edit Currency Modal -->
    <div id="currencyModal" class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg transform transition-all duration-300 ease-out opacity-0 -translate-y-12">
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white dongrek-font"><?php echo e(__('currencies.create_new_currency')); ?></h2>
        <form id="currencyForm" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('currencies.currency_code')); ?></label>
                <input type="text" name="code" id="currencyCode" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('currencies.currency_name')); ?></label>
                <input type="text" name="name" id="currencyName" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('currencies.symbol')); ?></label>
                <input type="text" name="symbol" id="currencySymbol" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray dongrek-font"><?php echo e(__('currencies.cancel')); ?></button>
                <button type="submit" class="btn-green dongrek-font" id="saveButton"><?php echo e(__('currencies.save')); ?></button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
    function openCreateModal() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('currencyModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '<?php echo e(__('currencies.create_new_currency')); ?>';
        document.getElementById('currencyForm').action = "<?php echo e(route('currencies.store')); ?>";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('currencyCode').value = '';
        document.getElementById('currencyName').value = '';
        document.getElementById('currencySymbol').value = '';
        document.getElementById('saveButton').innerText = '<?php echo e(__('currencies.save')); ?>';
    }

    function openEditModal(currency) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('currencyModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '<?php echo e(__('currencies.edit_currency')); ?>';
        document.getElementById('currencyForm').action = `/currencies/${currency.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('currencyCode').value = currency.code;
        document.getElementById('currencyName').value = currency.name;
        document.getElementById('currencySymbol').value = currency.symbol;
        document.getElementById('saveButton').innerText = '<?php echo e(__('currencies.update')); ?>';
    }

    function closeModal() {
        document.getElementById('currencyModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/currencies/index.blade.php ENDPATH**/ ?>