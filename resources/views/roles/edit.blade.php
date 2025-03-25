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
            <a href="{{ route('roles.list') }}" class="hover:text-gray-700 dark:hover:text-white">List Role</a>
        </li>
        <li class="flex items-center justify-center">
            <span class="text-gray-400 text-lg leading-none">•</span>
        </li>
        <li>
            <a href="javascript:;" class="text-primary font-bold">Edit Role</a>
        </li>
    </ol>
</nav>


    <div class="table-responsive overflow-x-auto shadow-lg rounded-lg p-4 bg-white dark:bg-gray-900">
        <form action="{{ route('roles.update', $getRecord->id) }}" method="post">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Role Name</label>
                <input type="text" name="name" value="{{ $getRecord->name }}" required class="w-full p-2 border rounded-lg">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold text-lg mb-3">Permissions</label>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="check-all-groups" class="rounded h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-500">
                        <label for="check-all-groups" class="ml-4 text-sm text-gray-600 dark:text-gray-300 font-medium p-2">Check All Groups</label>
                    </div>
                    <div class="flex justify-end">
                        <span id="checked-count" class="text-sm text-gray-600 dark:text-gray-300 font-medium">0 permissions checked</span>
                    </div>
                </div>
                <div class="overflow-x-auto md:overflow-x-hidden bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        @foreach ($getPermission as $value)
                            <div class="flex-shrink-0 w-72 bg-gray-50 dark:bg-gray-700 rounded-md p-2 border border-gray-200 dark:border-gray-600">
                                <div class="mb-3 font-semibold text-gray-800 dark:text-gray-100 text-base flex items-center justify-between">
                                    <span>{{ $value['name'] }}</span>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" class="check-all rounded h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-500" data-group="{{ $value['name'] }}">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">All</span>
                                    </label>
                                </div>
                                <div class="flex flex-col gap-3 permission-group" data-group="{{ $value['name'] }}">
                                    @foreach ($value['group'] as $group)

                                        @php
                                            $checked = "";
                                        @endphp

                                        @foreach ($getRolePermission  as $role )
                                            @if ($role->permission_id == $group['id'])
                                                @php
                                                    $checked = "checked";
                                                @endphp
                                            @endif
                                        @endforeach

                                        <label class="flex items-center space-x-3 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 rounded px-2 py-1 transition-colors duration-150">
                                            <input type="checkbox"  {{ $checked }} name="permission_id[]" value="{{ $group['id'] }}" class="rounded h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-500 permission-checkbox">
                                            <span class="text-sm">{{ $group['name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <hr class="my-3 border-gray-200 dark:border-gray-600">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex space-x-1 justify-end mt-4">
                <a href="{{ route('roles.list') }}" class="btn btn-gray">Cancel</a>
                <button type="submit" class="btn btn-blue">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Check All Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check All Groups
        const checkAllGroups = document.getElementById('check-all-groups');
        const allPermissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        const allGroupCheckAlls = document.querySelectorAll('.check-all');
        const checkedCountSpan = document.getElementById('checked-count');

        // Function to update the checked count
        function updateCheckedCount() {
            const checkedCount = Array.from(allPermissionCheckboxes).filter(cb => cb.checked).length;
            checkedCountSpan.textContent = `${checkedCount} permission${checkedCount === 1 ? '' : 's'} checked`;
        }

        // Initial count on page load
        updateCheckedCount();

        // Check All Groups
        checkAllGroups.addEventListener('change', function () {
            allPermissionCheckboxes.forEach(function (checkbox) {
                checkbox.checked = checkAllGroups.checked;
            });
            allGroupCheckAlls.forEach(function (groupCheckAll) {
                groupCheckAll.checked = checkAllGroups.checked;
                groupCheckAll.indeterminate = false;
            });
            updateCheckedCount();
        });

        // Per-Group Check All
        const checkAllBoxes = document.querySelectorAll('.check-all');
        checkAllBoxes.forEach(function (checkAll) {
            checkAll.addEventListener('change', function () {
                const groupName = this.getAttribute('data-group');
                const groupCheckboxes = document.querySelectorAll(`.permission-group[data-group="${groupName}"] .permission-checkbox`);

                groupCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = checkAll.checked;
                });

                updateCheckAllGroups();
                updateCheckedCount();
            });
        });

        // Sync "Check All" and "Check All Groups" state when individual checkboxes change
        const permissionGroups = document.querySelectorAll('.permission-group');
        permissionGroups.forEach(function (group) {
            const groupName = group.getAttribute('data-group');
            const checkboxes = group.querySelectorAll('.permission-checkbox');
            const checkAll = document.querySelector(`.check-all[data-group="${groupName}"]`);

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    const someChecked = Array.from(checkboxes).some(cb => cb.checked);
                    checkAll.checked = allChecked;
                    checkAll.indeterminate = someChecked && !allChecked;

                    updateCheckAllGroups();
                    updateCheckedCount();
                });
            });
        });

        // Function to sync "Check All Groups" state
        function updateCheckAllGroups() {
            const allChecked = Array.from(allPermissionCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(allPermissionCheckboxes).some(cb => cb.checked);
            checkAllGroups.checked = allChecked;
            checkAllGroups.indeterminate = someChecked && !allChecked;
        }
    });
</script>

@endsection
