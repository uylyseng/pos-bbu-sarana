@extends('layouts.app')
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
        Swal.fire({
            title: "Error!",
            text: "{{ session('error') }}",
            icon: "error",
            confirmButtonText: "OK"
        });
    </script>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4">
                <!-- Title dynamically changes font based on locale -->
                <h1 class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">
                    {{ __('text.welcome') }}
                </h1>

                <!-- Description dynamically changes font based on locale -->
                <p class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">
                    {{ __('text.description') }}
                </p>

                <!-- Language Switcher -->

            </div>
        </div>
    </div>
</div>
@endsection
