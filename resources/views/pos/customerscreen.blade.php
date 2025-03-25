@extends('layouts.poss')
@section('content')

    <div class="min-h-screen flex bg-gray-100 dark:bg-gray-900">
        <!-- Left Side: Order Details -->
        <div
            class="w-full md:w-1/3 lg:w-1/3 bg-gray dark:bg-gray-800 p-4 shadow-md sticky top-0 h-screen overflow-y-auto rounded-[3px] flex flex-col">
            <div class="flex justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md shadow">
                <!-- Business Name -->
                <h1 class="text-base text-green-500 font-bold tracking-wide">SOUSDEY COFFEE</h1>
                <!-- Date & Icon -->
                <div class="flex items-center space-x-2 text-x font-semibold">
                    <span>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</span>
                    
                </div>
            </div>
            <div class="mb-1 relative p-1"></div>
            <!-- Order Items Table -->
            <div class="flex-grow border p-2 rounded-lg bg-white dark:bg-gray-800 mb-2 shadow-md overflow-y-auto">
                <table class="min-w-full text-xs md:text-sm">
                    <thead class="bg-blue-500 text-white sticky top-0 z-10 border p-3 rounded-lg">
                        <tr class="text-left font-semibold">
                            <th class="px-3 py-2">Name</th>
                            <th class="px-3 py-2 text-center">Size</th>
                            <th class="px-3 py-2 text-center">Topping</th>
                            <th class="px-3 py-2 text-center">Price</th>
                            <th class="px-3 py-2 text-center">Qty</th>
                            <th class="px-3 py-2 text-center">Discount</th>
                            <th class="px-3 py-2 text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody id="customerOrderTableBody">
                        <!-- Order rows will be inserted here -->
                    </tbody>
                </table>
            </div>
            <!-- Order Summary -->
            <div class="p-5 bg-blue-50 dark:bg-gray-700 rounded-md shadow-md text-xs mb-2">
                <!-- Row 1: Total Items & Subtotal -->
                <div class="grid grid-cols-2 gap-4 mb-2">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700  font-bold dark:text-gray-200">
                            Total Items:
                        </span>
                        <span id="customerTotalItems" class="font-bold text-gray-800 dark:text-gray-100">
                            0
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 font-bold dark:text-gray-200">
                            <b>|</b> Sub Total:
                        </span>
                        <span class="font-bold text-gray-800 dark:text-gray-100">
                            $<span id="customerSubtotal">0.00</span>
                        </span>
                    </div>
                </div>

                <!-- Row 2: Discount -->
                <div class="flex items-center justify-between mb-2 cursor-pointer
               text-blue-700 dark:text-blue-300 font-medium" onclick="showDiscountPopup()">
                    <span class="inline-flex items-center">
                        Discount
                        <!-- Badge with icon -->
                        <span class="ml-1 w-4 h-4 flex items-center justify-center rounded-full
                   bg-blue-500 text-white text-xs">
                            <i class="fas fa-dollar text-[10px]"></i>
                        </span>
                        :
                    </span>
                    <span id="customerDiscount">0 (0) </span>
                </div>

                <div class="border-t border-gray-300 dark:border-gray-600 my-2"></div>

                <!-- Row 3: Total Payable -->
                <div class="flex items-center justify-between text-sm font-bold text-green-600 dark:text-green-300">
                    <span>Total Payable:</span>
                    <span>
                        $<span id="customerTotalPayable">0.00</span>
                    </span>
                </div>
            </div>

        </div>

        <!-- Right Side: Background Image -->
        <div class="w-full md:w-2/3 lg:w-2/3 bg-gray-200 dark:bg-gray-800 p-4 shadow-md rounded-[4px] relative h-screen flex flex-col bg-cover bg-center"
            style="background-image: url('{{ asset('assets/images/auth/coffee.jpg') }}');">
            <!-- Optional: Additional overlay content can be added here -->
        </div>
    </div>

    <script>
        // Update the summary section on Screen Two.
        function updateCustomerOrderSummary(order) {
            document.getElementById("customerTotalItems").innerText = order.totalItems;
            document.getElementById("customerSubtotal").innerText = order.subtotal;
            document.getElementById("customerDiscount").innerText = order.discount;
            document.getElementById("customerTotalPayable").innerText = order.totalPayable;
        }

        // Update the order table on Screen Two.
        function updateCustomerOrderTable() {
            const orderRowsStr = localStorage.getItem("orderRows");
            const tbody = document.getElementById("customerOrderTableBody");
            tbody.innerHTML = ""; // Clear existing rows.
            if (orderRowsStr) {
                try {
                    const orderRows = JSON.parse(orderRowsStr);
                    orderRows.forEach(rowData => {
                        const tr = document.createElement("tr");
                        tr.innerHTML = `
                  <td class="px-3 py-2">${rowData.productName}</td>
                  <td class="px-3 py-2">${rowData.size}</td>
                  <td class="px-3 py-2">${rowData.topping}</td>
                  <td class="px-3 py-2">${rowData.price}</td>
                  <td class="px-3 py-2">${rowData.qty}</td>
                  <td class="px-3 py-2">${rowData.discount}</td>
                  <td class="px-3 py-2">${rowData.total}</td>
                `;
                        tbody.appendChild(tr);
                    });
                } catch (err) {
                    console.error("Error parsing order rows:", err);
                }
            }
        }

        // Listen for changes to localStorage from other tabs/windows.
        window.addEventListener("storage", function (e) {
            if (e.key === "orderSummary") {
                try {
                    const order = JSON.parse(e.newValue);
                    updateCustomerOrderSummary(order);
                } catch (err) {
                    console.error("Error parsing order summary:", err);
                }
            }
            if (e.key === "orderRows") {
                updateCustomerOrderTable();
            }
            // If Screen One signals a refresh, reload Screen Two.
            if (e.key === "screenOneRefresh") {
                window.location.reload();
            }
        });

        // On page load, clear all stored order data so the screen starts empty.
        window.addEventListener("load", function () {
            localStorage.removeItem("orderSummary");
            localStorage.removeItem("orderRows");
            updateCustomerOrderSummary({ totalItems: 0, subtotal: "0.00", discount: "0 (0)", totalPayable: "0.00" });
            updateCustomerOrderTable();
        });
        window.addEventListener("load", function () {
            const storedSummary = localStorage.getItem("orderSummary");
            if (storedSummary) {
                try {
                    updateCustomerOrderSummary(JSON.parse(storedSummary));
                } catch (err) {
                    console.error("Error loading stored order summary:", err);
                }
            }
            updateCustomerOrderTable();
        });
        // Function to show the QR modal with a static image.
        window.addEventListener("storage", function (e) {
            if (e.key === "qrPayload") {
                try {
                    const payload = JSON.parse(e.newValue);
                    showQRModal(payload);
                    localStorage.removeItem("qrPayload");
                } catch (err) {
                    console.error("Error parsing QR payload:", err);
                }
            }
            if (e.key === "closeQR") {
                closeQRModal();
            }
        });

        function showQRModal(payload) {
            // Generate a QR code image URL dynamically (or use a static image)
            const qrUrl = "{{ asset('assets/images/qrabas.jpg') }}"; // Adjust the path as needed
            let modal = document.getElementById("qrModal");

            if (!modal) {
                modal = document.createElement("div");
                modal.id = "qrModal";
                modal.style.position = "fixed";
                modal.style.top = "0";
                modal.style.left = "0";
                modal.style.width = "100%";
                modal.style.height = "100%";
                modal.style.backgroundColor = "rgba(0,0,0,0.5)";
                modal.style.display = "flex";
                modal.style.justifyContent = "center";
                modal.style.alignItems = "center";
                modal.style.zIndex = "1000";
                modal.style.overflow = "auto";
                document.body.appendChild(modal);
            }

            // Use a fade-in animation for the modal content.
            modal.innerHTML = `
            <div id="qrContent" style="
                background: white;
                padding: 20px;
                border-radius: 8px;
                text-align: center;
                max-width: 350px;
                width: 100%;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                font-family: 'Battambang', sans-serif;
                transform: scale(0.8);
                opacity: 0;
                transition: transform 0.3s ease, opacity 0.3s ease;
            ">
              <h2 style="font-size: 1rem; font-weight: bold; margin-bottom: 14px; color: #333;">
                ទឹកប្រាក់​​ត្រូវទូទាត់ | Payable: $${payload.totalPrice}
              </h2>
              <img src="${qrUrl}" alt="QR Code" style="width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;"/>
              <br/>
            </div>
        `;

            // Ensure the modal is displayed and animate its appearance.
            modal.style.display = "flex";
            const qrContent = document.getElementById("qrContent");

            // Force a reflow
            qrContent.offsetHeight;

            // Animate scale and fade-in
            qrContent.style.transform = "scale(1)";
            qrContent.style.opacity = "1";
        }

        function closeQRModal() {
            const modal = document.getElementById("qrModal");
            if (modal) {
                const qrContent = document.getElementById("qrContent");
                if (qrContent) {
                    qrContent.style.transform = "scale(0.8)";
                    qrContent.style.opacity = "0";
                    setTimeout(() => {
                        modal.style.display = "none";
                    }, 300);
                } else {
                    modal.style.display = "none";
                }
            }
        }


        window.addEventListener("storage", function (e) {
            if (e.key === "qrPayload") {
                try {
                    const payload = JSON.parse(e.newValue);
                    console.log("QR payload received on Screen Two:", payload);
                    showQRModal(payload);
                    // Optionally, remove the key so it doesn't fire repeatedly.
                    localStorage.removeItem("qrPayload");
                } catch (err) {
                    console.error("Error parsing QR payload:", err);
                }
            }
        });

        function applyThemeFromLocalStorage() {
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme === 'dark') {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  }

  // 2) When this screen loads, apply the stored theme
  window.addEventListener('DOMContentLoaded', applyThemeFromLocalStorage);

  // 3) If Screen One toggles dark mode, a 'storage' event is fired here
  window.addEventListener('storage', (e) => {
    if (e.key === 'theme') {
      applyThemeFromLocalStorage();
    }
  });

    </script>

@endsection