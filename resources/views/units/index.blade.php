@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white">
            <li>
                <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white/70 dongrek-font">
                    {{ __("units.home") }}
                </a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary dongrek-font">
                    {{ __("units.units") }}
                </a>
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

    <!-- Header and Add New Unit Button -->
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-semibold dongrek-font">{{ __('units.units_list') }}</h2>
        <button class="btn-green" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold dongrek-font">{{ __('units.add_new') }}</span>
        </button>
    </div>

    <!-- Units Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm">
            <thead style="color: blue;">
                <tr>
                    <th class="dongrek-font">{{ __('units.id') }}</th>
                    <th class="dongrek-font">{{ __('units.name') }}</th>
                    <th class="dongrek-font">{{ __('units.symbol') }}</th>
                    <th class="dongrek-font">{{ __('units.description') }}</th>
                    <th class="dongrek-font">{{ __('units.conversion_rate') }}</th>
                    <th class="dongrek-font">{{ __('units.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                <tr>
                    <td>{{ $unit->id }}</td>
                    <td class="dongrek-font">{{ $unit->name }}</td>
                    <td>{{ $unit->symbol }}</td>
                    <td class="dongrek-font">{{ $unit->descript }}</td>
                    <td>{{ $unit->conversion_rate }}</td>
                    <td class="text-center">
                        <button class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded" onclick="openEditModal({{ json_encode($unit) }})">
                            <i class="fa-solid fa-pen-to-square" style="color: blue;"></i>
                            <span class="dongrek-font">{{ __('units.edit') }}</span>
                        </button>
                        <button class="text-red-500 hover:text-red-700 px-3 py-1 border border-red-500 rounded" onclick="confirmDelete('{{ route('units.destroy', $unit->id) }}')">
                            <i class="fa-solid fa-trash" style="color: red;"></i>
                            <span class="dongrek-font">{{ __('units.delete') }}</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($units->total() > 0)
    <div>
        {{ $units->links('layouts.pagination') }}
    </div>
    @else
        <p class="dongrek-font">{{ __('units.no_units_available') }}</p>
    @endif
</div>

<!-- MODAL BACKDROP -->
<div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-start justify-center transition-opacity duration-300">

    <!-- Create/Edit Unit Modal -->
    <div id="unitModal" class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg transform transition-all duration-300 ease-out opacity-0 -translate-y-12">
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dongrek-font">{{ __('units.create_new_unit') }}</h2>
        <form id="unitForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium dongrek-font">{{ __('units.unit_name') }}</label>
                <input type="text" name="name" id="unitName" class="form-input w-full px-3 py-2 border rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium dongrek-font">{{ __('units.symbol') }}</label>
                <input type="text" name="symbol" id="unitSymbol" class="form-input w-full px-3 py-2 border rounded-md">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium dongrek-font">{{ __('units.description') }}</label>
                <textarea name="descript" id="unitDescription" class="form-input w-full px-3 py-2 border rounded-md"></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium dongrek-font">{{ __('units.conversion_rate') }}</label>
                <input type="number" step="0.01" name="conversion_rate" id="unitConversionRate" class="form-input w-full px-3 py-2 border rounded-md" value="1">
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray dongrek-font">{{ __('units.cancel') }}</button>
                <button type="submit" class="btn-green dongrek-font" @click="showAlert()" id="saveButton">{{ __('units.save') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
    function openCreateModal() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('unitModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '{{ __('units.create_new_unit') }}';
        document.getElementById('unitForm').action = "{{ route('units.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('unitName').value = '';
        document.getElementById('unitSymbol').value = '';
        document.getElementById('unitDescription').value = '';
        document.getElementById('unitConversionRate').value = 1;
        document.getElementById('saveButton').innerText = '{{ __('units.save') }}';
    }

    function openEditModal(unit) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('unitModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = '{{ __('units.edit_unit') }}';
        document.getElementById('unitForm').action = `/units/${unit.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('unitName').value = unit.name;
        document.getElementById('unitSymbol').value = unit.symbol;
        document.getElementById('unitDescription').value = unit.descript;
        document.getElementById('unitConversionRate').value = unit.conversion_rate;
        document.getElementById('saveButton').innerText = '{{ __('units.update') }}';
    }

    function closeModal() {
        document.getElementById('unitModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }

    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: '{{ __('units.are_you_sure') }}',
            text: "{{ __('units.this_action_cannot_be_undone') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __('units.yes_delete_it') }}'
        }).then((result) => {
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
        });
    }
</script>

<style>
  /* Add Dongrek font for Khmer text */
  .dongrek-font {
    font-family: 'Dangrek', 'Noto Sans Khmer', sans-serif;
  }
</style>

@endsection
