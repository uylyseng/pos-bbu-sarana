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

<div class="main-container relative min-h-screen flex items-center justify-center overflow-hidden w-full h-screen">
    <div x-data="auth" class="w-full h-full flex items-center justify-center">
        <!-- Background overlay -->
        <div class="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('assets/images/auth/coffee.jpg') }}'); background-size:full; ">
        </div>

        <!-- Login form container -->
        <div class="relative w-full max-w-md rounded-lg bg-white/80 dark:bg-black/50 p-6 md:p-10 shadow-lg backdrop-blur-lg flex items-center justify-center">
            <div class="w-full">
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-extrabold uppercase text-primary md:text-4xl">Sign In</h1>
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Enter your email and password to login</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="Email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input id="Email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email" class="w-full px-4 py-2 rounded-md border border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label for="Password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <input id="Password" type="password" name="password" required autocomplete="current-password" placeholder="Enter Password" class="w-full px-4 py-2 rounded-md border border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" class="form-checkbox h-4 w-4 text-primary dark:bg-gray-800" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Remember Password</label>
                    </div>
                    <button type="submit" class="w-full py-2 px-4 bg-primary text-white rounded-md shadow-lg uppercase hover:bg-primary-dark">Sign In</button>
                    @if (Route::has('password.request'))

                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
