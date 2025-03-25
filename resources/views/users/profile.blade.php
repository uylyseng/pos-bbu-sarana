@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4 flex justify-start">
        <ol class="flex text-gray-500 font-semibold dark:text-white">
            <li>
                <a href="{{ route('home') }}" class="hover:text-gray-500/70 dark:hover:text-white/70">
                    {{ __("Home") }}
                </a>
            </li>
            <li class="before:w-1 before:h-1 before:rounded-full before:bg-primary before:inline-block before:relative before:-top-0.5 before:mx-4">
                <a href="javascript:;" class="text-primary">
                    {{ __("Profile") }}
                </a>
            </li>
        </ol>
    </nav>

    <!-- Profile Section -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 sm:mb-0 dark:text-white">{{ __("Your Profile") }}</h2>
    </div>

    <!-- Profile Display -->
    <div class="bg-white p-6 rounded shadow dark:bg-[#1b2e4b]">

        <!-- Profile Image -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('storage/' . $user->profile) }}" 
                 onerror="this.onerror=null; this.src='{{ asset('assets/images/default-user.png') }}';"
                 alt="Profile Picture" class="rounded-full w-32 h-32 object-cover">
        </div>

        <!-- Name Display -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium mb-1">{{ __("Name") }}</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-input w-full px-3 py-2 border rounded-md" disabled>
        </div>

        <!-- Email Display -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium mb-1">{{ __("Email") }}</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-input w-full px-3 py-2 border rounded-md" disabled>
        </div>

        <!-- Gender Display -->
        <div class="mb-4">
            <label for="gender" class="block text-sm font-medium mb-1">{{ __("Gender") }}</label>
            <input type="text" name="gender" id="gender" value="{{ ucfirst($user->gender) }}" class="form-input w-full px-3 py-2 border rounded-md" disabled>
        </div>

        <!-- Status Display -->
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium mb-1">{{ __("Status") }}</label>
            <input type="text" name="status" id="status" value="{{ ucfirst($user->status) }}" class="form-input w-full px-3 py-2 border rounded-md" disabled>
        </div>

        <!-- Profile Picture Display -->
        <div class="mb-4">
            <label for="profile" class="block text-sm font-medium mb-1">{{ __("Profile Image") }}</label>
            <input type="file" name="profile" id="profile" class="form-input w-full px-3 py-2 border rounded-md" disabled>
        </div>

        <!-- Password Display -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium mb-1">{{ __("Password") }}</label>
            <input type="password" name="password" id="password" class="form-input w-full px-3 py-2 border rounded-md" disabled>
        </div>

        <!-- View Only Message -->
        <div class="flex justify-center">
            <span class="text-sm text-gray-500">{{ __("You are viewing your profile. To update, please contact the administrator.") }}</span>
        </div>

    </div>
</div>
@endsection
