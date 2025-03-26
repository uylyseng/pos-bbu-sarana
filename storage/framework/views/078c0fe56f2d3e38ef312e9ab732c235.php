<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Users List</a>
            </li>
        </ol>
    </nav>

    <!-- Add New User Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white">Users List</h2>
        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-success flex items-center shadow-md">
            <i class="fas fa-user-plus mr-2"></i>
            <span class="font-semibold">Add New</span>
        </a>
    </div>

    <!-- Success/Error Messages -->
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
    <?php if(session('error')): ?>
        <script>
            Swal.fire({
                title: "Error!",
                text: "<?php echo e(session('error')); ?>",
                icon: "error",
                confirmButtonText: "OK"
            });
        </script>
    <?php endif; ?>

    <!-- Responsive Users Table -->
    <div class="table-responsive overflow-x-auto shadow-lg rounded-lg p-4 bg-white dark:bg-gray-900">
        <table class="table table-hover shadow-md w-full">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b] h-12" style="color: blue;">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Gender</th>
                    <th>Profile</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $getRecord; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="align-middle"><?php echo e($user->id); ?></td>
                        <td class="align-middle"><?php echo e($user->name); ?></td>
                        <td class="align-middle"><?php echo e($user->email); ?></td>
                        <td class="align-middle"><?php echo e($user->role_name ?? 'No Role'); ?></td>
                        <td class="align-middle">
                            <span class="badge <?php echo e($user->status == 'active' ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo e(ucfirst($user->status)); ?>

                            </span>
                        </td>
                        <td class="align-middle"><?php echo e($user->gender ? ucfirst($user->gender) : '-'); ?></td>
                        <td class="align-middle">
                            <img src="<?php echo e($user->profile ? asset('storage/' . $user->profile) : asset('assets/images/default-user.png')); ?>"
                                alt="Profile" class="h-10 w-10 object-cover rounded-lg shadow-md">
                        </td>
                        <td class="align-middle">
                            <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700">
                            <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i> Edit
                            </a>
                            <button type="button" onclick="confirmDelete('<?php echo e(route('users.destroy', $user->id)); ?>')" class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700">
                            <i class="fa-solid fa-trash mr-1" style="color: red;"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination (if applicable) -->
    <?php if($getRecord->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($getRecord->links()); ?>

        </div>
    <?php endif; ?>
</div>

<!-- JavaScript for Delete Confirmation -->
<script>
    async function confirmDelete(deleteUrl) {
        const result = await Swal.fire({
            icon: 'warning',
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        });

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
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/users/list.blade.php ENDPATH**/ ?>