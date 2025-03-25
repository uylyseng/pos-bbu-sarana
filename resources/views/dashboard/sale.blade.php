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
        Swal.fire({
            title: "Error!",
            text: "{{ session('error') }}",
            icon: "error",
            confirmButtonText: "OK"
        });
    </script>
@endif
    <div class="container">
        <div class="pt-5">
            <div class="mb-6 grid grid-cols-1 gap-6 text-white sm:grid-cols-2 xl:grid-cols-4">
                <!-- Users Visit -->
                <div class="panel bg-gradient-to-r from-cyan-500 to-cyan-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Total Sale</div>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">

                            </a>

                        </div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">
                            ${{ number_format($totalSales, 2) }}
                        </div>

                    </div>
                    <div class="mt-5 flex items-center font-semibold">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                            <path opacity="0.5"
                                d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                                stroke="currentColor" stroke-width="1.5"></path>
                            <path
                                d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                                stroke="currentColor" stroke-width="1.5"></path>
                        </svg>
                        <span>Last Month: ${{ number_format($totalSales, 2) }}</span>
                    </div>
                </div>


                <div class="panel bg-gradient-to-r from-fuchsia-500 to-fuchsia-400">

                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Total Purchase</div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3" id="totalPurchase">
                            $0.00
                        </div>
                        <div class="badge bg-white/30 text-white" id="totalPurchaseDifference">
                            - $0.00
                        </div>
                    </div>
                    <div class="mt-5 flex items-center font-semibold text-white">
                        <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                            <path opacity="0.5"
                                d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                                stroke="currentColor" stroke-width="1.5"></path>
                            <path
                                d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                                stroke="currentColor" stroke-width="1.5"></path>
                        </svg>
                        Last Week :<span id="totalPurchaseLastWeek">$0.00</span>
                    </div>
                </div>
                <div class="panel bg-gradient-to-r from-violet-500 to-violet-400 p-5 rounded-lg shadow-md">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Total Expense</div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3" id="totalExpense">
                            $0.00
                        </div>
                        <div class="badge bg-white/30 text-white" id="totalExpenseDifference">
                            - $0.00
                        </div>
                    </div>
                    <div class="mt-5 flex items-center font-semibold text-white">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                            <path opacity="0.5"
                                d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                                stroke="currentColor" stroke-width="1.5"></path>
                            <path
                                d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                                stroke="currentColor" stroke-width="1.5"></path>
                        </svg>
                        Last Week :<span id="totalExpenseLastWeek">$0.00</span>
                    </div>
                </div>

                <!-- Most Sold Product -->
                <!-- <div class="panel bg-gradient-to-r from-violet-500 to-violet-400 p-5 rounded-lg shadow-md">
        <div class="flex justify-between items-center">
            <div class="text-md font-semibold text-white">Most Sold Product</div>
            <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                <button @click="open = !open" class="focus:outline-none">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white opacity-70 hover:opacity-80">
                        <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                        <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                        <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                    </svg>
                </button>
                <ul x-cloak x-show="open" x-transition class="absolute right-0 mt-2 w-32 bg-white shadow-lg rounded-md text-black">
                    <li><a href="{{ route('reports.topsellingproduct') }}" class="block px-4 py-2 hover:bg-gray-100">View Report</a></li>
                    <li><a href="javascript:;" class="block px-4 py-2 hover:bg-gray-100">Edit Report</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-5 flex items-center">
            <div class="text-3xl font-bold text-white mr-3">
                {{ $mostSoldProduct->product_name ?? 'No product' }}
            </div>
            <div class="badge bg-white/30 px-3 py-1 text-white rounded-full">
                {{ $mostSoldProduct->total_quantity ?? 0 }} sold
            </div>
        </div>

        <div class="mt-5 flex items-center font-semibold text-white">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2">
                <path opacity="0.5"
                    d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                    stroke="currentColor" stroke-width="1.5"></path>
                <path
                    d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                    stroke="currentColor" stroke-width="1.5"></path>
            </svg>
            <span>Last Week: {{ $mostSoldProduct->last_week_sold ?? 0 }}</span>
        </div>
    </div> -->


                <!-- Time On-Site -->
                <!-- Low Stock -->
                <!-- <div class="panel bg-gradient-to-r from-blue-500 to-blue-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Low Stock</div>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">
                                <svg width="24" height="24" viewbox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70 hover:opacity-80">
                                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5">
                                    </circle>
                                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                                </svg>
                            </a>
                            <ul x-cloak="" x-show="open" x-transition="" x-transition.duration.300ms=""
                                class="text-black ltr:right-0 rtl:left-0 dark:text-white-dark">
                                <li><a href="{{ route('reports.topsellingproduct') }}" @click="toggle">View Report</a></li>
                                <li><a href="javascript:;" @click="toggle">Edit Report</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">
                            {{ $lowStockCount ?? 0 }}
                        </div>
                        <div class="badge bg-white/30">Products</div>
                    </div>

                </div> -->
            <!-- </div> -->
            <div class="panel bg-gradient-to-r from-blue-500 to-blue-400">
    <div class="flex justify-between">
        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Profit &amp; Loss</div>
        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
            <a href="javascript:;" @click="toggle">

            </a>

        </div>
    </div>

    <!-- Net difference and status -->
    <div class="mt-5 flex items-center">
        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3" id="netValue">
            0
        </div>
        <div class="badge bg-white/30" id="profitLossStatus">
            Profit
        </div>
    </div>
