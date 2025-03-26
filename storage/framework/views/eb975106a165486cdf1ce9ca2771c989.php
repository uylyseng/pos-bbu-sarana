<?php $__env->startSection('content'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Dangrek&display=swap');

    .font-dangrek {
        font-family: 'Dangrek', cursive !important;
    }

    /* Make Khmer text slightly larger for better readability */
    .khmer-text {
        font-size: 1.05em;
        line-height: 1.6;
    }
</style>

<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white">
            <li>
                <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-500/70 dark:hover:text-gray-300 <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>">
                    <?php echo e(__('expense_types.home')); ?>

                </a>
            </li>
            <li class="before:content-[''] before:block before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>">
                    <?php echo e(__('expense_types.expense_types')); ?>

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

    <!-- Add New Expense Type Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.expense_types_list')); ?></h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2" style="color: white;"></i>
            <span class="font-semibold text-white <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.add_new')); ?></span>
        </button>
    </div>

    <!-- Expense Types Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.id')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.expense_type_name')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.description')); ?></th>
                    <th class="px-4 py-2 text-center <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $expenseTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expenseType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 dark:text-white"><?php echo e($expenseType->id); ?></td>
                    <td class="px-4 py-2 dark:text-white"><?php echo e($expenseType->name); ?></td>
                    <td class="px-4 py-2 dark:text-white"><?php echo e($expenseType->description); ?></td>
                    <td class="px-4 py-2 text-center">
                        <button type="button" onclick="openEditModal(<?php echo e(json_encode($expenseType)); ?>)" class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700">
                            <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i>
                            <span class="<?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.edit')); ?></span>
                        </button>
                        <button type="button" onclick="confirmDelete('<?php echo e(route('expense_types.destroy', $expenseType->id)); ?>')" class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700">
                            <i class="fa-solid fa-trash mr-1" style="color: red;"></i>
                            <span class="<?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.delete')); ?></span>
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        <?php echo e($expenseTypes->links('layouts.pagination')); ?>

    </div>
</div>

<!-- Modal Backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
flex items-start justify-center transition-opacity duration-300">
    <!-- Create/Edit Expense Type Modal -->
    <div id="expenseTypeModal" class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12">
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.create_new_expense_type')); ?></h2>
        <form id="expenseTypeForm" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <!-- Expense Type Name Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.expense_type_name')); ?></label>
                <input type="text" name="name" id="expenseTypeName" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white <?php echo e(app()->getLocale() === 'km' ? 'khmer-text' : ''); ?>" required>
            </div>
            <!-- Description Field -->
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.description')); ?></label>
                <textarea name="description" id="expenseTypeDescription" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white <?php echo e(app()->getLocale() === 'km' ? 'khmer-text' : ''); ?>"></textarea>
            </div>
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>"><?php echo e(__('expense_types.cancel')); ?></button>
                <button type="submit" class="btn-green <?php echo e(app()->getLocale() === 'km' ? 'font-dangrek khmer-text' : ''); ?>" id="saveButton"><?php echo e(__('expense_types.save')); ?></button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
    function openCreateModal() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        const modal = document.getElementById('expenseTypeModal');
        modal.classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '<?php echo e(__("expense_types.create_new_expense_type")); ?>';
        document.getElementById('expenseTypeForm').action = "<?php echo e(route('expense_types.store')); ?>";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('expenseTypeName').value = '';
        document.getElementById('expenseTypeDescription').value = '';
        document.getElementById('saveButton').innerText = '<?php echo e(__("expense_types.save")); ?>';
    }

    function openEditModal(expenseType) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        const modal = document.getElementById('expenseTypeModal');
        modal.classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '<?php echo e(__("expense_types.edit_expense_type")); ?>';
        document.getElementById('expenseTypeForm').action = `/expense_types/${expenseType.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('expenseTypeName').value = expenseType.name;
        document.getElementById('expenseTypeDescription').value = expenseType.description;
        document.getElementById('saveButton').innerText = '<?php echo e(__("expense_types.update")); ?>';
    }

    function closeModal() {
        document.getElementById('expenseTypeModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }

    function confirmDelete(deleteUrl) {
        const isKhmer = '<?php echo e(app()->getLocale()); ?>' === 'km';
        const swalConfig = {
            title: '<?php echo e(__("expense_types.are_you_sure")); ?>',
            text: '<?php echo e(__("expense_types.this_action_cannot_be_undone")); ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<?php echo e(__("expense_types.yes_delete_it")); ?>',
            cancelButtonText: '<?php echo e(__("expense_types.cancel")); ?>',
            reverseButtons: true,
        };

        // Add custom class for Khmer text
        if (isKhmer) {
            swalConfig.customClass = {
                title: 'font-dangrek khmer-text',
                htmlContainer: 'font-dangrek khmer-text',
                confirmButton: 'font-dangrek khmer-text',
                cancelButton: 'font-dangrek khmer-text',
            };
        }

        window.Swal.fire(swalConfig).then((result) => {
            if (result.isConfirmed) {
                // Create a form dynamically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '<?php echo e(csrf_token()); ?>';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/expense_types/index.blade.php ENDPATH**/ ?>