<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานค่าใช้จ่าย - ระบบบันทึกค่าใช้จ่าย</title>
    
    <!-- Tailwind CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <!-- Google Fonts - Thai font support -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card-shadow {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="gradient-bg">
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                    <i class="fas fa-chart-bar text-2xl text-purple-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">รายงานค่าใช้จ่าย</h1>
                <p class="text-white text-opacity-90">วิเคราะห์และสรุปข้อมูลค่าใช้จ่ายของคุณ</p>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <a href="{{ route('expenses.index') }}" class="bg-white bg-opacity-20 text-white px-6 py-3 rounded-lg hover:bg-opacity-30 transition duration-200 flex items-center">
                    <i class="fas fa-list mr-2"></i>
                    รายการค่าใช้จ่าย
                </a>
                <a href="{{ route('expenses.create') }}" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    เพิ่มค่าใช้จ่าย
                </a>
                <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-200 flex items-center">
                    <i class="fas fa-cog mr-2"></i>
                    จัดการหมวดหมู่
                </a>
            </div>

            <!-- Report Filters Card -->
            <div class="bg-white rounded-2xl p-6 card-shadow mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-filter text-blue-500 mr-2"></i>
                    ตัวกรองรายงาน
                </h2>
                
                <form id="reportFilterForm" class="space-y-4">
                    @csrf
                    <!-- Report Type Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Today -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition duration-200">
                            <input type="radio" name="report_type" value="today" class="mr-3 text-blue-600" checked>
                            <div>
                                <div class="font-medium text-gray-800">วันนี้</div>
                                <div class="text-sm text-gray-600">รายงานค่าใช้จ่ายประจำวัน</div>
                            </div>
                        </label>

                        <!-- Specific Date -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition duration-200">
                            <input type="radio" name="report_type" value="specific_date" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium text-gray-800">วันที่เฉพาะ</div>
                                <div class="text-sm text-gray-600">เลือกวันที่ที่ต้องการ</div>
                            </div>
                        </label>

                        <!-- Date Range -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition duration-200">
                            <input type="radio" name="report_type" value="date_range" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium text-gray-800">ช่วงวันที่</div>
                                <div class="text-sm text-gray-600">ตั้งแต่...จนถึง...</div>
                            </div>
                        </label>

                        <!-- Monthly -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition duration-200">
                            <input type="radio" name="report_type" value="monthly" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium text-gray-800">รายเดือน</div>
                                <div class="text-sm text-gray-600">สรุปรายเดือน</div>
                            </div>
                        </label>

                        <!-- Month Range -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition duration-200">
                            <input type="radio" name="report_type" value="month_range" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium text-gray-800">ช่วงเดือน</div>
                                <div class="text-sm text-gray-600">ตั้งแต่เดือน...ถึงเดือน...</div>
                            </div>
                        </label>

                        <!-- Yearly -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition duration-200">
                            <input type="radio" name="report_type" value="yearly" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium text-gray-800">รายปี</div>
                                <div class="text-sm text-gray-600">สรุปรายปี</div>
                            </div>
                        </label>
                    </div>

                    <!-- Dynamic Filter Fields -->
                    <div id="filterFields" class="space-y-4">
                        <!-- Specific Date Field -->
                        <div id="specificDateField" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">เลือกวันที่</label>
                            <input type="date" name="specific_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                        </div>

                        <!-- Date Range Fields -->
                        <div id="dateRangeFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ตั้งแต่</label>
                                <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">จนถึง</label>
                                <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                            </div>
                        </div>

                        <!-- Monthly Field -->
                        <div id="monthlyField" class="hidden grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ปี</label>
                                <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i + 543 }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">เดือน</label>
                                <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                                    @php
                                        $thaiMonths = [
                                            '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', 
                                            '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน',
                                            '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน',
                                            '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
                                        ];
                                    @endphp
                                    @foreach($thaiMonths as $num => $name)
                                        <option value="{{ $num }}" {{ $num == date('m') ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Month Range Fields -->
                        <div id="monthRangeFields" class="hidden grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ปีเริ่มต้น</label>
                                <select name="start_year" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i + 543 }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">เดือนเริ่มต้น</label>
                                <select name="start_month" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                                    @foreach($thaiMonths as $num => $name)
                                        <option value="{{ $num }}" {{ $num == '01' ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ปีสิ้นสุด</label>
                                <select name="end_year" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i + 543 }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">เดือนสิ้นสุด</label>
                                <select name="end_month" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                                    @foreach($thaiMonths as $num => $name)
                                        <option value="{{ $num }}" {{ $num == date('m') ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Yearly Field -->
                        <div id="yearlyField" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">ปี</label>
                            <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                                @for($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i + 543 }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4 pt-4">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            สร้างรายงาน
                        </button>
                        <button type="button" id="resetFilters" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200 flex items-center">
                            <i class="fas fa-redo mr-2"></i>
                            รีเซ็ต
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="hidden text-center py-8">
                <div class="inline-flex items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-white">กำลังโหลดข้อมูล...</span>
                </div>
            </div>

            <!-- Results Section -->
            <div id="resultsSection" class="hidden fade-in">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-2xl p-6 card-shadow text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">รวมค่าใช้จ่าย</h3>
                        <p id="totalAmount" class="text-2xl font-bold text-green-600">0.00 บาท</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 card-shadow text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                            <i class="fas fa-calculator text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">ค่าใช้จ่ายเฉลี่ย</h3>
                        <p id="averageAmount" class="text-2xl font-bold text-blue-600">0.00 บาท</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 card-shadow text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-3">
                            <i class="fas fa-receipt text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">จำนวนรายการ</h3>
                        <p id="expenseCount" class="text-2xl font-bold text-purple-600">0 รายการ</p>
                    </div>
                </div>

                <!-- Charts and Data -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Chart -->
                    <div class="bg-white rounded-2xl p-6 card-shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
                            สัดส่วนค่าใช้จ่ายตามหมวดหมู่
                        </h3>
                        <div class="h-64">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>

                    <!-- Expense List -->
                    <div class="bg-white rounded-2xl p-6 card-shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-list text-green-500 mr-2"></i>
                            รายการค่าใช้จ่าย
                        </h3>
                        <div id="expenseList" class="space-y-3 max-h-64 overflow-y-auto">
                            <!-- Expenses will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Detailed Table -->
                <div class="bg-white rounded-2xl p-6 card-shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-table text-purple-500 mr-2"></i>
                        ตารางสรุปค่าใช้จ่าย
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">วันที่</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">หมวดหมู่</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">รายการ</th>
                                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">จำนวนเงิน</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody id="expenseTableBody">
                                <!-- Table rows will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- No Data Message -->
            <div id="noDataMessage" class="hidden text-center py-12">
                <div class="bg-white rounded-2xl p-8 card-shadow">
                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">ไม่มีข้อมูล</h3>
                    <p class="text-gray-600 mb-4">ไม่พบข้อมูลค่าใช้จ่ายตามเงื่อนไขที่คุณเลือก</p>
                    <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        เพิ่มค่าใช้จ่ายใหม่
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart instance
        let categoryChart = null;

        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - initializing event listeners');
            initializeEventListeners();
            loadTodayReport(); // Load today's report by default
        });

        function initializeEventListeners() {
            console.log('Initializing event listeners');
            
            // Report type change
            document.querySelectorAll('input[name="report_type"]').forEach(radio => {
                radio.addEventListener('change', handleReportTypeChange);
            });

            // Form submission
            document.getElementById('reportFilterForm').addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form submitted');
                loadReportData();
            });

            // Reset filters
            document.getElementById('resetFilters').addEventListener('click', function() {
                console.log('Resetting filters');
                document.getElementById('reportFilterForm').reset();
                showFilterFields('today');
                loadTodayReport();
            });
        }

        function handleReportTypeChange(e) {
            const reportType = e.target.value;
            console.log('Report type changed to:', reportType);
            showFilterFields(reportType);
        }

        function showFilterFields(reportType) {
            console.log('Showing filter fields for:', reportType);
            
            // Hide all filter fields first
            document.querySelectorAll('#filterFields > div').forEach(field => {
                field.classList.add('hidden');
            });

            // Show relevant fields based on report type
            switch (reportType) {
                case 'specific_date':
                    document.getElementById('specificDateField').classList.remove('hidden');
                    break;
                case 'date_range':
                    document.getElementById('dateRangeFields').classList.remove('hidden');
                    break;
                case 'monthly':
                    document.getElementById('monthlyField').classList.remove('hidden');
                    break;
                case 'month_range':
                    document.getElementById('monthRangeFields').classList.remove('hidden');
                    break;
                case 'yearly':
                    document.getElementById('yearlyField').classList.remove('hidden');
                    break;
            }
        }

        function loadTodayReport() {
            console.log('Loading today report');
            const formData = new FormData();
            formData.append('report_type', 'today');
            loadReportData(formData);
        }

        function loadReportData(formData = null) {
            const loadingIndicator = document.getElementById('loadingIndicator');
            const resultsSection = document.getElementById('resultsSection');
            const noDataMessage = document.getElementById('noDataMessage');

            console.log('Loading report data...');

            // Show loading
            loadingIndicator.classList.remove('hidden');
            resultsSection.classList.add('hidden');
            noDataMessage.classList.add('hidden');

            // Use provided form data or get from form
            if (!formData) {
                formData = new FormData(document.getElementById('reportFilterForm'));
            }

            // Convert FormData to URL parameters
            const params = new URLSearchParams();
            for (let [key, value] of formData) {
                params.append(key, value);
            }

            console.log('Fetching data with params:', params.toString());

            fetch(`/reports/data?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                loadingIndicator.classList.add('hidden');

                if (data.success && data.expenses && data.expenses.length > 0) {
                    displayReportData(data);
                    resultsSection.classList.remove('hidden');
                } else {
                    console.log('No data found');
                    noDataMessage.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching report data:', error);
                loadingIndicator.classList.add('hidden');
                noDataMessage.classList.remove('hidden');
                
                // Show error message
                alert('เกิดข้อผิดพลาดในการโหลดข้อมูล: ' + error.message);
            });
        }

        function displayReportData(data) {
            console.log('Displaying report data:', data);
            
            // Update summary cards
            document.getElementById('totalAmount').textContent = data.summary.total_amount_formatted + ' บาท';
            document.getElementById('averageAmount').textContent = data.summary.average_amount_formatted + ' บาท';
            document.getElementById('expenseCount').textContent = data.summary.expense_count + ' รายการ';

            // Update chart
            updateChart(data.charts.category_breakdown);

            // Update expense list
            updateExpenseList(data.expenses);

            // Update table
            updateExpenseTable(data.expenses);
        }

        function updateChart(chartData) {
            console.log('Updating chart with data:', chartData);
            const ctx = document.getElementById('categoryChart').getContext('2d');
            
            // Destroy existing chart
            if (categoryChart) {
                categoryChart.destroy();
            }

            // Create new chart
            categoryChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.data,
                        backgroundColor: chartData.backgroundColors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    family: "'Noto Sans Thai', sans-serif"
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value.toLocaleString()} บาท (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

    //  ---------------------------

function updateExpenseList(expenses) {
    console.log('Updating expense list with:', expenses.length, 'items');
    const expenseList = document.getElementById('expenseList');
    expenseList.innerHTML = '';

    if (expenses.length === 0) {
        const emptyMessage = document.createElement('div');
        emptyMessage.className = 'text-center text-gray-500 py-4';
        emptyMessage.textContent = 'ไม่มีรายการค่าใช้จ่าย';
        expenseList.appendChild(emptyMessage);
        return;
    }

    // Show ALL items with better mobile layout
    expenses.forEach(expense => {
        const expenseItem = document.createElement('div');
        expenseItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2';
        expenseItem.innerHTML = `
            <div class="flex-1 min-w-0">
                <div class="font-medium text-gray-800 text-sm truncate">${expense.type}</div>
                <div class="text-xs text-gray-600 flex flex-wrap gap-1 mt-1">
                    <span class="bg-blue-100 text-blue-800 px-1 rounded">${expense.category}</span>
                    <span>${expense.date}</span>
                </div>
                ${expense.description ? `<div class="text-xs text-gray-500 mt-1 truncate">${expense.description}</div>` : ''}
            </div>
            <div class="text-right ml-2 flex-shrink-0">
                <div class="font-bold text-green-600 text-sm whitespace-nowrap">${expense.amount.toLocaleString()} บาท</div>
            </div>
        `;
        expenseList.appendChild(expenseItem);
    });
}

function updateExpenseTable(expenses) {
    console.log('Updating expense table with:', expenses.length, 'items');
    const tableBody = document.getElementById('expenseTableBody');
    tableBody.innerHTML = '';

    if (expenses.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                ไม่มีรายการค่าใช้จ่าย
            </td>
        `;
        tableBody.appendChild(row);
        return;
    }

    expenses.forEach(expense => {
        const row = document.createElement('tr');
        row.className = 'border-t border-gray-200 hover:bg-gray-50';
        row.innerHTML = `
            <td class="px-2 py-3 text-sm text-gray-900 whitespace-nowrap">
                <div class="font-medium">${expense.date}</div>
            </td>
            <td class="px-2 py-3 text-sm text-gray-900">
                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">${expense.category}</span>
            </td>
            <td class="px-2 py-3 text-sm text-gray-900">
                <div class="font-medium">${expense.type}</div>
            </td>
            <td class="px-2 py-3 text-sm text-right text-green-600 font-medium whitespace-nowrap">
                <strong>${expense.amount.toLocaleString()} บาท</strong>
            </td>
            <td class="px-2 py-3 text-sm text-gray-900 hidden lg:table-cell">
                ${expense.description || '<span class="text-gray-400">-</span>'}
            </td>
        `;
        tableBody.appendChild(row);
    });
}


// -----------------------------
    </script>
</body>
</html>