</div>
</div>


            <!-- Total Purchase Panel -->

            <div class="mb-6 grid gap-6 lg:grid-cols-3">
                <div class="panel h-full p-0 lg:col-span-2">
                    <div
                        class="mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 dark:border-[#1b2e4b] dark:text-white-light">
                        <h5 class="text-lg font-semibold">Monthly Sale</h5>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">
                                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5">
                                    </circle>
                                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                                </svg>
                            </a>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                                class="ltr:right-0 rtl:left-0">
                                <li><a href="javascript:;" @click="toggle">View</a></li>
                                <li><a href="javascript:;" @click="toggle">Update</a></li>
                                <li><a href="javascript:;" @click="toggle">Download</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Chart Container -->
                    <div x-ref="uniqueVisitorSeries" class="overflow-hidden">
                        <!-- Loader while data is fetched -->
                        <div
                            class="grid min-h-[360px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                            <span
                                class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
                        </div>
                    </div>
                </div>
                <div class="panel h-full">
                    <div class="mb-5 flex items-center">
                        <h5 class="text-lg font-semibold dark:text-white-light">Sales By Category</h5>
                    </div>
                    <div class="overflow-hidden">
                        <div x-ref="salesByCategory" class="rounded-lg bg-white dark:bg-black" id="chart">
                            <!-- loader -->
                            <div
                                class="grid min-h-[353px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                                <span
                                    class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full p-0 lg:col-span-2" x-data="chartData" x-init="initChart()">

                    <!-- Heading -->
                    <h2 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white px-4 py-2">
                        Most Sale Product
                    </h2>

                    <!-- Chart container -->
                    <div x-ref="simpleColumnStacked" class="rounded-lg bg-white dark:bg-black overflow-hidden"
                        style="height: 350px;">
                    </div>

                    <!-- Optional code snippet block -->
                    <template x-if="codeArr.includes('code4')">
                        <pre class="code overflow-auto rounded-md !bg-[#191e3a] p-4 text-white">
                <!-- Your code snippet here -->
            </pre>
                    </template>
                </div>

                <div class="panel h-full">
    <div class="mb-5 flex items-center dark:text-white-light">
        <h5 class="text-lg font-semibold">Summary</h5>
        <!-- Example dropdown (omitted for brevity) -->
    </div>

    <div class="space-y-9">
        <!-- Income -->
        <div class="flex items-center">
            <!-- Icon container -->
            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                <div class="grid h-9 w-9 place-content-center rounded-full bg-secondary-light text-secondary dark:bg-secondary dark:text-secondary-light">
                    <!-- Example Icon for Income -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3.74157 18.5545C4.94119 20 7.17389 20 11.6393 20H12.3605C16.8259 20 19.0586 20 20.2582 18.5545M3.74157 18.5545C2.54194 17.1091 2.9534 14.9146 3.77633 10.5257C4.36155 7.40452 4.65416 5.84393 5.76506 4.92196M3.74157 18.5545C3.74156 18.5545 3.74157 18.5545 3.74157 18.5545ZM20.2582 18.5545C21.4578 17.1091 21.0464 14.9146 20.2235 10.5257C19.6382 7.40452 19.3456 5.84393 18.2347 4.92196M20.2582 18.5545C20.2582 18.5545 20.2582 18.5545 20.2582 18.5545ZM18.2347 4.92196C17.1238 4 15.5361 4 12.3605 4H11.6393C8.46374 4 6.87596 4 5.76506 4.92196M18.2347 4.92196C18.2347 4.92196 18.2347 4.92196 18.2347 4.92196ZM5.76506 4.92196C5.76506 4.92196 5.76506 4.92196 5.76506 4.92196Z"
                            stroke="currentColor" stroke-width="1.5">
                        </path>
                        <path opacity="0.5"
                            d="M9.1709 8C9.58273 9.16519 10.694 10 12.0002 10C13.3064 10 14.4177 9.16519 14.8295 8"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                        </path>
                    </svg>
                </div>
            </div>
            <!-- Bar + label -->
            <div class="flex-1">
                <div class="mb-2 flex font-semibold text-white-dark">
                    <h6>Income</h6>
                    <p class="ltr:ml-auto rtl:mr-auto" id="incomeValue">$0.00</p>
                </div>
                <div class="h-2 rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                    <div
                        id="incomeBar"
                        class="h-full rounded-full bg-gradient-to-r from-[#7579ff] to-[#b224ef]"
                        style="width: 0%"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Profit -->
        <div class="flex items-center">
            <!-- Icon container -->
            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                <div class="grid h-9 w-9 place-content-center rounded-full bg-success-light text-success dark:bg-success dark:text-success-light">
                    <!-- Example Icon for Profit -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                            stroke="currentColor" stroke-width="1.5">
                        </path>
                        <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                            transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                            stroke-width="1.5">
                        </circle>
                        <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round">
                        </path>
                    </svg>
                </div>
            </div>
            <!-- Bar + label -->
            <div class="flex-1">
                <div class="mb-2 flex font-semibold text-white-dark">
                    <h6>Profit</h6>
                    <p class="ltr:ml-auto rtl:mr-auto" id="profitValue">$0.00</p>
                </div>
                <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                    <div
                        id="profitBar"
                        class="h-full rounded-full bg-gradient-to-r from-[#3cba92] to-[#0ba360]"
                        style="width: 0%"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Purchase -->
        <div class="flex items-center">
            <!-- Icon container -->
            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                <div class="grid h-9 w-9 place-content-center rounded-full bg-info-light text-info dark:bg-info dark:text-info-light">
                    <!-- Example Icon for Purchase -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 5H4H22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <path
                            d="M6 5L7.26304 17.0533C7.38061 18.1635 7.43939 18.7187 7.66125 19.1344C7.85673 19.4935 8.15439 19.7794 8.51668 19.9514C8.9326 20.1429 9.48497 20.1429 10.5897 20.1429H17.4103C18.515 20.1429 19.0674 20.1429 19.4833 19.9514C19.8456 19.7794 20.1433 19.4935 20.3387 19.1344C20.5606 18.7187 20.6194 18.1635 20.737 17.0533L22 5"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                        </path>
                        <path
                            d="M9 11H15"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                </div>
            </div>
            <!-- Bar + label -->
            <div class="flex-1">
                <div class="mb-2 flex font-semibold text-white-dark">
                    <h6>Purchase</h6>
                    <p class="ltr:ml-auto rtl:mr-auto" id="purchaseValue">$0.00</p>
                </div>
                <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                    <div
                        id="purchaseBar"
                        class="h-full rounded-full bg-gradient-to-r from-[#17EAD9] to-[#6078EA]"
                        style="width: 0%"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Expenses -->
        <div class="flex items-center">
            <!-- Icon container -->
            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                <div class="grid h-9 w-9 place-content-center rounded-full bg-warning-light text-warning dark:bg-warning dark:text-warning-light">
                    <!-- Example Icon for Expenses -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                            stroke="currentColor" stroke-width="1.5">
                        </path>
                        <path opacity="0.5" d="M10 16H6" stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round">
                        </path>
                        <path opacity="0.5" d="M14 16H12.5" stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round">
                        </path>
                        <path opacity="0.5" d="M2 10L22 10" stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round">
                        </path>
                    </svg>
                </div>
            </div>
            <!-- Bar + label -->
            <div class="flex-1">
                <div class="mb-2 flex font-semibold text-white-dark">
                    <h6>Expenses</h6>
                    <p class="ltr:ml-auto rtl:mr-auto" id="expensesValue">$0.00</p>
                </div>
                <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                    <div
                        id="expensesBar"
                        class="h-full rounded-full bg-gradient-to-r from-[#f09819] to-[#ff5858]"
                        style="width: 0%"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</div>




            </div>


            <div x-ref="simpleColumnStacked" class="bg-white dark:bg-black rounded-lg"></div>



        </div>
        <!-- Include Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
