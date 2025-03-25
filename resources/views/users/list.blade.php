@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">Users List</a>
            </li>
        </ol>
    </nav>

    <!-- Add New User Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white">Users List</h2>
        <a href="{{ route('users.create') }}" class="btn btn-success flex items-center shadow-md">
            <i class="fas fa-user-plus mr-2"></i>
            <span class="font-semibold">Add New</span>
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
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
                    text: "{{ session('success') }}",
                    padding: '2em',
                });
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            Swal.fire({
                title: "Error!",
                text: "{{ session('error') }}",
                icon: "error",
                confirmButtonText: "OK"
            });
        </script>
    @endif

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
                @forelse ($getRecord as $user)
                    <tr>
                        <td class="align-middle">{{ $user->id }}</td>
                        <td class="align-middle">{{ $user->name }}</td>
                        <td class="align-middle">{{ $user->email }}</td>
                        <td class="align-middle">{{ $user->role_name ?? 'No Role' }}</td>
                        <td class="align-middle">
                            <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="align-middle">{{ $user->gender ? ucfirst($user->gender) : '-' }}</td>
                        <td class="align-middle">
                            <img src="{{ $user->profile ? asset('storage/' . $user->profile) : asset('assets/images/default-user.png') }}"
                                alt="Profile" class="h-10 w-10 object-cover rounded-lg shadow-md">
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center px-3 py-1 mr-2 border border-blue-500 text-blue-500 rounded hover:text-blue-700 hover:border-blue-700">
                            <i class="fa-solid fa-pen-to-square mr-1" style="color: blue;"></i> Edit
                            </a>
                            <button type="button" onclick="confirmDelete('{{ route('users.destroy', $user->id) }}')" class="inline-flex items-center px-3 py-1 border border-red-500 text-red-500 rounded hover:text-red-700 hover:border-red-700">
                            <i class="fa-solid fa-trash mr-1" style="color: red;"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination (if applicable) -->
    @if ($getRecord->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $getRecord->links() }}
        </div>
    @endif
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
            csrfToken.value = '{{ csrf_token() }}';

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
@endsection
