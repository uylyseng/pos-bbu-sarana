<div class="main-content flex min-h-screen flex-col">
    <!-- Start Header Section -->
    <!-- The header is fixed so that it does not move when scrolling -->
    <header class="fixed top-0 left-0 right-0 z-40"
        :class="{'dark' : $store.app.semidark && $store.app.menu === 'horizontal'}">
        <div class="shadow-sm">
            <div class="relative flex w-full items-center bg-white px-5 py-2.5 dark:bg-[#0e1726]">
                <div class="horizontal-logo flex items-center justify-between ltr:mr-2 rtl:ml-2 lg:hidden">
                    <a href="index.html" class="main-logo flex shrink-0 items-center">

                        <span
                            class="hidden align-middle text-2xl font-semibold transition-all duration-300 ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light md:inline">
                            {{ $store->name }}
                        </span>

                    </a>
                    <a href="javascript:;"
                        class="collapse-icon flex flex-none rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary ltr:ml-2 rtl:mr-2 dark:bg-dark/40 dark:text-[#d0d2d6] dark:hover:bg-dark/60 dark:hover:text-primary lg:hidden"
                        @click="$store.app.toggleSidebar()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round"></path>
                            <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                            </path>
                        </svg>
                    </a>
                </div>
                <div class="hidden ltr:mr-2 rtl:ml-2 sm:block">
                    <ul class="flex items-center space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6]">
                        <!-- Additional nav links can go here if needed -->
                    </ul>
                </div>
                <div x-data="header"
                    class="flex items-center space-x-1.5 ltr:ml-auto rtl:mr-auto rtl:space-x-reverse dark:text-[#d0d2d6] sm:flex-1 ltr:sm:ml-0 sm:rtl:mr-0 lg:space-x-2">
                    <div class="sm:ltr:mr-auto sm:rtl:ml-auto" x-data="{ search: false }"
                        @click.outside="search = false">
                        <form
                            class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0"
                            :class="{'!block' : search}" @submit.prevent="search = false">
                            <div class="relative">

                            </div>
                        </form>
                    </div>
                    <div>
                        <a href="javascript:;" x-cloak x-show="$store.app.theme === 'light'"
                            class="flex items-center rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60"
                            @click="$store.app.toggleTheme('dark')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="1.5"></circle>
                                <path d="M12 2V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                </path>
                                <path d="M12 20V22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                </path>
                                <path d="M4 12L2 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                </path>
                                <path d="M22 12L20 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                </path>
                                <path opacity="0.5" d="M19.7778 4.22266L17.5558 6.25424" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round"></path>
                                <path opacity="0.5" d="M4.22217 4.22266L6.44418 6.25424" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round"></path>
                                <path opacity="0.5" d="M6.44434 17.5557L4.22211 19.7779" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round"></path>
                                <path opacity="0.5" d="M19.7778 19.7773L17.5558 17.5551" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </a>
                        <a href="javascript:;" x-cloak x-show="$store.app.theme === 'dark'"
                            class="flex items-center rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60"
                            @click="$store.app.toggleTheme('system')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21.0672 11.8568L20.4253 11.469L21.0672 11.8568ZM12.1432 2.93276L11.7553 2.29085V2.29085L12.1432 2.93276ZM21.25 12C21.25 17.1086 17.1086 21.25 12 21.25V22.75C17.9371 22.75 22.75 17.9371 22.75 12H21.25ZM12 21.25C6.89137 21.25 2.75 17.1086 2.75 12H1.25C1.25 17.9371 6.06294 22.75 12 22.75V21.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75V1.25C6.06294 1.25 1.25 6.06294 1.25 12H2.75ZM15.5 14.25C12.3244 14.25 9.75 11.6756 9.75 8.5H8.25C8.25 12.5041 11.4959 15.75 15.5 15.75V14.25ZM20.4253 11.469C19.4172 13.1373 17.5882 14.25 15.5 14.25V15.75C18.1349 15.75 20.4407 14.3439 21.7092 12.2447L20.4253 11.469ZM9.75 8.5C9.75 6.41182 10.8627 4.5828 12.531 3.57467L11.7553 2.29085C9.65609 3.5593 8.25 5.86509 8.25 8.5H9.75ZM12 2.75C11.9115 2.75 11.8077 2.71008 11.7324 2.63168C11.6686 2.56527 11.6538 2.50244 11.6503 2.47703C11.6461 2.44587 11.6482 2.35557 11.7553 2.29085L12.531 3.57467C13.0342 3.27065 13.196 2.71398 13.1368 2.27627C13.0754 1.82126 12.7166 1.25 12 1.25V2.75ZM21.7092 12.2447C21.6444 12.3518 21.5541 12.3539 21.523 12.3497C21.4976 12.3462 21.4347 12.3314 21.3683 12.2676C21.2899 12.1923 21.25 12.0885 21.25 12H22.75C22.75 11.2834 22.1787 10.9246 21.7237 10.8632C21.286 10.804 20.7293 10.9658 20.4253 11.469L21.7092 12.2447Z"
                                    fill="currentColor"></path>
                            </svg>
                        </a>
                        <a href="javascript:;" x-cloak x-show="$store.app.theme === 'system'"
                            class="flex items-center rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60"
                            @click="$store.app.toggleTheme('light')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3 9C3 6.17157 3 4.75736 3.87868 3.87868C4.75736 3 6.17157 3 9 3H15C17.8284 3 19.2426 3 20.1213 3.87868C21 4.75736 21 6.17157 21 9V14C21 15.8856 21 16.8284 20.4142 17.4142C19.8284 18 18.8856 18 17 18H7C5.11438 18 4.17157 18 3.58579 17.4142C3 16.8284 3 15.8856 3 14V9Z"
                                    stroke="currentColor" stroke-width="1.5"></path>
                                <path opacity="0.5" d="M22 21H2" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                                <path opacity="0.5" d="M15 15H9" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="dropdown shrink-0" x-data="dropdown" @click.outside="open = false">
                        <a href="javascript:;"
                            class="block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60"
                            @click="toggle">
                            <img x-bind:src="'/assets/images/flags/' + $store.app.locale.toUpperCase() + '.svg'"
                                alt="flag" class="h-5 w-5  object-cover">
                        </a>
                        <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                            class="top-11 grid w-[200px] grid-cols-1 gap-y-2 !px-2 font-semibold text-dark ltr:right-0 rtl:left-0 dark:text-white-dark dark:text-white-light/90">
                            <li>
                                <a href="{{ route('changeLang', ['lang' => 'en']) }}" class="hover:text-primary"
                                    @click="$store.app.toggleLocale('en'), toggle()"
                                    :class="{'bg-primary/10 text-primary': $store.app.locale === 'en'}">
                                    <img src="{{ asset('assets/images/flags/EN.svg') }}" alt="English"
                                        class="h-5 w-5  object-cover">
                                    <span class="ltr:ml-3 rtl:mr-3">English</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('changeLang', ['lang' => 'km']) }}" class="hover:text-primary"
                                    @click="$store.app.toggleLocale('kh'), toggle()"
                                    :class="{'bg-primary/10 text-primary': $store.app.locale === 'kh'}">
                                    <img src="{{ asset('assets/images/flags/KH.svg') }}" alt="Khmer"
                                        class="h-5 w-5  object-cover">
                                    <span class="ltr:ml-3 rtl:mr-3">Khmer</span>
                                </a>
                            </li>


                        </ul>
                    </div>

                    <div class="dropdown flex-shrink-0" x-data="dropdown" @click.outside="open = false">
                        <a href="javascript:;" class="group relative" @click="toggle()">
                            <span class="relative inline-block">
                                <!-- Profile Image -->
                                <img class="h-9 w-9 rounded-full object-cover saturate-100 group-hover:saturate-100"
                                    src="{{ Auth::user() && Auth::user()->profile
           ? asset('storage/' . Auth::user()->profile)
           : asset('assets/images/user-profile.jpeg') }}" alt="User Profile" />

                                <!-- Smaller Status Indicator -->
                                <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full
           ring-1 ring-white dark:ring-gray-800 bg-green-500">
                                </span>
                            </span>

                        </a>
                        <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                            class="top-11 w-[230px] !py-0 font-semibold text-dark ltr:right-0 rtl:left-0 dark:text-white-dark dark:text-white-light/90">
                            <li>
                                <div class="flex items-center px-4 py-4">
                                    <div class="flex-none">
                                        <img class="h-9 w-9 rounded-full object-cover saturate-50 group-hover:saturate-100"
                                            src="{{ Auth::user() && Auth::user()->profile
           ? asset('storage/' . Auth::user()->profile)
           : asset('assets/images/user-profile.jpeg') }}" alt="User Profile" />
                                        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full
             ring-1 ring-white dark:ring-gray-800 bg-green-500">
                                        </span>
                                    </div>

                                    <div class="truncate ltr:pl-4 rtl:pr-4">
                                        <h4 class="text-base">
                                            {{ Auth::user()->name ?? 'StarCode Kh' }}
                                        </h4>
                                        <a href="mailto:{{ Auth::user()->email ?? 'starcodekh@gmail.com' }}"
                                            class="text-black/60 hover:text-primary dark:text-dark-light/60 dark:hover:text-white">
                                            {{ Auth::user()->email ?? 'starcodekh@gmail.com' }}
                                        </a>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <a href="{{ route('users.index') }}" class="dark:hover:text-white" @click="toggle">
                                    <svg class="h-4.5 w-4.5 shrink-0 ltr:mr-2 rtl:ml-2" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5"></circle>
                                        <path opacity="0.5"
                                            d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                    </svg>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="#" class="!py-3 text-danger"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <svg class="h-4.5 w-4.5 shrink-0 rotate-90 ltr:mr-2 rtl:ml-2" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                        <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    Sign Out
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>



    <!-- Alpine.js initialization for dropdown and header components -->
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dropdown', () => ({
            open: false,
            toggle() {
                this.open = !this.open;
            }
        }));
        Alpine.data('header', () => ({
            search: false
        }));
    });
    </script>
    </body>

    </html>