document.addEventListener('DOMContentLoaded', function() {
    // Adjust if your route is different
    fetch('/dashboard/profit-loss')
        .then(response => response.json())
        .then(data => {
            // data: { totalIncome, totalExpenses, net, status }

            // 1) Update the net difference
            document.getElementById('netValue').textContent = data.net;

            // 2) Update the status (Profit/Loss/Break-even)
            document.getElementById('profitLossStatus').textContent = data.status;
        })
        .catch(error => {
            console.error('Error fetching profit/loss data:', error);
        });
});

document.addEventListener('DOMContentLoaded', function() {
    fetch('/dashboard/summary') // Adjust if your route is different
        .then(response => response.json())
        .then(data => {
            // Data structure: { income, profit, purchase, expenses }

            // 1) Update numeric text
            document.getElementById('incomeValue').textContent   = `$${data.income.toLocaleString()}`;
            document.getElementById('profitValue').textContent   = `$${data.profit.toLocaleString()}`;
            document.getElementById('purchaseValue').textContent = `$${data.purchase.toLocaleString()}`;
            document.getElementById('expensesValue').textContent = `$${data.expenses.toLocaleString()}`;

            // 2) Find the maximum to scale the bars
            let maxValue = Math.max(data.income, data.profit, data.purchase, data.expenses);
            if (maxValue === 0) maxValue = 1; // avoid dividing by zero

            // 3) Calculate each bar's width as a % of maxValue
            let incomePct   = (data.income   / maxValue) * 100;
            let profitPct   = (data.profit   / maxValue) * 100;
            let purchasePct = (data.purchase / maxValue) * 100;
            let expensePct  = (data.expenses / maxValue) * 100;

            // 4) Update the bar widths
            document.getElementById('incomeBar').style.width   = `${incomePct}%`;
            document.getElementById('profitBar').style.width   = `${profitPct}%`;
            document.getElementById('purchaseBar').style.width = `${purchasePct}%`;
            document.getElementById('expensesBar').style.width = `${expensePct}%`;
        })
        .catch(err => console.error('Error fetching summary data:', err));
});



            document.addEventListener('alpine:init', () => {
                Alpine.data('chartData', () => ({
                    codeArr: [],
                    chart: null,

                    initChart() {
                        fetch('/dashboard/most-sell-product')
                            .then(response => response.json())
                            .then(data => {
                                /*
                                  data structure from the controller:
                                  {
                                    "data": [
                                      { "product": "Product A", "quantity": 123, "total_price": 456.78 },
                                      ...
                                    ],
                                    "grandTotalQuantity": 999,
                                    "grandTotalPrice": 1500.25
                                  }
                                */

                                // Extract arrays for ApexCharts
                                let productNames = data.data.map(item => item.product);
                                let productQty = data.data.map(item => parseFloat(item.quantity));
                                let productPrices = data.data.map(item => parseFloat(item.total_price));

                                let options = {
                                    chart: {
                                        type: 'bar',
                                        height: 350
                                    },
                                    // Two series: one for Quantity, one for Total Price
                                    series: [
                                        {
                                            name: 'Quantity',
                                            data: productQty
                                        },
                                        {
                                            name: 'Total Price ($)',
                                            data: productPrices
                                        }
                                    ],
                                    xaxis: {
                                        categories: productNames
                                    },
                                    plotOptions: {
                                        bar: {
                                            borderRadius: 4
                                        }
                                    },
                                    legend: {
                                        position: 'top'
                                    },
                                    // Provide two colors for the two series
                                    colors: ['#008FFB', '#FEB019'],
                                };

                                // Render the chart
                                this.chart = new ApexCharts(this.$refs.simpleColumnStacked, options);
                                this.chart.render();
                            })
                            .catch(error => {
                                console.error('Error fetching Most Sold Products:', error);
                            });
                    }
                }))
            });







            document.addEventListener('DOMContentLoaded', function () {
                // Fetch monthly sale data from your web endpoint
                fetch('/web/monthly-sale')
                    .then(response => response.json())
                    .then(data => {
                        // Assume API returns an object with labels and sales arrays
                        // Example: { "labels": ["Jan", "Feb", "Mar", ...], "sales": [1200, 1500, 1800, ...] }

                        // Replace loader with a canvas element for the chart
                        const chartContainer = document.querySelector('[x-ref="uniqueVisitorSeries"]');
                        chartContainer.innerHTML = '<canvas id="monthlySaleChart"></canvas>';

                        // Initialize Chart.js on the canvas
                        const ctx = document.getElementById('monthlySaleChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Monthly Sale',
                                    data: data.sales,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    fill: true,
                                    tension: 0.3
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function (value) {
                                                return '$' + value;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching monthly sale data:', error);
                    });
            });
            document.addEventListener('DOMContentLoaded', function () {
                // 1. Fetch data (replace with your actual endpoint).
                fetch('/web/sales-by-category')
                    .then(response => response.json())
                    .then(data => {
                        // Example of expected data structure:
                        // {
                        //   "categories": [
                        //     { "category": "Drinks", "total": 92.50 },
                        //     { "category": "Coffee", "total": 48.70 }
                        //   ],
                        //   "grandTotal": 141.20
                        // }

                        console.log('Fetched data:', data);

                        // 2. Extract arrays for the donut slices
                        const categories = data.categories || [];
                        const labels = categories.map(item => item.category);
                        // Make sure each 'total' is a valid number
                        const series = categories.map(item => parseFloat(item.total) || 0);

                        // 3. Donut chart configuration
                        const options = {
                            chart: {
                                type: 'donut',
                                height: 350
                            },
                            labels: labels,
                            series: series,
                            legend: {
                                position: 'bottom'
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '70%',
                                        labels: {
                                            show: true,
                                            name: { show: false },
                                            value: { show: false },
                                            total: {
                                                show: true,
                                                label: 'Total',
                                                fontSize: '16px',
                                                formatter: function (w) {
                                                    // Use the grandTotal from the response
                                                    return data.grandTotal || 0;
                                                }
                                            }
                                        }
                                    }
                                }
                            },
                            dataLabels: {
                                enabled: true
                            }
                        };

                        // 4. Render the chart
                        const chartContainer = document.querySelector('#chart');
                        chartContainer.innerHTML = ''; // Remove loader
                        const chart = new ApexCharts(chartContainer, options);
                        chart.render();
                    })
                    .catch(error => {
                        console.error('Error fetching sales by category data:', error);
                    });
            });
            document.addEventListener('DOMContentLoaded', function () {
                // Fetch recent orders from the endpoint
                fetch('/web/recent-orders')
                    .then(response => response.json())
                    .then(data => {
                        // 'data' is an array of objects:
                        // [
                        //   {
                        //     "customer": "John Doe",
                        //     "product": "Laptop",
                        //     "quantity": 2,
                        //     "price": "1500.00",
                        //     "status": "Paid",
                        //     "created_at": "2023-09-01 12:00:00"
                        //   },
                        //   ...
                        // ]

                        const tbody = document.getElementById('recent-orders-body');
                        tbody.innerHTML = ''; // Clear existing rows (if any)

                        data.forEach(order => {
                            // Create a table row
                            const tr = document.createElement('tr');
                            tr.classList.add('group', 'text-white-dark', 'hover:text-black', 'dark:hover:text-white-light/90');

                            // Price formatting (convert string to float, then to fixed decimal)
                            const formattedPrice = parseFloat(order.price).toFixed(2);

                            // Insert row cells
                            tr.innerHTML = `
                        <td class="text-black dark:text-white">${order.customers}</td>
                        <td class="text-primary">${order.product}</td>
                        <td>${order.quantity}</td>
                        <td>$${formattedPrice}</td>
                        <td class="ltr:rounded-l-md rtl:rounded-r-md w-32 whitespace-nowrap">
        <span class="badge bg-success shadow-md dark:group-hover:bg-transparent px-2 py-1">
            ${order.status}
        </span>
    </td>

                    `;

                            tbody.appendChild(tr);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching recent orders:', error);
                    });
            });

            document.addEventListener('DOMContentLoaded', function () {
                // Fetch total purchase data from the controller
                fetch('/web/total-purchase')
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.totalPurchase !== undefined && data.totalPurchaseDifference !== undefined && data.totalPurchaseLastWeek !== undefined) {
                            // Update DOM with total purchase data
                            document.getElementById('totalPurchase').textContent = `$${parseFloat(data.totalPurchase).toFixed(2)}`;
                            document.getElementById('totalPurchaseDifference').textContent = `- $${parseFloat(data.totalPurchaseDifference).toFixed(2)}`;
                            document.getElementById('totalPurchaseLastWeek').textContent = `$${parseFloat(data.totalPurchaseLastWeek).toFixed(2)}`;
                        } else {
                            console.error('Invalid data structure:', data);
                        }
                    })
                    .catch(error => console.error('Error fetching total purchase data:', error));
            });

            document.addEventListener('DOMContentLoaded', function () {
                // Fetch total expense data from the controller
                fetch('/dashboard/total-expense')
                    .then(response => response.json())
                    .then(data => {
                        if (
                            data &&
                            data.totalExpense !== undefined &&
                            data.totalExpenseDifference !== undefined &&
                            data.totalExpenseLastWeek !== undefined
                        ) {
                            // Update DOM with total expense data
                            document.getElementById('totalExpense').textContent =
                                `$${parseFloat(data.totalExpense).toFixed(2)}`;
                            document.getElementById('totalExpenseDifference').textContent =
                                `- $${parseFloat(data.totalExpenseDifference).toFixed(2)}`;
                            document.getElementById('totalExpenseLastWeek').textContent =
                                `$${parseFloat(data.totalExpenseLastWeek).toFixed(2)}`;
                        } else {
                            console.error('Invalid data structure:', data);
                        }
                    })
                    .catch(error => console.error('Error fetching total expense data:', error));
            });


        </script>

@endsection
