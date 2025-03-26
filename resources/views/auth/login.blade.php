@extends('layouts.admin')
@section('content')

@if(session('success'))
<script>
    Swal.fire({
        title: "Success!",
        text: "{!! session('success') !!}",
        icon: "success",
        timer: 1000,
        confirmButtonText: "OK"
    });
</script>
@endif

@if(session('info'))
<script>
    Swal.fire({
        title: "Success!",
        text: "{!! session('success') !!}",
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

<style>
    /* Import Dongrek font from Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Dangrek&display=swap');

    .dongrek-font {
        font-family: 'Dangrek', 'Arial', sans-serif;
        letter-spacing: 0.01em;
        font-feature-settings: "kern" 1;
        text-rendering: optimizeLegibility;
        font-weight: 500;
    }

    /* Apply Dangrek font to input fields and make text white */
    input {
        font-family: 'Dangrek', 'Arial', sans-serif;
    }

    label {
        color: white !important;
    }

    .text-gray-700 {
        color: white !important;
    }

    .h1\text-gray-900 {
        color: white !important;
    }

    .dark\:text-gray-300 {
        color: white !important;
    }
</style>

<div class="main-container relative min-h-screen flex items-center justify-center overflow-hidden w-full h-screen">
    <div x-data="auth" class="w-full h-full flex items-center justify-center">
        <!-- Background overlay -->
        <div class="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('assets/images/auth/coffee.jpg') }}'); background-size:full; ">
        </div>

        <!-- Login form container -->
        <div class="relative w-full max-w-md rounded-lg bg-white/80 dark:bg-black/50 p-6 md:p-10 shadow-lg backdrop-blur-lg flex items-center justify-center">
            <div class="w-full">
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-extrabold uppercase text-green-600 md:text-4xl dongrek-font">{{ __('login_'.app()->getLocale().'.login') }}</h1>
                    <p class="mt-2 text-sm text-white dongrek-font">{{ __('login_'.app()->getLocale().'.enter_credentials') }}</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div class="flex flex-row items-center gap-4">
                        <label for="Email" class="w-1/3 text-sm font-medium dongrek-font">{{ __('login_'.app()->getLocale().'.email_address') }}</label>
                        <input id="Email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('login_'.app()->getLocale().'.enter_email') }}"
                            class="w-2/3 px-4 py-2 rounded-md border border-gray-300 focus:border-green-600 focus:ring focus:ring-green-600 focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:text-white dongrek-font">
                    </div>
                    <div class="flex flex-row items-center gap-4">
                        <label for="Password" class="w-1/3 text-sm font-medium dongrek-font">{{ __('login_'.app()->getLocale().'.password') }}</label>
                        <input id="Password" type="password" name="password" required autocomplete="current-password" placeholder="{{ __('login_'.app()->getLocale().'.enter_password') }}"
                            class="w-2/3 px-4 py-2 rounded-md border border-gray-300 focus:border-green-600 focus:ring focus:ring-green-600 focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:text-white dongrek-font">
                    </div>
                    <div class="flex items-center ml-[33%]">
                        <input type="checkbox" id="remember" class="form-checkbox h-4 w-4 text-green-600 dark:bg-gray-800" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="ml-2 text-sm dongrek-font">{{ __('login_'.app()->getLocale().'.remember_me') }}</label>
                    </div>
                    <button type="submit" class="w-full py-2 px-4 bg-green-600 text-white rounded-md shadow-lg uppercase hover:bg-green-700 dongrek-font">{{ __('login_'.app()->getLocale().'.login') }}</button>
                    @if (Route::has('password.request'))
                    <div class="text-center">
                        <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:underline dongrek-font">{{ __('login_'.app()->getLocale().'.forgot_password') }}</a>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
