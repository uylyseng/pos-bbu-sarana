@extends('layouts.master')

@section('content')
<div class="container">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
    <ol class="flex items-center space-x-2 text-gray-500 font-semibold dark:text-white-dark">
        <li>
            <a href="{{ route('home') }}" class="hover:text-gray-700 dark:hover:text-white">Home</a>
        </li>
        <li class="flex items-center justify-center">
            <span class="text-gray-400 text-lg leading-none">•</span>
        </li>
        <li>
            <a href="{{ route('users.index') }}" class="hover:text-gray-700 dark:hover:text-white">User List</a>
        </li>
        <li class="flex items-center justify-center">
            <span class="text-gray-400 text-lg leading-none">•</span>
        </li>
        <li>
            <a href="javascript:;" class="text-primary font-bold">Add User</a>
        </li>
    </ol>
</nav>

    <!-- Page Content -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-lg p-4 bg-white dark:bg-gray-900 rounded-lg">
                <h4 class="text-xl font-semibold mb-4 dark:text-white">Add New User</h4>

                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Add User Form -->
                <form class="space-y-5" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Two-Column Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="block text-sm font-medium dark:text-white">Full Name</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}"
                                    class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                    placeholder="Enter Full Name" required />
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="block text-sm font-medium dark:text-white">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                    class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                    placeholder="Enter Email" required />
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="block text-sm font-medium dark:text-white">Password</label>
                                <input id="password" type="password" name="password"
                                    class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                    placeholder="Enter Password" required />
                                @error('password')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password Confirmation -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="block text-sm font-medium dark:text-white">Confirm Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                    placeholder="Confirm Password" required />
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <!-- Three-Column Row for Role, Status, Gender -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Role Selection -->
                                <div class="mb-2">
                                    <label for="roles_id" class="block text-sm font-medium dark:text-white">Role</label>
                                    <select id="roles_id" name="roles_id"
                                        class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700">
                                        <option value="" selected disabled>Select Role</option>
                                        @foreach ($getRole as $role)
                                            <option value="{{ $role->id }}" {{ old('roles_id') == $role->id ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('roles_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>


                                <!-- Gender -->
                                <div class="mb-3">
                                    <label for="gender" class="block text-sm font-medium dark:text-white">Gender</label>
                                    <select id="gender" name="gender"
                                        class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700">
                                        <option value="" selected disabled>Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <!-- Status -->
                                <div class="mb-3">
                                    <label class="block text-sm font-medium dark:text-white">Status</label>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="status" value="active" id="statusSwitch"
                                            class="form-checkbox h-5 w-5 text-blue-600 dark:bg-gray-800 dark:border-gray-700"
                                            {{ old('status') == 'active' ? 'checked' : '' }}>
                                        <label for="statusSwitch" class="ml-2 dark:text-white">Active</label>
                                    </div>
                                    @error('status')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>


                            <!-- Profile Picture (Full Width Below) -->
                            <div class="mb-3">
                                <label for="profile_picture" class="block text-sm font-medium dark:text-white">Profile Picture</label>
                                <img id="imagePreview" src="{{ asset('assets/images/default-user.png') }}"
                                    class="h-16 w-16 rounded-full object-cover border mb-2" alt="profile">
                                <input id="profile_picture" type="file" name="profile_picture" accept="image/*"
                                    class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                    onchange="previewImage(event)">
                                @error('profile_picture')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-2 mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary px-4 py-2 rounded-md dark:bg-gray-700 dark:text-white">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-md">Save</button>
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
@endsection
