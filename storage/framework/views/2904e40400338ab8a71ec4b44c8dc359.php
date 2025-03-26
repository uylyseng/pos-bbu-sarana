<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6 <?php echo e(app()->getLocale() == 'km' ? 'khmer-dangrek' : ''); ?>">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="javascript:;" class="hover:text-gray-500/70 dark:hover:text-white-dark/70"><?php echo e(__('customers.home')); ?></a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary"><?php echo e(__('customers.customers')); ?></a>
            </li>
        </ol>
    </nav>

    <!-- Add New Customer Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white"><?php echo e(__('customers.customers_list')); ?></h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold"><?php echo e(__('customers.add_new')); ?></span>
        </button>
    </div>

    <!-- Customers Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm table-auto">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2 dark:text-white"><?php echo e(__('customers.id')); ?></th>
                    <th class="px-4 py-2 dark:text-white"><?php echo e(__('customers.name')); ?></th>
                    <th class="px-4 py-2 dark:text-white"><?php echo e(__('customers.contact_info')); ?></th>
                    <!-- Column for Assigned Coupons -->
                    <th class="px-4 py-2 dark:text-white"><?php echo e(__('customers.coupons')); ?></th>
                    <th class="px-4 py-2 dark:text-white"><?php echo e(__('customers.actions')); ?></th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 dark:text-gray-100"><?php echo e($customer->id); ?></td>
                    <td class="px-4 py-2 dark:text-gray-100"><?php echo e($customer->name); ?></td>
                    <td class="px-4 py-2 dark:text-gray-100"><?php echo e($customer->contact_info); ?></td>
                    <!-- Display the customer's assigned coupons as comma-separated codes -->
                    <td class="px-4 py-2 dark:text-gray-100">
                        <?php if($customer->coupons->count() > 0): ?>
                            <?php echo e($customer->coupons->pluck('code')->join(', ')); ?>

                        <?php else: ?>
                            <span class="text-gray-500 dark:text-gray-400"><?php echo e(__('customers.no_coupons')); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <!-- Manage Coupons Button -->
                        <button
                            type="button"
                            onclick="window.location.href='<?php echo e(route('customers.chooseCoupon', $customer->id)); ?>'"
                            class="inline-flex items-center px-3 py-1 border border-green-500 text-green-500 rounded hover:text-green-700 hover:border-green-700"
                        >
                            <i class="fa-solid fa-cog mr-1" style="color: green;"></i> <?php echo e(__('customers.manage')); ?>

                        </button>

                        <!-- Edit Customer Button -->
                        <button class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded mr-2"
                                onclick="openEditModal(<?php echo e(json_encode($customer)); ?>)">
                            <i class="fa-solid fa-pen-to-square" style="color: blue;"></i> <?php echo e(__('customers.edit')); ?>

                        </button>

                        <!-- Delete Customer Button -->
                        <button class="text-red-500 hover:text-red-700 px-3 py-1 border border-red-500 rounded"
                                onclick="confirmDelete('<?php echo e(route('customers.destroy', $customer->id)); ?>')">
                            <i class="fa-solid fa-trash" style="color: red;"></i> <?php echo e(__('customers.delete')); ?>

                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div >

    <?php if($customers->total() > 0): ?>
<div >
    <?php echo e($customers->links('layouts.pagination')); ?>

</div>
<?php else: ?>
    <p><?php echo e(__('customers.no_customers')); ?></p>
<?php endif; ?>
</div>

<!-- MODAL BACKDROP for Create/Edit Customer -->
<div id="modalBackdrop" c class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
flex items-start justify-center transition-opacity duration-300">
    <!-- Create/Edit Customer Modal -->
    <div id="customerModal"  class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12"
>
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white"><?php echo e(__('customers.create_new_customer')); ?></h2>
        <form id="customerForm" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white"><?php echo e(__('customers.customer_name')); ?></label>
                <input type="text" name="name" id="customerName" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white"><?php echo e(__('customers.contact_info')); ?></label>
                <textarea name="contact_info" id="customerContactInfo" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray"><?php echo e(__('customers.cancel')); ?></button>
                <button type="submit" class="btn-green"><?php echo e(__('customers.save')); ?></button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
    function openCreateModal() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('customerModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = "<?php echo e(__('customers.create_new_customer')); ?>";
        document.getElementById('customerForm').action = "<?php echo e(route('customers.store')); ?>";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('customerName').value = '';
        document.getElementById('customerContactInfo').value = '';
    }

    function openEditModal(customer) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('customerModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = "<?php echo e(__('customers.edit_customer')); ?>";
        document.getElementById('customerForm').action = `/customers/${customer.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('customerName').value = customer.name;
        document.getElementById('customerContactInfo').value = customer.contact_info;
    }

    function closeModal() {
        document.getElementById('customerModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }

    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: "<?php echo e(__('customers.are_you_sure')); ?>",
            text: "<?php echo e(__('customers.this_action_cannot_be_undone')); ?>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: "<?php echo e(__('customers.yes_delete_it')); ?>",
            cancelButtonText: "<?php echo e(__('customers.cancel')); ?>"
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/customers/index.blade.php ENDPATH**/ ?>