<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
    <ol class="flex items-center space-x-2 text-gray-500 font-semibold dark:text-white-dark">
        <li>
            <a href="<?php echo e(route('home')); ?>" class="hover:text-gray-700 dark:hover:text-white">Home</a>
        </li>
        <li class="flex items-center justify-center">
            <span class="text-gray-400 text-lg leading-none">•</span>
        </li>
        <li>
            <a href="<?php echo e(route('users.index')); ?>" class="hover:text-gray-700 dark:hover:text-white">User List</a>
        </li>
        <li class="flex items-center justify-center">
            <span class="text-gray-400 text-lg leading-none">•</span>
        </li>
        <li>
            <a href="javascript:;" class="text-primary font-bold">Edit User</a>
        </li>
    </ol>
</nav>
    <!-- Page Content -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-lg p-4 bg-white dark:bg-gray-900 rounded-lg">
                <h4 class="text-xl font-semibold mb-4 dark:text-white">Edit User</h4>

                <!-- Success/Error Messages -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <!-- Edit User Form -->
                <form class="space-y-5" action="<?php echo e(route('users.update', $getRecord->id)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <!-- Two-Column Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="block text-sm font-medium dark:text-white">Full Name</label>
                                <input id="name" type="text" name="name" value="<?php echo e(old('name', $getRecord->name)); ?>"
                                    class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                    placeholder="Enter Full Name" required />
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="block text-sm font-medium dark:text-white">Email</label>
                                <input id="email" type="email" name="email" value="<?php echo e(old('email', $getRecord->email)); ?>"
                                    class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                    placeholder="Enter Email" required />
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-red-500 font-bold text-sm"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="block text-sm font-medium dark:text-white">Password <span class="text-gray-500">(Leave blank to keep unchanged)</span></label>
                                <input id="password" type="password" name="password"
                                    class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                    placeholder="Enter New Password" />
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Password Confirmation -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="block text-sm font-medium dark:text-white">Confirm Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                    placeholder="Confirm New Password" />
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <!-- Three-Column Row for Role, Status, Gender -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Role Selection -->
                                <div class="mb-3">
                                    <label for="roles_id" class="block text-sm font-medium dark:text-white">Role</label>
                                    <select id="roles_id" name="roles_id"
                                        class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700">
                                        <option value="" <?php echo e(old('roles_id', $getRecord->roles_id) ? '' : 'selected'); ?> disabled>Select Role</option>
                                        <?php $__currentLoopData = $getRole; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->id); ?>" <?php echo e(old('roles_id', $getRecord->roles_id) == $role->id ? 'selected' : ''); ?>>
                                                <?php echo e(ucfirst($role->name)); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['roles_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Gender -->
                                <div class="mb-3">
                                    <label for="gender" class="block text-sm font-medium dark:text-white">Gender</label>
                                    <select id="gender" name="gender"
                                        class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700">
                                        <option value="" <?php echo e(old('gender', $getRecord->gender) ? '' : 'selected'); ?> disabled>Select Gender</option>
                                        <option value="male" <?php echo e(old('gender', $getRecord->gender) == 'male' ? 'selected' : ''); ?>>Male</option>
                                        <option value="female" <?php echo e(old('gender', $getRecord->gender) == 'female' ? 'selected' : ''); ?>>Female</option>
                                    </select>
                                    <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="block text-sm font-medium dark:text-white">Status</label>
                                <div class="flex items-center">
                                    <input type="checkbox" name="status" value="active" id="statusSwitch"
                                        class="form-checkbox h-5 w-5 text-blue-600 dark:bg-gray-800 dark:border-gray-700"
                                        <?php echo e(old('status', $getRecord->status) == 'active' ? 'checked' : ''); ?>>
                                    <label for="statusSwitch" class="ml-2 dark:text-white">Active</label>
                                </div>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Profile Picture (Full Width Below) -->
                            <div class="mb-3">
                                <label for="profile_picture" class="block text-sm font-medium dark:text-white">Profile Picture</label>
                                <img id="imagePreview" src="<?php echo e($getRecord->profile ? asset('storage/' . $getRecord->profile) : asset('assets/images/default-user.png')); ?>"
                                    class="h-16 w-16 rounded-full object-cover border mb-2" alt="Profile Preview">
                                <input id="profile_picture" type="file" name="profile_picture" accept="image/*"
                                    class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                    onchange="previewImage(event)">
                                <?php $__errorArgs = ['profile_picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-2 mt-4">
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary px-4 py-2 rounded-md dark:bg-gray-700 dark:text-white">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-md">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Image Preview -->
<script>
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/users/edit.blade.php ENDPATH**/ ?>