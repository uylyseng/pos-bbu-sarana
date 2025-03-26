<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pos System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/sursdey_coffee_logo.png')}}" >

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Khmer Fonts -->
    <link href="{{ asset('css/khmer-fonts.css') }}" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.css') }}" defer>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/nice-select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/highlight.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/file-upload-with-preview.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flatpickr.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="assets/css/master_main.css" defer>
    <link rel="stylesheet" type="text/css" href="assets/css/main_app.css" defer>


    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/checkbox-style.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/switch-style.css') }}" >
    <!-- Scripts -->
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
     <script defer src="assets/js/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</head>

<body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased" :class="[$store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ? 'dark' : '', $store.app.menu, $store.app.layout, $store.app.rtlClass]">

    <!-- Sidebar menu overlay -->
    <div x-cloak class="fixed inset-0 z-50 bg-black/60 lg:hidden" :class="{'hidden' : !$store.app.sidebar}" @click="$store.app.toggleSidebar()"></div>

    <!-- Scroll to top button -->


    <div class="main-container min-h-screen text-black dark:text-white-dark" :class="$store.app.navbar">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Header -->
        @include('layouts.partials.header')

        <div class="animate__animated p-6" :class="$store.app.animation">
            @yield('content')
        </div>

        <!-- Footer -->
        <!-- @include('layouts.partials.footer') -->
    </div>

    <!-- Scripts -->
   <!-- Alpine.js Plugins -->
    <script src="{{ asset('assets/js/alpine-persist.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/alpine-ui.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/alpine-focus.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/alpine.min.js') }}"></script>
    <script src="{{ asset('assets/js/perfect-scrollbar.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/tippy-bundle.umd.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <!-- ApexCharts -->
    <script defer src="{{ asset('assets/js/apexcharts.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<style>
        body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
        color: #333;
        transition: background 0.3s ease-in-out, color 0.3s ease-in-out;
    }
    body.dark {
        background-color: #1e293b;
        color: #cbd5e1;
    }
    .card {
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background 0.3s ease-in-out;
    }
    body.dark .card {
        background-color: #0f172a;
    }
    .form-input,
    .form-select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        transition: all 0.3s ease-in-out;
    }
    body.dark .form-input,
    body.dark .form-select {
        background-color: #1e293b;
        border: 1px solid #334155;
        color: white;
    }
    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #007bff;
    }
    .btn {
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s ease-in-out;
    }
    .btn-blue {
        background-color: #007bff;
        color: white;
    }
    .btn-blue:hover {
        background-color: #0069d9;
    }
    .btn-gray {
        background-color: #6c757d;
        color: white;
    }
    .btn-gray:hover {
        background-color: #5a6268;
    }
    .btn-red {
        background-color: #dc3545;
        color: white;
        border: none;
    }
    .btn-red:hover {
        background-color: #c82333;
    }
    .btn-magic {
        background-color: #cbd5e1;
        color: white;
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }
    .btn-magic:hover {
        background-color: #5a359a;
    }
    .switch-container {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        gap: 10px;
    }
    .switch-container input {
        width: 0;
        height: 0;
        opacity: 0;
        position: absolute;
    }
    .slider {
        position: relative;
        width: 40px;
        height: 20px;
        background: #ddd;
        border-radius: 15px;
        transition: 0.3s;
    }
    .slider::before {
        content: "";
        position: absolute;
        width: 18px;
        height: 18px;
        background: white;
        border-radius: 50%;
        top: 1px;
        left: 2px;
        transition: 0.3s;
    }
    .switch-container input:checked + .slider {
        background: #28a745;
    }
    .switch-container input:checked + .slider::before {
        transform: translateX(20px);
    }
    .label-text {
        font-size: 14px;
        color: #333;
    }
    body.dark .label-text {
        color: #cbd5e1;
    }
    .switch-container-box {
        background: #f9fafb;
        border: 1px solid #e2e8f0;
        padding: 16px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    body.dark th {
        background-color: #1e293b;
        color: #cbd5e1;
    }
    .dark-mode-toggle {
        cursor: pointer;
        background-color: #333;
        color: white;
        border-radius: 50%;
        padding: 6px 10px;
        font-size: 16px;
        transition: background 0.3s;
    }
    .dark-mode-toggle:hover {
        background-color: #555;
    }
</style>
</body>

</html>
