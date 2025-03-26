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
        <ol class="flex text-gray-500 font-semibold dark:text-white dongrek-font">
            <li>
                <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-500/70 dark:hover:text-white-dark/70 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('toppings.home')); ?></a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh'); ?>"><?php echo e(__('toppings.toppings')); ?></a>
            </li>
        </ol>
    </nav>

    <!-- Add New Topping Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.toppings_list')); ?></h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.add_new')); ?></span>
        </button>
    </div>

    <!-- Toppings Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
    <table class="w-full whitespace-nowrap shadow-sm">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b]" style="color: blue;">
                <tr>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.id')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.name')); ?></th>
                    <th class="px-4 py-2 <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.description')); ?></th>
                    <th class="px-4 py-2 text-center <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $toppings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-2"><?php echo e($topping->id); ?></td>
                    <td class="px-4 py-2"><?php echo e($topping->name); ?></td>
                    <td class="px-4 py-2"><?php echo e($topping->descript); ?></td>
                    <td class="px-4 py-2 text-center">
                        <button class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded mr-2" onclick="openEditModal(<?php echo e(json_encode($topping)); ?>)">
                            <i class="fa-solid fa-pen-to-square" style="color: blue;"></i>
                            <span class="<?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.edit')); ?></span>
                        </button>
                        <button class="text-red-500 hover:text-red-700 px-3 py-1 border border-red-500 rounded" onclick="confirmDelete('<?php echo e(route('toppings.destroy', $topping->id)); ?>')">
                            <i class="fa-solid fa-trash" style="color: red;"></i>
                            <span class="<?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.delete')); ?></span>
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($toppings->total() > 0): ?>
    <div>
        <?php echo e($toppings->links('layouts.pagination')); ?>

    </div>
    <?php else: ?>
        <p class="<?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.no_toppings_available')); ?></p>
    <?php endif; ?>
</div>

<!-- MODAL BACKDROP (Blur Effect) -->
<div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
         flex items-start justify-center transition-opacity duration-300">
    <!-- Create/Edit Topping Modal -->
    <div id="toppingModal" class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12">
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.create_new_topping')); ?></h2>
        <form id="toppingForm" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.topping_name')); ?></label>
                <input type="text" name="name" id="toppingName" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.description')); ?></label>
                <textarea name="descript" id="toppingDescription" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>"><?php echo e(__('toppings.cancel')); ?></button>
                <button type="submit" class="btn-green <?php echo e(app()->getLocale() == 'en' ? 'font-en' : 'font-kh dongrek-font'); ?>" id="saveButton"><?php echo e(__('toppings.save')); ?></button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
    const translations = {
        create_new_topping: "<?php echo e(__('toppings.create_new_topping')); ?>",
        edit_topping: "<?php echo e(__('toppings.edit_topping')); ?>",
        save: "<?php echo e(__('toppings.save')); ?>",
        update: "<?php echo e(__('toppings.update')); ?>",
        are_you_sure: "<?php echo e(__('toppings.are_you_sure')); ?>",
        this_action_cannot_be_undone: "<?php echo e(__('toppings.this_action_cannot_be_undone')); ?>",
        yes_delete_it: "<?php echo e(__('toppings.yes_delete_it')); ?>"
    };

    function openCreateModal() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('toppingModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = translations.create_new_topping;
        document.getElementById('toppingForm').action = "<?php echo e(route('toppings.store')); ?>";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('toppingName').value = '';
        document.getElementById('toppingDescription').value = '';
        document.getElementById('saveButton').innerText = translations.save;
    }

    function openEditModal(topping) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('toppingModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = translations.edit_topping;
        document.getElementById('toppingForm').action = `/toppings/${topping.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('toppingName').value = topping.name;
        document.getElementById('toppingDescription').value = topping.descript;
        document.getElementById('saveButton').innerText = translations.update;
    }

    function closeModal() {
        document.getElementById('toppingModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }

    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: translations.are_you_sure,
            text: translations.this_action_cannot_be_undone,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: translations.yes_delete_it
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '<?php echo e(csrf_token()); ?>';
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/toppings/index.blade.php ENDPATH**/ ?>