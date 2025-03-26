<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex">
        <ol class="flex text-gray-500 font-semibold dark:text-white">
            <li>
                <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-500/70 dark:hover:text-gray-300 dongrek-font">
                    <?php echo e(__('suppliers.home')); ?>

                </a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary dongrek-font">
                    <?php echo e(__('suppliers.suppliers')); ?>

                </a>
            </li>
        </ol>
    </nav>



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

    <!-- Add New Supplier Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white dongrek-font"><?php echo e(__('suppliers.suppliers_list')); ?></h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2" style="color: white;"></i>
            <span class="font-semibold text-white dongrek-font"><?php echo e(__('suppliers.add_new')); ?></span>
        </button>
    </div>

    <!-- Suppliers Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr>
                    <th class="px-4 py-2 dongrek-font"><?php echo e(__('suppliers.id')); ?></th>
                    <th class="px-4 py-2 dongrek-font"><?php echo e(__('suppliers.supplier_name')); ?></th>
                    <th class="px-4 py-2 dongrek-font"><?php echo e(__('suppliers.contact_info')); ?></th>
                    <th class="px-4 py-2 dongrek-font"><?php echo e(__('suppliers.address')); ?></th>
                    <th class="px-4 py-2 text-center dongrek-font"><?php echo e(__('suppliers.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-2 dark:text-white"><?php echo e($supplier->id); ?></td>
                    <td class="px-4 py-2 dark:text-white"><?php echo e($supplier->name); ?></td>
                    <td class="px-4 py-2 dark:text-white"><?php echo e($supplier->contact_info); ?></td>
                    <td class="px-4 py-2 dark:text-white"><?php echo e($supplier->address); ?></td>
                    <td class="px-4 py-2 text-center">
                        <button type="button" onclick="openEditModal(<?php echo e(json_encode($supplier)); ?>)"class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded mr-2">
                        <i class="fa-solid fa-pen-to-square"  style="color: blue;"></i> <span class="dongrek-font"><?php echo e(__('suppliers.edit')); ?></span>
                        </button>
                        <button type="button" onclick="confirmDelete('<?php echo e(route('suppliers.destroy', $supplier->id)); ?>')"  class="text-red-500 hover:text-red-700 px-3 py-1 border border-red-500 rounded" >
                        <i class="fa-solid fa-trash"  style="color: red;"></i> <span class="dongrek-font"><?php echo e(__('suppliers.delete')); ?></span>
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($suppliers->total() > 0): ?>
    <div>
        <?php echo e($suppliers->links('layouts.pagination')); ?>

    </div>
    <?php else: ?>
        <p class="dongrek-font"><?php echo e(__('suppliers.no_suppliers_available')); ?></p>
    <?php endif; ?>
</div>

<!-- Modal Backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
         flex items-start justify-center transition-opacity duration-300"
>
    <!-- Create/Edit Supplier Modal -->
    <div id="supplierModal" class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12"
>
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white dongrek-font"><?php echo e(__('suppliers.create_new_supplier')); ?></h2>
        <form id="supplierForm" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <!-- Supplier Name Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('suppliers.supplier_name')); ?></label>
                <input type="text" name="name" id="supplierName" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" required>
            </div>
            <!-- Contact Info Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('suppliers.contact_info')); ?></label>
                <textarea name="contact_info" id="supplierContact" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" rows="2"></textarea>
            </div>
            <!-- Address Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white dongrek-font"><?php echo e(__('suppliers.address')); ?></label>
                <textarea name="address" id="supplierAddress" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" rows="2"></textarea>
            </div>
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray dongrek-font"><?php echo e(__('suppliers.cancel')); ?></button>
                <button type="submit" class="btn-green dongrek-font" id="saveButton"><?php echo e(__('suppliers.save')); ?></button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
    function openCreateModal() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        const modal = document.getElementById('supplierModal');
        modal.classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '<?php echo e(__('suppliers.create_new_supplier')); ?>';
        document.getElementById('supplierForm').action = "<?php echo e(route('suppliers.store')); ?>";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('supplierName').value = '';
        document.getElementById('supplierContact').value = '';
        document.getElementById('supplierAddress').value = '';
        document.getElementById('saveButton').innerText = '<?php echo e(__('suppliers.save')); ?>';
    }

    function openEditModal(supplier) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        const modal = document.getElementById('supplierModal');
        modal.classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '<?php echo e(__('suppliers.edit_supplier')); ?>';
        document.getElementById('supplierForm').action = `/suppliers/${supplier.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('supplierName').value = supplier.name;
        document.getElementById('supplierContact').value = supplier.contact_info;
        document.getElementById('supplierAddress').value = supplier.address;
        document.getElementById('saveButton').innerText = '<?php echo e(__('suppliers.update')); ?>';
    }

    function closeModal() {
        document.getElementById('supplierModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/suppliers/index.blade.php ENDPATH**/ ?>