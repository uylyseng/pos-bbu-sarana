<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Pos System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="favicon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/sursdey_coffee_logo.png')}}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="assets/css/perfect-scrollbar.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
        <link rel="stylesheet" type="text/css" href="assets/css/button.css">
        <link rel="stylesheet" type="text/css" href="assets/css/animate.css" defer>
        <link rel="stylesheet" type="text/css" href="assets/css/main_app.css" defer>
        <!-- FontAwesome CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">

        <script src="assets/js/perfect-scrollbar.min.js"></script>
        <script defer src="assets/js/popper.min.js"></script>
        <script defer src="assets/js/tippy-bundle.umd.min.js"></script>
        <script defer src="assets/js/sweetalert.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        

    </head>
    <style>
   .dot {
        animation: bounce 0.5s infinite ease-in-out;
    }

    .dot:nth-child(1) {
        animation-delay: 0s;
    }

    .dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    .dot:nth-child(4) {
        animation-delay: 0.6s;
    }

    .dot:nth-child(5) {
        animation-delay: 0.8s;
    }

    @keyframes bounce {
        0%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
    }
</style>

    <body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased" :class="[$store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ? 'dark' : '', $store.app.menu, $store.app.layout, $store.app.rtlClass]">
    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-white dark:bg-black">
    <div class="flex space-x-2">
        <div class="dot w-3 h-3 bg-blue-500 rounded-full"></div>
        <div class="dot w-3 h-3 bg-blue-500 rounded-full"></div>
        <div class="dot w-3 h-3 bg-blue-500 rounded-full"></div>
        <div class="dot w-3 h-3 bg-blue-500 rounded-full"></div>
        <div class="dot w-3 h-3 bg-grenn-500 rounded-full"></div>
    </div>
</div>



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
        <script src="assets/js/alpine-collaspe.min.js"></script>
        <script src="assets/js/alpine-persist.min.js"></script>
        <script defer src="assets/js/alpine-ui.min.js"></script>
        <script defer src="assets/js/alpine-focus.min.js"></script>
        <script defer src="assets/js/alpine.min.js"></script>
        <script src="assets/js/custom.js"></script>
        <script defer src="assets/js/apexcharts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('scrollToTop', () => ({
                    showTopButton: false,
                    init() {
                        window.onscroll = () => this.scrollFunction();
                    },
                    scrollFunction() {
                        this.showTopButton = document.body.scrollTop > 50 || document.documentElement.scrollTop > 50;
                    },
                    goToTop() {
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                    },
                }));
            });
            document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        let preloader = document.getElementById("preloader");
        preloader.style.opacity = "0";
        preloader.style.transition = "opacity 0.5s ease-out";
        
        setTimeout(() => {
            preloader.style.display = "none";
        }, 100); // Matches the fade-out duration
    }, 200); // Adjust timing if needed
});


        </script>



 


    </body>
</html>
