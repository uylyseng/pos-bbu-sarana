<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>
        Swal.fire({
            title: "Success!",
            text: "<?php echo e(session('success')); ?>",
            icon: "success",
            timer: 1000,
            confirmButtonText: "OK"
        });
    </script>
<?php endif; ?>

<?php if(session('error')): ?>
    <script>
        Swal.fire({
            title: "Error!",
            text: "<?php echo e(session('error')); ?>",
            icon: "error",
            confirmButtonText: "OK"
        });
    </script>
<?php endif; ?>

    <div class="container">
        <div class="pt-5">
            <div class="mb-6 grid grid-cols-1 gap-6 text-white sm:grid-cols-2 xl:grid-cols-4">
                <!-- Users Visit -->
                <div class="panel bg-gradient-to-r from-cyan-500 to-cyan-400">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Total Sale Today</div>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">

                            </a>

                        </div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3">
                            $<?php echo e(number_format($totalSalesToday, 2)); ?>

                        </div>
                    </div>
                </div>



                <!-- Most Sold Product -->
                <div class="panel bg-gradient-to-r from-violet-500 to-violet-400 p-4">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">
                            Most Sold Product
                        </div>
                    </div>

                    <!-- Product name & total sold -->
                    <div class="mt-5 flex items-center">
                        <!-- Big product name -->
                        <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3" id="mostSoldProductName">
                            No product
                        </div>
                        <!-- Badge showing total sold -->
                        <div class="badge bg-white/30">
                            <span id="mostSoldProductTotalSold">0</span> sold
                        </div>
                    </div>

                    <!-- "Today" sold field -->
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
                        <span>Today: <span id="mostSoldProductToday">0</span></span>
                    </div>
                </div>


                <div class="panel bg-gradient-to-r from-blue-500 to-blue-400 p-4">
                    <!-- Panel Header -->
                    <div class="flex justify-between">
                        <div class="text-md font-semibold">
                            Total Work Time
                        </div>
                        <!-- Optional dropdown/icons can go here -->
                    </div>

                    <!-- Total Hours -->
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold mr-3" id="totalHourValue">0</div>
                        <div class="badge bg-white/30">hrs</div>
                    </div>

                    <!-- Day & Week (each on its own line) -->
                    <div class="mt-5 flex items-center space-x-6 font-semibold">
                        <!-- Day -->
                        <div class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 shrink-0 mr-1">
                                <path opacity="0.5"
                                    d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                                    stroke="currentColor" stroke-width="1.5">
                                </path>
                                <path
                                    d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                                    stroke="currentColor" stroke-width="1.5">
                                </path>
                            </svg>
                            Day : <span id="dayValue" class="ml-1">0</span>
                        </div>

                        <!-- Week -->
                        <div class="flex items-center ml-2">

                            Week :<span id="weekValue" class="ml-1">0</span>
                        </div>
                    </div>

                </div>




                <div class="panel bg-gradient-to-r from-fuchsia-500 to-fuchsia-400 p-4">
                    <div class="flex justify-between">
                        <div class="text-md font-semibold">Low Stock</div>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">
                            </a>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                                class="text-black ltr:right-0 rtl:left-0 dark:text-white-dark">
                            </ul>
                        </div>
                    </div>
                    <!-- Main low stock count display -->
                    <div class="mt-5 flex items-center">
                        <div class="text-3xl font-bold mr-3" id="lowStockCountValue">
                            <?php echo e($lowStockCount ?? 0); ?>

                        </div>
                        <div class="badge bg-white/30">
                            Products
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-6 grid gap-6 lg:grid-cols-3">
    <div class="panel h-full p-0 lg:col-span-2">
        <!-- Header -->
        <div class="mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 dark:border-[#1b2e4b] dark:text-white-light">
            <h5 class="text-lg font-semibold">Weekly Sale (Mon - Sun)</h5>
        </div>

        <!-- Chart Container -->
        <div class="overflow-hidden" x-ref="uniqueVisitorSeries">
            <!-- Loader while data is fetched -->
            <div class="grid min-h-[360px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                <span class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
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

                </div>





            </div>
            <!-- Include Chart.js CDN -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Adjust this URL if your route is different
                    fetch('/dashboard/low-stock-count')
                        .then(response => response.json())
                        .then(data => {
                            // data might look like: { "low_stock_count": 5 }
                            document.getElementById('lowStockCountValue').textContent =
                                data.low_stock_count || 0;
                        })
                        .catch(error => {
                            console.error('Error fetching low stock count:', error);
                        });
                });
                document.addEventListener('DOMContentLoaded', function () {
                    // Adjust if your route is different
                    fetch('/dashboard/most-sold-product-today')
                        .then(response => response.json())
                        .then(data => {
                            // data might be: { product_name, total_quantity, today_sold }
                            document.getElementById('mostSoldProductName').textContent =
                                data.product_name || 'No product';
                            document.getElementById('mostSoldProductTotalSold').textContent =
                                data.total_quantity || 0;
                            document.getElementById('mostSoldProductToday').textContent =
                                data.today_sold || 0;
                        })
                        .catch(error => {
                            console.error('Error fetching most sold product today:', error);
                        });
                });
                document.addEventListener('DOMContentLoaded', function () {
                    // Adjust to your actual route, e.g., '/dashboard/work-time-summary'
                    fetch('/dashboard/work-time-summary')
                        .then(response => response.json())
                        .then(data => {
                            // data example: { "total_hour": 125.50, "amount_day": 2, "amount_week": 1 }

                            // 1) total_hour
                            document.getElementById('totalHourValue').textContent = data.total_hour;

                            // 2) amount_day
                            document.getElementById('dayValue').textContent = data.amount_day;

                            // 3) amount_week
                            document.getElementById('weekValue').textContent = data.amount_week;
                        })
                        .catch(error => console.error('Error fetching work time summary:', error));
                });
                document.addEventListener('DOMContentLoaded', function () {
                    // Fetch weekly sales data (Mon - Sun) from your endpoint
                    fetch('/web/weekly-sale')
                        .then(response => response.json())
                        .then(data => {
                            // Expected data format, for example:
                            // {
                            //   "labels": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
                            //   "sales":  [200, 150, 0, 300, 100, 50, 500]
                            // }

                            const chartContainer = document.querySelector('[x-ref="uniqueVisitorSeries"]');
                            chartContainer.innerHTML = '<canvas id="weeklySaleChart"></canvas>';

                            // Initialize ApexCharts (donut, line, bar, etc.). Here’s a simple bar chart:
                            const options = {
                                chart: {
                                    type: 'bar',
                                    height: 360
                                },
                                series: [{
                                    name: 'Sales',
                                    data: data.sales
                                }],
                                xaxis: {
                                    categories: data.labels
                                },

                            };

                            const chart = new ApexCharts(chartContainer, options);
                            chart.render();
                        })
                        .catch(error => {
                            console.error('Error fetching weekly sale data:', error);
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
                                                name: {
                                                    show: false
                                                },
                                                value: {
                                                    show: false
                                                },
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
                                tr.classList.add('group', 'text-white-dark', 'hover:text-black',
                                    'dark:hover:text-white-light/90');

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
            </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lyseng/pos-bbu-sarana/resources/views/dashboard/cashier.blade.php ENDPATH**/ ?>