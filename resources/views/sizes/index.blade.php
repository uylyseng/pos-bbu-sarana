@extends('layouts.app')

@section('content')

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

<style>
    /* Dongrek font for Khmer text */
    @font-face {
        font-family: 'Dongrek';
        src: url('https://fonts.googleapis.com/css2?family=Dangrek&display=swap');
    }

    .khmer-font {
        font-family: 'Dongrek', 'Khmer OS', sans-serif;
    }
</style>

<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white dongrek-font">
            <li>
                <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white-dark/70">
                    {{ __('sizes.home') }}
                </a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">{{ __('sizes.sizes') }}</a>
            </li>
        </ol>
    </nav>

    <!-- Add New Size Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white khmer-font">{{ __('sizes.sizes_list') }}</h2>
        <button class="btn-green flex items-center" onclick="openCreateModal()">
            <i class="fas fa-plus-circle mr-2"></i>
            <span class="font-semibold khmer-font">{{ __('sizes.add_new') }}</span>
        </button>
    </div>

    <!-- Sizes Table -->
    <div class="mt-6 p-4 bg-white rounded shadow dark:bg-[#1b2e4b]">
        <table class="w-full whitespace-nowrap shadow-sm table-auto">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2 khmer-font">{{ __('sizes.id') }}</th>
                    <th class="px-4 py-2 khmer-font">{{ __('sizes.name') }}</th>
                    <th class="px-4 py-2 khmer-font">{{ __('sizes.description') }}</th>
                    <th class="px-4 py-2 text-center khmer-font">{{ __('sizes.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sizes as $size)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-2">{{ $size->id }}</td>
                    <td class="px-4 py-2">{{ $size->name }}</td>
                    <td class="px-4 py-2">{{ $size->descript }}</td>
                    <td class="px-4 py-2 text-center dongrek-font">
                        <button class="text-blue-500 hover:text-blue-700 px-3 py-1 border border-blue-500 rounded mr-2" onclick="openEditModal({{ json_encode($size) }})">
                            <i class="fa-solid fa-pen-to-square" style="color: blue;"></i> {{ __('sizes.edit') }}
                        </button>
                        <button class="text-red-500 hover:text-red-700 px-3 py-1 border border-red-500 rounded" onclick="confirmDelete('{{ route('sizes.destroy', $size->id) }}')">
                            <i class="fa-solid fa-trash" style="color: red;"></i> {{ __('sizes.delete') }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($sizes->total() > 0)
    <div>
        {{ $sizes->links('layouts.pagination') }}
    </div>
    @else
    <p class="khmer-font">{{ __('sizes.no_sizes_available') }}</p>
    @endif
</div>

<!-- MODAL BACKDROP (Blur Effect) -->
<div id="modalBackdrop"
  class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden
         flex items-start justify-center transition-opacity duration-300">
    <!-- Create/Edit Size Modal -->
    <div id="sizeModal" class="relative mt-10 w-full max-w-md bg-white dark:bg-gray-800
           rounded-lg p-6 shadow-lg
           transform transition-all duration-300 ease-out
           opacity-0 -translate-y-12">
        <h2 id="modalTitle" class="text-lg font-semibold mb-3 dark:text-white khmer-font">{{ __('sizes.create_new_size') }}</h2>
        <form id="sizeForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white khmer-font">{{ __('sizes.size_name') }}</label>
                <input type="text" name="name" id="sizeName" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 dark:text-white khmer-font">{{ __('sizes.description') }}</label>
                <textarea name="descript" id="sizeDescription" class="form-input w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="btn-gray khmer-font">{{ __('sizes.cancel') }}</button>
                <button type="submit" class="btn-green khmer-font" id="saveButton">{{ __('sizes.save') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal Handling -->
<script>
    function openCreateModal() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('sizeModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = "{{ __('sizes.create_new_size') }}";
        document.getElementById('sizeForm').action = "{{ route('sizes.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('sizeName').value = '';
        document.getElementById('sizeDescription').value = '';
        document.getElementById('saveButton').innerText = "{{ __('sizes.save') }}";
    }

    function openEditModal(size) {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('sizeModal').classList.remove('opacity-0', 'translate-y-[-30px]', 'scale-95');
        document.getElementById('modalTitle').innerText = "{{ __('sizes.edit_size') }}";
        document.getElementById('sizeForm').action = `/sizes/${size.id}`;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('sizeName').value = size.name;
        document.getElementById('sizeDescription').value = size.descript;
        document.getElementById('saveButton').innerText = "{{ __('sizes.update') }}";
    }

    function closeModal() {
        document.getElementById('sizeModal').classList.add('opacity-0', 'translate-y-[-30px]', 'scale-95');
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }, 300);
    }
</script>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        text-align: left;
        padding: 10px;
        border: 0px solid #ddd;
    }

    th {
        background: #185df2; /* Softer blue gradient */
        color: white;
        text-align: justify;
        font-weight: bold;
    }

    /* Dark mode adjustments */
    body.dark {
        background: #1e293b;
        color: #cbd5e1;
    }
    body.dark .modal-content {
        background: #0f172a;
        color: #cbd5e1;
    }

    /* Modal animation */
    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        transform: translateY(-30px);
        transition: transform 0.3s ease-in-out;
    }
    #modalBackdrop {
        background: rgba(0, 0, 0, 0.7);
    }
    /* Button Styles */
    .btn-green { background-color: #0ea5e9;  color: white; padding: 8px 12px; border-radius: 5px; transition: background 0.2s ease-in-out; }
    .btn-green:hover { background-color: darkgreen; }

    .btn-blue { background-color: blue; color: white; padding: 8px 12px; border-radius: 5px; }
    .btn-red { background-color: red; color: white; padding: 8px 12px; border-radius: 5px; }
    .btn-gray { background-color: gray; color: white; padding: 8px 12px; border-radius: 5px; }
</style>
@endsection
