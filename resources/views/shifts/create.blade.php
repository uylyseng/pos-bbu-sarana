@extends('layouts.master')
@section('content')

@if(session('success'))
<script>
Swal.fire({
    title: "Success!",
    text: "{{ session('success') }}",
    icon: "success",
    timer: 1000,
    confirmButtonText: "OK"
});
</script>
@endif

@if(session('error'))
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
        icon: 'error',
        text: "{{ session('error') }}",
        padding: '2em',
    });
});
</script>
@endif


<div class="container mx-auto px-4 py-6">
    <!-- Success Alert will be triggered via SweetAlert (see below) -->
    @if(session('active_shift'))
    <div class="bg-yellow-500 text-white p-3 rounded-md flex justify-between items-center">
        <p class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i> You already have an open shift!
            <a href="{{ route('pos.index') }}" class="underline ml-2 font-semibold">Continue POS</a>
        </p>
    </div>
    @else
    @if(auth()->check())
    <h1 class="text-2xl font-bold text-gray-700 dark:text-white mb-4 flex items-center space-x-2">
        <i class="fas fa-clock text-blue-500"></i>
        <span>Create New Shift</span>
        <img src="{{ asset('icons/shifts.png') }}" alt="Create New Shift" class="w-6 h-6">
    </h1>

    <form action="{{ route('shifts.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        @csrf
        <!-- Hidden Fields -->
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <!-- time_open is set to current datetime in ISO format (without seconds) -->
        <input type="hidden" name="time_open" value="{{ now()->format('Y-m-d\TH:i') }}">
        <input type="hidden" name="cash_submitted" value="0">

        <!-- Cash In Hand Input -->
        <div class="mb-4">
            <label for="cash_in_hand" class="block text-sm font-medium text-gray-700 dark:text-white flex items-center">
                <i class="fas fa-dollar-sign mr-2 text-green-500"></i> Cash In Hand
            </label>
            <input type="number" step="0.01" name="cash_in_hand" id="cash_in_hand"
                class="w-full px-4 py-2 mt-1 border rounded-lg text-gray-900 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring focus:ring-blue-300 focus:outline-none transition-all"
                value=" " required>
        </div>

        <!-- Buttons: Cancel & Submit -->
        <div class="flex justify-end mt-4 space-x-2">
            <a href="{{ route('shifts.index') }}"
                class="btn btn-gray flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-all">
                Cancel
            </a>
            <button type="submit" class="btn-green flex items-center px-4 py-2">
                Create Shift
               
            </button>
        </div>
    </form>
    @else
    <p class="text-red-500 font-semibold mt-4 flex items-center">
        <i class="fas fa-exclamation-triangle mr-2"></i> Error: You must be logged in to create a shift.
    </p>
    @endif
    @endif
</div>

<!-- SweetAlert Async Alert for Success Message -->


<!-- Styles -->
<style>
.btn-green {
    background-color: #10b981;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;

}

.btn-gray {
    background-color: #333;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;

}
</style>
@endsection