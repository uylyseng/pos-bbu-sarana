@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white-dark">
            <li>
                <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">Home</a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">List Role</a>
            </li>
        </ol>
    </nav>

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

    <!-- Add New Category Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white">Roles List</h2>
        @if (!empty(($PermissionAdd)))
        <button class="btn-green flex items-center shadow-md ml-auto" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <a href="{{ route('roles.create') }}">
                <span class="font-semibold">Add New</span>
            </a>
        </button>
        @endif
    </div>

    <div class="table-responsive overflow-x-auto shadow-lg rounded-lg p-4 bg-white dark:bg-gray-900">
        <table class="table table-hover shadow-md w-full">
            <thead class="bg-gray-100 dark:bg-[#1b2e4b] h-12" style="color: blue;">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Role Name</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Permissions</th>
                    <th class="px-4 py-2">
                        @if (!empty(($PermissionEdit)) || !empty(($PermissionDelete)))
                        Actions
                        @endif
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($getRecord as $role)
                <tr>
                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2">{{ $role->name }}</td>
                    <td class="px-4 py-2">{{ $role->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-2 text-center ">{{ $role->permissions_count }}</td>
                    <td class="px-4 py-2 space-x-2">
                        @if (!empty(($PermissionEdit)))
                        <a href="{{ route('roles.edit', $role->id) }}" class="text-blue-500 hover:text-blue-700" title="Edit">
                        <i class="fa-solid fa-sliders text-lg text-blue-500"style="color: blue;"></i>

                        </a>
                        @endif
                        @if (!empty(($PermissionDelete)))
                        <a href="{{ route('roles.destroy', $role->id) }}" class="text-blue-500 hover:text-blue-700" title="Delete">
                            <i class="fa-solid fa-trash text-lg" style="color: red;"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


