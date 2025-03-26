<div class="main-container min-h-screen text-black dark:text-white-dark" :class="[$store.app.navbar]">
    <!-- start sidebar section -->
    <div :class="{'dark text-white-dark' : $store.app.semidark}">
        <nav x-data="sidebar" class="sidebar fixed bottom-0 top-0 z-50 h-full min-h-screen w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
            <div class="h-full bg-white dark:bg-[#0e1726]">
                <div class="flex items-center justify-between px-4 py-3">
                    <a href="index.html" class="main-logo flex shrink-0 items-center">
                        <span class="hidden align-middle text-2xl font-semibold transition-all duration-300 ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light md:inline">
                            {{ Auth::user()->store->name ?? config('app.name', 'Your App Name') }}
                        </span>
                    </a>
                    <a href="javascript:;" class="collapse-icon flex items-center h-8 w-8 rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10" @click="$store.app.toggleSidebar()">
                        <svg class="m-auto h-5 w-5" width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                </div>

                @php
                    $PermissionDasboard = App\Models\PermissionRole::getPermission('dashboard', Auth::user()->roles_id);
                    $PermissionSales = App\Models\PermissionRole::getPermission('sale', Auth::user()->roles_id);
                    $PermissionCashiers = App\Models\PermissionRole::getPermission('cashier', Auth::user()->roles_id);
                    $PermissionPos = App\Models\PermissionRole::getPermission('pos', Auth::user()->roles_id);
                    $PermissionCategories = App\Models\PermissionRole::getPermission('categories', Auth::user()->roles_id);
                    $PermissionProducts = App\Models\PermissionRole::getPermission('products', Auth::user()->roles_id);
                    $PermissionProductSizes = App\Models\PermissionRole::getPermission('product_sizes', Auth::user()->roles_id);
                    $PermissionProductToppings = App\Models\PermissionRole::getPermission('product_toppings', Auth::user()->roles_id);
                    $PermissionSizes = App\Models\PermissionRole::getPermission('sizes', Auth::user()->roles_id);
                    $PermissionTables = App\Models\PermissionRole::getPermission('tables', Auth::user()->roles_id);
                    $PermissionGroupTables = App\Models\PermissionRole::getPermission('group_tables', Auth::user()->roles_id);
                    $PermissionGroupToppings = App\Models\PermissionRole::getPermission('group_toppings', Auth::user()->roles_id);
                    $PermissionToppings = App\Models\PermissionRole::getPermission('toppings', Auth::user()->roles_id);
                    $PermissionUnits = App\Models\PermissionRole::getPermission('units', Auth::user()->roles_id);
                    $PermissionCurrencies = App\Models\PermissionRole::getPermission('currencies', Auth::user()->roles_id);
                    $PermissionCustomers = App\Models\PermissionRole::getPermission('customers', Auth::user()->roles_id);
                    $PermissionCoupons = App\Models\PermissionRole::getPermission('coupon', Auth::user()->roles_id);
                    $PermissionExchangeRates = App\Models\PermissionRole::getPermission('exchange_rates', Auth::user()->roles_id);
                    $PermissionPaymentMethods = App\Models\PermissionRole::getPermission('payment_methods', Auth::user()->roles_id);
                    $PermissionStores = App\Models\PermissionRole::getPermission('stores', Auth::user()->roles_id);
                    $PermissionPurchases = App\Models\PermissionRole::getPermission('purchase', Auth::user()->roles_id);
                    $PermissionSuppliers = App\Models\PermissionRole::getPermission('supplier', Auth::user()->roles_id);
                    $PermissionExspense_types = App\Models\PermissionRole::getPermission('exspense-type', Auth::user()->roles_id);
                    $PermissionExspenses = App\Models\PermissionRole::getPermission('exspenses', Auth::user()->roles_id);
                    $PermissionReports = App\Models\PermissionRole::getPermission('report', Auth::user()->roles_id);

                    $PermissionUsers = App\Models\PermissionRole::getPermission('users', Auth::user()->roles_id);
                    $PermissionRoles = App\Models\PermissionRole::getPermission('roles', Auth::user()->roles_id);

                @endphp

                <ul class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold" x-data="{ activeDropdown: 'dashboard' }">
                    @if (!empty($PermissionDasboard) || !empty($PermissionSales) || !empty($PermissionCashiers))
                    <li class="menu nav-item dongrek-font">
                        <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'dashboard'}" @click="activeDropdown === 'dashboard' ? activeDropdown = null : activeDropdown = 'dashboard'">
                            <div class="flex items-center">
                            <svg viewBox="0 0 24 24" id="dashboard_layout_screen" data-name="dashboard layout screen" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect id="Mask" width="24" height="24" fill="none"></rect> <g id="Group_2" data-name="Group 2" transform="translate(4 4)"> <rect id="Rectangle_15" data-name="Rectangle 15" width="6" height="9" rx="1" transform="translate(0 0)" fill="none" stroke="#000000" stroke-miterlimit="10" stroke-width="1.5"></rect> <rect id="Rectangle_15-2" data-name="Rectangle 15" width="16" height="4" rx="1" transform="translate(0 12)" fill="none" stroke="#000000" stroke-miterlimit="10" stroke-width="1.5"></rect> <rect id="Rectangle_15-3" data-name="Rectangle 15" width="7" height="9" rx="1" transform="translate(9 0)" fill="none" stroke="#000000" stroke-miterlimit="10" stroke-width="1.5"></rect> </g> </g></svg>

                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.dashboard') }}</span>
                            </div>
                            <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'dashboard'}">
                                <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </button>
                        <ul  x-cloak="" x-show="activeDropdown === 'dashboard'" x-collapse="" class="sub-menu text-gray-500 dongrek-font">
                            @if (!empty($PermissionSales))
                            <li>
                                <a href="{{ route('dashboard.sale') }}" class="active {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.sales') }}</a>
                            </li>
                            @endif
                            @if (!empty($PermissionCashiers))
                            <li>
                                <a href="{{ route('dashboard.cashier') }}" class="active {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.cashier') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if (!empty($PermissionPos))
                    <h1 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08] dongrek-font">
                        <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.pos') }}</span>
                    </h1>
                    <li class="nav-item dongrek-font">
                        <ul>
                            <li class="nav-item">
                                <a href="{{ route('shifts.create') }}" class="group">
                                    <div class="flex items-center">
                                    <svg fill="#000000" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M30 2.994h-28c-1.099 0-2 0.9-2 2v17.006c0 1.099 0.9 1.999 2 1.999h13v3.006h-5c-0.552 0-1 0.448-1 1s0.448 1 1 1h12c0.552 0 1-0.448 1-1s-0.448-1-1-1h-5v-3.006h13c1.099 0 2-0.9 2-1.999v-17.006c0-1.1-0.901-2-2-2zM30 22h-28v-17.006h28v17.006z"></path> </g></svg>

                                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.pos') }}</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if (!empty($PermissionReports))
                    <h1 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08] dongrek-font">
                        <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.list_sale') }}</span>
                    </h1>

                    <li class="nav-item dongrek-font">
                        <ul>
                            <li class="nav-item">
                                <a href="{{ route('sales.list') }}" class="group">
                                    <div class="flex items-center">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M8 6.00067L21 6.00139M8 12.0007L21 12.0015M8 18.0007L21 18.0015M3.5 6H3.51M3.5 12H3.51M3.5 18H3.51M4 6C4 6.27614 3.77614 6.5 3.5 6.5C3.22386 6.5 3 6.27614 3 6C3 5.72386 3.22386 5.5 3.5 5.5C3.77614 5.5 4 5.72386 4 6ZM4 12C4 12.2761 3.77614 12.5 3.5 12.5C3.22386 12.5 3 12.2761 3 12C3 11.7239 3.22386 11.5 3.5 11.5C3.77614 11.5 4 11.7239 4 12ZM4 18C4 18.2761 3.77614 18.5 3.5 18.5C3.22386 18.5 3 18.2761 3 18C3 17.7239 3.22386 17.5 3.5 17.5C3.77614 17.5 4 17.7239 4 18Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.list_sale') }}</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dongrek-font">
                        <ul>
                            <li class="nav-item dongrek-font">
                                <a href="{{ route('sales.today') }}" class="group">
                                    <div class="flex items-center">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M8 6.00067L21 6.00139M8 12.0007L21 12.0015M8 18.0007L21 18.0015M3.5 6H3.51M3.5 12H3.51M3.5 18H3.51M4 6C4 6.27614 3.77614 6.5 3.5 6.5C3.22386 6.5 3 6.27614 3 6C3 5.72386 3.22386 5.5 3.5 5.5C3.77614 5.5 4 5.72386 4 6ZM4 12C4 12.2761 3.77614 12.5 3.5 12.5C3.22386 12.5 3 12.2761 3 12C3 11.7239 3.22386 11.5 3.5 11.5C3.77614 11.5 4 11.7239 4 12ZM4 18C4 18.2761 3.77614 18.5 3.5 18.5C3.22386 18.5 3 18.2761 3 18C3 17.7239 3.22386 17.5 3.5 17.5C3.77614 17.5 4 17.7239 4 18Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.list_open_sale') }}</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <li class="nav-item dongrek-font">
                        <ul>
                            @if (!empty($PermissionCategories) || !empty($PermissionProducts) || !empty($PermissionPurchase))
                                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                                    <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.product') }}</span>
                                </h2>
                                @if (!empty($PermissionCategories))
                                <li class="nav-item dongrek-font">
                                    <a href="{{ route('categories.index') }}" class="group">
                                        <div class="flex items-center">
                                            <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M10 3H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM9 9H5V5h4v4zm11 4h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1zm-1 6h-4v-4h4v4zM17 3c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2zM7 13c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z"></path></g></svg>
                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Category') }} </span>
                                        </div>
                                    </a>
                                </li>
                                @endif

                                @if (!empty($PermissionProducts))
                                <li class="nav-item dongrek-font">
                                    <a href="{{ route('products.index') }}" class="group">
                                        <div class="flex items-center">
                                        <svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title></title> <g fill="none" fill-rule="evenodd" id="页面-1" stroke="none" stroke-width="1"> <g id="导航图标" transform="translate(-325.000000, -80.000000)"> <g id="编组" transform="translate(325.000000, 80.000000)"> <polygon fill="#FFFFFF" fill-opacity="0.01" fill-rule="nonzero" id="路径" points="24 0 0 0 0 24 24 24"></polygon> <polygon id="路径" points="22 7 12 2 2 7 2 17 12 22 22 17" stroke="#212121" stroke-linejoin="round" stroke-width="1.5"></polygon> <line id="路径" stroke="#212121" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" x1="2" x2="12" y1="7" y2="12"></line> <line id="路径" stroke="#212121" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" x1="12" x2="12" y1="22" y2="12"></line> <line id="路径" stroke="#212121" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" x1="22" x2="12" y1="7" y2="12"></line> <line id="路径" stroke="#212121" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" x1="17" x2="7" y1="4.5" y2="9.5"></line> </g> </g> </g> </g></svg>

                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">
                                                {{ __('text.Products') }}
                                            </span>

                                        </div>
                                    </a>
                                </li>
                                @endif

                                @if (!empty($PermissionPurchases))
                                <li class="nav-item dongrek-font">
                                    <a href="{{ route('purchases.index') }}" class="group">
                                        <div class="flex items-center">
                                        <svg fill="#000000" height="200px" width="200px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 209.163 209.163" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M155.214,60.485c-0.62,2.206-2.627,3.649-4.811,3.649c-0.447,0-0.902-0.061-1.355-0.188l-40.029-11.241 c-2.659-0.747-4.209-3.507-3.462-6.166c0.747-2.658,3.506-4.209,6.166-3.462l40.03,11.241 C154.41,55.066,155.961,57.826,155.214,60.485z M84.142,182.268c-7.415,0-13.448,6.033-13.448,13.448 c0,7.415,6.033,13.447,13.448,13.447c7.415,0,13.447-6.032,13.447-13.447C97.589,188.301,91.557,182.268,84.142,182.268z M165.761,182.268c-7.415,0-13.448,6.033-13.448,13.448c0,7.415,6.033,13.447,13.448,13.447c7.415,0,13.448-6.032,13.448-13.447 C179.208,188.301,173.176,182.268,165.761,182.268z M197.442,72.788l-12.996,71.041c-0.435,2.375-2.504,4.1-4.918,4.1H72.198 l2.76,13.012c0.686,3.233,3.583,5.58,6.888,5.58h90.751c2.761,0,5,2.239,5,5s-2.239,5-5,5H81.845c-7.999,0-15.01-5.68-16.67-13.505 l-4.024-18.97L34.382,35.294H16.639c-2.761,0-5-2.239-5-5c0-2.761,2.239-5,5-5H38.3c2.301,0,4.305,1.57,4.855,3.805l9.265,37.639 l29.969,0.032l13.687-48.737c0.001-0.002,0-0.003,0.001-0.005l4.038-14.376c0.747-2.658,3.507-4.21,6.166-3.462l72.448,20.344 c2.659,0.747,4.209,3.507,3.462,6.165c-0.62,2.207-2.627,3.649-4.811,3.65c-0.447,0-0.902-0.06-1.354-0.188l-1.106-0.311 l-1.294,4.608l1.106,0.31c2.658,0.747,4.208,3.507,3.462,6.166l-7.282,25.93l21.62,0.023c1.482,0.001,2.888,0.661,3.837,1.8 C197.315,69.828,197.709,71.329,197.442,72.788z M108.389,11.168l-1.294,4.608l56.9,15.979l1.294-4.608L108.389,11.168z M95.31,66.783l63.083,0.068l3.061-10.899c0.358-1.277,0.195-2.644-0.454-3.8c-0.649-1.157-1.731-2.007-3.008-2.366L109.13,36.065 c-1.276-0.359-2.643-0.196-3.8,0.454c-1.156,0.649-2.007,1.731-2.366,3.008L95.31,66.783z"></path> </g></svg>

                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.purchase') }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif
                            @endif

                            @if (!empty($PermissionCustomers) || !empty($PermissionCoupons) || !empty($PermissionSuppliers))
                                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                                    <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.management') }}</span>
                                </h2>
                                @if (!empty($PermissionCustomers))
                                <li class="nav-item dongrek-font">
                                    <a href="{{ route('customers.index') }}" class="group">
                                        <div class="flex items-center">
                                        <svg viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M749.7 548.7l-164.6 91.4V823l164.6 91.4L914.3 823V640.1l-164.6-91.4zM841.1 780l-91.4 50.8-91.4-50.8v-96.8l91.4-50.8 91.4 50.8V780z" fill="#0F1F3C"></path><path d="M713.600831 737.455926a36.6 36.6 0 1 0 72.255718-11.719698 36.6 36.6 0 1 0-72.255718 11.719698Z" fill="#0F1F3C"></path><path d="M688.7 479.8c-12.7-6.2-25.7-11.8-38.9-16.6 49.8-40.3 81.6-101.8 81.6-170.6 0-121-98.4-219.4-219.4-219.4s-219.4 98.4-219.4 219.4c0 68.9 31.9 130.5 81.7 170.7-154.2 56.4-264.6 204.5-264.6 378h73.1c0-181.5 147.7-329.1 329.1-329.1 50.7 0 99.3 11.2 144.5 33.3l32.3-65.7zM512 146.3c80.7 0 146.3 65.6 146.3 146.3S592.7 438.9 512 438.9s-146.3-65.6-146.3-146.3S431.4 146.3 512 146.3z" fill="#0F1F3C"></path></g></svg>



                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.customers') }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif

                                @if (!empty($PermissionCoupons))
                                <li class="nav-item">
                                    <a href="{{ route('coupons.index') }}" class="group">
                                        <div class="flex items-center dongrek-font">
                                        <svg viewBox="0 0 32 32" enable-background="new 0 0 32 32" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Layer_1"></g> <g id="Layer_2"> <g> <polyline fill="none" points=" 2,7 2,6 30,6 30,26 2,26 2,17 " stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2"></polyline> <line fill="none" stroke="#000000" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2" x1="7" x2="7" y1="6" y2="10"></line> <line fill="none" stroke="#000000" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2" x1="7" x2="7" y1="14" y2="26"></line> <circle cx="7" cy="12" fill="none" r="2" stroke="#000000" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2"></circle> <polyline fill="none" points="9,13 12,14 12,10 9,11 " stroke="#000000" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2"></polyline> <polyline fill="none" points="5,13 2,14 2,10 5,11 " stroke="#000000" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2"></polyline> <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2" x1="21" x2="21" y1="10" y2="12"></line> <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2" x1="21" x2="21" y1="20" y2="22"></line> <path d=" M19,20h3c1.1,0,2-0.9,2-2s-0.9-2-2-2h-2c-1.1,0-2-0.9-2-2s0.9-2,2-2h3" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2"></path> </g> </g> </g></svg>

                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.coupon') }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif

                                @if (!empty($PermissionSuppliers))
                                <li class="nav-item dongrek-font">
                                    <a href="{{ route('suppliers.index') }}" class="group">
                                        <div class="flex items-center">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11 2C9.34315 2 8 3.34315 8 5V6.00038C7.39483 6.00219 6.86113 6.01237 6.41261 6.06902C5.8235 6.14344 5.25718 6.31027 4.76902 6.73364C4.28087 7.15702 4.03562 7.69406 3.87865 8.26672C3.73286 8.79855 3.63761 9.46561 3.52795 10.2335L3.51947 10.2929L2.65222 16.3636C2.50907 17.3653 2.38687 18.2204 2.38563 18.9086C2.38431 19.6412 2.51592 20.3617 3.03969 20.9656C3.56347 21.5695 4.25813 21.8017 4.98354 21.904C5.66496 22.0001 6.52877 22.0001 7.54064 22H16.4594C17.4713 22.0001 18.3351 22.0001 19.0165 21.904C19.7419 21.8017 20.4366 21.5695 20.9604 20.9656C21.4842 20.3617 21.6158 19.6412 21.6144 18.9086C21.6132 18.2204 21.491 17.3653 21.3478 16.3635L20.4721 10.2335C20.3625 9.46561 20.2672 8.79855 20.1214 8.26672C19.9645 7.69406 19.7192 7.15702 19.2311 6.73364C18.7429 6.31027 18.1766 6.14344 17.5875 6.06902C17.1389 6.01237 16.6052 6.00219 16 6.00038V5C16 3.34315 14.6569 2 13 2H11ZM14 6V5C14 4.44772 13.5523 4 13 4H11C10.4477 4 10 4.44772 10 5V6L14 6ZM9 8C9.55228 8 10 8.44772 10 9V11C10 11.5523 9.55228 12 9 12C8.44772 12 8 11.5523 8 11V9C8 8.44772 8.44772 8 9 8ZM16 9C16 8.44772 15.5523 8 15 8C14.4477 8 14 8.44772 14 9V11C14 11.5523 14.4477 12 15 12C15.5523 12 16 11.5523 16 11V9Z" fill="#323232"></path> </g></svg>
                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.supplier') }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif
                            @endif

                            @if (!empty($PermissionCurrencies) || !empty($PermissionExchangeRates) || !empty($PermissionPaymentMethods || !empty($PermissionExspense_type) || !empty($PermissionExspenses)))
                                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08] dongrek-font">
                                    <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.finance') }}</span>
                                </h2>
                                @if (!empty($PermissionCurrencies))
                                <li class="nav-item dongrek-font">
                                    <a href="{{ route('currencies.index') }}" class="group">
                                        <div class="flex items-center">
                                        <svg fill="#000000" viewBox="0 0 256 256" id="Flat" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g opacity="0.2"> <circle cx="128" cy="128" r="96"></circle> </g> <path d="M128,24A104,104,0,1,0,232,128,104.11791,104.11791,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.09957,88.09957,0,0,1,128,216Zm40-68a28.03146,28.03146,0,0,1-28,28h-4v8a8,8,0,0,1-16,0v-8H104a8,8,0,0,1,0-16h36a12,12,0,0,0,0-24H116a28,28,0,0,1,0-56h4V72a8,8,0,0,1,16,0v8h16a8,8,0,0,1,0,16H116a12,12,0,0,0,0,24h24A28.03146,28.03146,0,0,1,168,148Z"></path> </g></svg>


                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.currencies') }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif
                                @if (!empty($PermissionExchangeRates))
                                <li class="nav-item dongrek-font">
                                    <a href="{{ route('exchange-rates.index') }}" class="group">
                                        <div class="flex items-center">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M8 10H20L16 6" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M16 14L4 14L8 18" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>


                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.exchange_rate') }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif
                                @if (!empty($PermissionPaymentMethods))
                                <li class="nav-item dongrek-font">
                                    <a href="{{ route('payment_methods.index') }}" class="group">
                                        <div class="flex items-center">
                                        <svg fill="#000000" height="200px" width="200px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M493.714,66.178h-69.66H197.661h-69.663c-10.099,0-18.286,8.187-18.286,18.286v69.659v30.475H18.286 C8.187,184.598,0,192.785,0,202.884v224.653c0,10.099,8.187,18.286,18.286,18.286H335.24c10.099,0,18.286-8.187,18.286-18.286 v-63.565h70.528h69.66c10.099,0,18.286-8.186,18.286-18.286v-69.66V154.122V84.464C512,74.364,503.813,66.178,493.714,66.178z M146.285,102.75h29.729c-5.214,13.642-16.084,24.511-29.729,29.725V102.75z M316.954,409.249H36.572V294.311h280.383V409.249z M316.954,257.74H36.572v-36.571h280.383V257.74z M475.428,327.4h-29.727c5.213-13.642,16.082-24.511,29.727-29.725V327.4z M475.428,259.667c-33.843,7.186-60.548,33.891-67.735,67.734h-54.169v-71.918h0.001c14.81-9.817,23.609-24.527,23.607-40.409 c0-29.475-29.112-52.564-66.276-52.564c-21.971,0-42.032,8.405-54.207,22.088H146.285v-14.114 c33.844-7.186,60.55-33.891,67.737-67.734h193.672c7.188,33.842,33.892,60.545,67.735,67.734V259.667z M475.428,132.475 c-13.644-5.214-24.513-16.082-29.727-29.725h29.727V132.475z"></path> </g> </g> <g> <g> <path d="M152.383,330.883H91.429c-10.099,0-18.286,8.187-18.286,18.286c0,10.099,8.187,18.286,18.286,18.286h60.954 c10.099,0,18.286-8.187,18.286-18.286C170.668,339.07,162.481,330.883,152.383,330.883z"></path> </g> </g> </g></svg>

                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.payment_methods') }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif
                                @if (!empty($PermissionExspense_types))
                                <li class="nav-item dongrek-font">
                                    <a href="{{ route('expense_types.index') }}" class="group">
                                        <div class="flex items-center">
                                        <svg viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M731.15 585.97c-100.99 0-182.86 81.87-182.86 182.86s81.87 182.86 182.86 182.86 182.86-81.87 182.86-182.86-81.87-182.86-182.86-182.86z m0 292.57c-60.5 0-109.71-49.22-109.71-109.71s49.22-109.71 109.71-109.71c60.5 0 109.71 49.22 109.71 109.71s-49.21 109.71-109.71 109.71z" fill="#0F1F3C"></path><path d="M758.58 692.98h-54.86v87.27l69.4 68.79 38.6-38.97-53.14-52.68zM219.51 474.96h219.43v73.14H219.51z" fill="#0F1F3C"></path><path d="M182.61 365.86h585.62v179.48h73.14V145.21c0-39.96-32.5-72.48-72.46-72.48h-27.36c-29.18 0-55.04 16.73-65.88 42.59-5.71 13.64-27.82 13.66-33.57-0.02-10.86-25.86-36.71-42.57-65.88-42.57h-18.16c-29.18 0-55.04 16.73-65.88 42.59-5.71 13.64-27.82 13.66-33.57-0.02-10.86-25.86-36.71-42.57-65.88-42.57H375.3c-29.18 0-55.04 16.73-65.88 42.59-5.71 13.64-27.82 13.66-33.57-0.02-10.86-25.86-36.71-42.57-65.88-42.57H182.4c-39.96 0-72.48 32.52-72.48 72.48v805.14h401.21v-73.14H183.04l-0.43-511.35z m25.81-222.29c14.25 34.09 47.32 56.11 84.23 56.11 36.89 0 69.96-22.02 82.66-53.8l15.86-2.3c14.25 34.09 47.32 56.11 84.23 56.11 36.89 0 69.96-22.02 82.66-53.8l16.59-2.3c14.25 34.09 47.32 56.11 84.23 56.11 36.89 0 69.96-22.02 82.66-53.8l26.68-0.66v147.5H182.54l-0.13-146.84 26.01-2.33z" fill="#0F1F3C"></path></g></svg>


                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.expenses_type') }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif
                                @if (!empty($PermissionExspenses))
                                <li class="nav-item dongrek-font">
                                    <a href="{{ route('expenses.index') }}" class="group">
                                        <div class="flex items-center">
                                        <svg viewBox="0 0 24 24" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><defs><style>.cls-1{fill:none;stroke:#020202;stroke-miterlimit:10;stroke-width:1.91px;}</style></defs><path class="cls-1" d="M12,12H22.5a10.45,10.45,0,0,1-3.07,7.42L12,12V1.48A10.5,10.5,0,1,0,19.43,19.4"></path><path class="cls-1" d="M15.82,8.16h3.34a1.43,1.43,0,0,0,1.43-1.43h0A1.43,1.43,0,0,0,19.16,5.3h-1a1.44,1.44,0,0,1-1.43-1.44h0A1.43,1.43,0,0,1,18.2,2.43h3.35"></path><line class="cls-1" x1="18.68" y1="0.52" x2="18.68" y2="2.43"></line><line class="cls-1" x1="18.68" y1="8.16" x2="18.68" y2="10.07"></line></g></svg>
                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.expense') }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif
                            @endif

                            @if (!empty($PermissionCustomers) || !empty($PermissionCoupons) || !empty($PermissionSuppliers))
                                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08] dongrek-font">
                                    <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.set_up') }}</span>
                                </h2>
                                @if (!empty($PermissionUnits)|| !empty($PermissionToppings) || !empty($PermissionSizes))
                                    <li class="menu nav-item dongrek-font" >
                                        <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'forms'}" @click="activeDropdown === 'forms' ? activeDropdown = null : activeDropdown = 'forms'">
                                            <div class="flex items-center">
                                            <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#000000" d="M224 160a64 64 0 0 0-64 64v576a64 64 0 0 0 64 64h576a64 64 0 0 0 64-64V224a64 64 0 0 0-64-64H224zm0-64h576a128 128 0 0 1 128 128v576a128 128 0 0 1-128 128H224A128 128 0 0 1 96 800V224A128 128 0 0 1 224 96z"></path><path fill="#000000" d="M384 416a64 64 0 1 0 0-128 64 64 0 0 0 0 128zm0 64a128 128 0 1 1 0-256 128 128 0 0 1 0 256z"></path><path fill="#000000" d="M480 320h256q32 0 32 32t-32 32H480q-32 0-32-32t32-32zm160 416a64 64 0 1 0 0-128 64 64 0 0 0 0 128zm0 64a128 128 0 1 1 0-256 128 128 0 0 1 0 256z"></path><path fill="#000000" d="M288 640h256q32 0 32 32t-32 32H288q-32 0-32-32t32-32z"></path></g></svg>

                                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Set Up') }} </span>
                                            </div>
                                            <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'forms'}">
                                                <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                        </button>
                                        <ul x-cloak="" x-show="activeDropdown === 'forms'" x-collapse="" class="sub-menu text-gray-500">
                                            @if (!empty($PermissionUnits))
                                            <li>
                                                <a href="{{ route('units.index') }}">Unit</a>
                                            </li>
                                            @endif

                                            @if (!empty($PermissionToppings))
                                            <li>
                                                <a href="{{ route('toppings.index') }}">Topping</a>
                                            </li>
                                            @endif

                                            @if (!empty($PermissionSizes))
                                            <li>
                                                <a href="{{ route('sizes.index') }}">Size</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                @if (!empty($PermissionStores)|| !empty($PermissionStores))
                                    <li class="menu nav-item dongrek-font" >
                                        <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'settings'}" @click="activeDropdown === 'settings' ? activeDropdown = null : activeDropdown = 'settings'">
                                            <div class="flex items-center">
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M2 12.8799V11.1199C2 10.0799 2.85 9.21994 3.9 9.21994C5.71 9.21994 6.45 7.93994 5.54 6.36994C5.02 5.46994 5.33 4.29994 6.24 3.77994L7.97 2.78994C8.76 2.31994 9.78 2.59994 10.25 3.38994L10.36 3.57994C11.26 5.14994 12.74 5.14994 13.65 3.57994L13.76 3.38994C14.23 2.59994 15.25 2.31994 16.04 2.78994L17.77 3.77994C18.68 4.29994 18.99 5.46994 18.47 6.36994C17.56 7.93994 18.3 9.21994 20.11 9.21994C21.15 9.21994 22.01 10.0699 22.01 11.1199V12.8799C22.01 13.9199 21.16 14.7799 20.11 14.7799C18.3 14.7799 17.56 16.0599 18.47 17.6299C18.99 18.5399 18.68 19.6999 17.77 20.2199L16.04 21.2099C15.25 21.6799 14.23 21.3999 13.76 20.6099L13.65 20.4199C12.75 18.8499 11.27 18.8499 10.36 20.4199L10.25 20.6099C9.78 21.3999 8.76 21.6799 7.97 21.2099L6.24 20.2199C5.33 19.6999 5.02 18.5299 5.54 17.6299C6.45 16.0599 5.71 14.7799 3.9 14.7799C2.85 14.7799 2 13.9199 2 12.8799Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.settings') }} </span>
                                            </div>
                                            <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'settings'}">
                                                <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                        </button>
                                        <ul x-cloak="" x-show="activeDropdown === 'settings'" x-collapse="" class="sub-menu text-gray-500">
                                            @if (!empty($PermissionStores))
                                            <li>
                                                <a href="{{ route('stores.index') }}">Stores</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                <li class="menu nav-item dongrek-font">
                                    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'datatables'}" @click="activeDropdown === 'datatables' ? activeDropdown = null : activeDropdown = 'datatables'">
                                        <div class="flex items-center">
                                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>table</title> <path d="M18.76,6l2,4H3.24l2-4H18.76M20,4H4L1,10v2H3v7H5V16H19v3h2V12h2V10L20,4ZM5,14V12H19v2Z"></path> <rect width="24" height="24" fill="none"></rect> </g></svg>


                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Tables') }} </span>
                                        </div>
                                        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'datatables'}">
                                            <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                    </button>
                                    <ul x-cloak="" x-show="activeDropdown === 'datatables'" x-collapse="" class="sub-menu text-gray-500">
                                        <li>
                                            <a href="{{ route('group_tables.index') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Group') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('tables.index') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.Tables') }}</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if (!empty($PermissionReports))
                                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                                    <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </h2>
                                <li class="menu nav-item dongrek-font">
                                    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'users'}" @click="activeDropdown === 'users' ? activeDropdown = null : activeDropdown = 'users'">
                                        <div class="flex items-center">
                                        <svg fill="#000000" viewBox="0 0 32 32" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/1999/xlink"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Layer1"> <path d="M27,3c-0,-0.552 -0.448,-1 -1,-1l-20,0c-0.552,0 -1,0.448 -1,1l-0,26c0,0.552 0.448,1 1,1l20,0c0.552,0 1,-0.448 1,-1l-0,-26Zm-2,1l-0,24c-0,0 -18,0 -18,0c-0,-0 -0,-24 -0,-24l18,0Zm-9,10c-3.311,0 -6,2.689 -6,6c-0,3.311 2.689,6 6,6c3.311,0 6,-2.689 6,-6c-0,-3.311 -2.689,-6 -6,-6Zm-1,2.126c-1.724,0.445 -3,2.012 -3,3.874c-0,2.208 1.792,4 4,4c1.862,0 3.429,-1.276 3.874,-3l-3.874,0c-0.552,0 -1,-0.448 -1,-1l0,-3.874Zm-2,-4.126l6,0c0.552,0 1,-0.448 1,-1c0,-0.552 -0.448,-1 -1,-1l-6,0c-0.552,0 -1,0.448 -1,1c0,0.552 0.448,1 1,1Zm-2,-4l10,0c0.552,0 1,-0.448 1,-1c0,-0.552 -0.448,-1 -1,-1l-10,0c-0.552,0 -1,0.448 -1,1c0,0.552 0.448,1 1,1Z"></path> </g> </g></svg>

                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.reports') }}</span>
                                        </div>
                                        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'users'}">
                                            <svg width="16" height="16" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                    </button>
                                    <ul x-cloak="" x-show="activeDropdown === 'users'" x-collapse="" class="sub-menu text-gray-500 dongrek-font">
                                            <li>
                                            <a href="{{ route('reports.daily-sales') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.daily_sale') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('reports.monthly-sales') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.monthly_sale') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('reports.sales') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.sale') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('reports.sale-items') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.sale_items') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('reports.purchases') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.purchase_reports') }}</a>
                                        </li>

                                        <li>
                                            <a href="{{ route('reports.topsellingproduct') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.top_sell_product') }}</a>
                                        </li>

                                        <li>
                                            <a href="{{ route('reports.sales-by-category') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.category_sale') }}</a>
                                        </li>

                                        <li>
                                            <a href="{{ route('reports.shifts') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.shift_reports') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('reports.expenses') }}" class="{{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.expense_reports') }}</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if (!empty($PermissionUsers) || !empty($PermissionRoles))
                                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08] dongrek-font">
                                    <svg class="hidden h-5 w-4 flex-none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span  class=" {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.user') }}</span>
                                </h2>
                                @if (!empty($PermissionRoles))
                                <li class="nav-item">
                                    <a href="{{ route('roles.list') }}" class="group">
                                        <div class="flex items-center dongrek-font">
                                            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle opacity="0.5" cx="15" cy="6" r="3" fill="currentColor"></circle>
                                                <ellipse opacity="0.5" cx="16" cy="17" rx="5" ry="3" fill="currentColor"></ellipse>
                                                <circle cx="9.00098" cy="6" r="4" fill="currentColor"></circle>
                                                <ellipse cx="9.00098" cy="17.001" rx="7" ry="4" fill="currentColor"></ellipse>
                                            </svg>

                                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.permissions') }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif

                                @if (!empty($PermissionUsers))
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="group">
                                        <div class="flex items-center">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M5 21C5 17.134 8.13401 14 12 14C15.866 14 19 17.134 19 21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark dongrek-font {{ app()->getLocale() == 'en' ? 'font-en' : 'font-kh' }}">{{ __('text.users') }}</span>
                                    </div>
                                    </a>
                                </li>
                                @endif
                            @endif
                        </ul>
                        </ul>
            </div>
        </nav>
    </div>

<style>

    @import url('https://fonts.googleapis.com/css2?family=Dangrek&display=swap');
    .dongrek-font {
        font-family: 'Dangrek', 'Arial', sans-serif;
        letter-spacing: 0.01em;
        font-feature-settings: "kern" 1;
        text-rendering: optimizeLegibility;
        font-weight: 500;
    }

</style>
