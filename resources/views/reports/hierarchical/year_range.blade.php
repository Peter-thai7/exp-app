<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานตามช่วงปี - ระบบบันทึกค่าใช้จ่าย</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Noto Sans Thai', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .card-shadow { box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); }
        .rotate-180 { transform: rotate(180deg); transition: transform 0.3s ease; }
        .loading { opacity: 0.6; pointer-events: none; }
    </style>
</head>
<body>
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                    <i class="fas fa-calendar text-2xl text-green-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">รายงานตามช่วงปี</h1>
                <p class="text-white text-opacity-90">ระหว่างปี {{ $reportData['period']['start'] }} ถึง {{ $reportData['period']['end'] }}</p>
            </div>

            <!-- Navigation -->
            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <a href="{{ route('reports.hierarchical') }}" class="bg-white bg-opacity-20 text-white px-6 py-3 rounded-lg hover:bg-opacity-30 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>กลับ
                </a>
                <button onclick="window.print()" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition flex items-center">
                    <i class="fas fa-print mr-2"></i>พิมพ์รายงาน
                </button>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 card-shadow text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                        <i class="fas fa-calendar text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">จำนวนปี</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ count($reportData['summaries']) }} ปี</p>
                </div>
                <div class="bg-white rounded-2xl p-6 card-shadow text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                        <i class="fas fa-receipt text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">จำนวนรายการ</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $reportData['total_count'] }} รายการ</p>
                </div>
                <div class="bg-white rounded-2xl p-6 card-shadow text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-3">
                        <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">ยอดรวมทั้งหมด</h3>
                    <p class="text-2xl font-bold text-purple-600">฿{{ number_format($reportData['total_amount'], 2) }}</p>
                </div>
            </div>

            <!-- Yearly Reports -->
            <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-chart-line text-green-500 mr-2"></i>
                        สรุปรายปี
                    </h2>
                </div>
                
                <div class="divide-y">
                    @forelse($reportData['summaries'] as $year)
                    <div class="p-6">
                        <div class="flex items-center justify-between cursor-pointer" onclick="toggleYearDetails('{{ $year->year }}', {{ $loop->index }})">
                            <div class="flex items-center space-x-4">
                                <div class="bg-green-100 text-green-600 p-3 rounded-lg">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">ปี {{ $year->thai_year }}</h3>
                                    <p class="text-sm text-gray-600">{{ $year->expense_count }} รายการ</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-xl font-bold text-gray-800">฿{{ $year->total_amount_formatted }}</span>
                                <i class="fas fa-chevron-down text-gray-400 transition-transform" id="icon-year-{{ $loop->index }}"></i>
                            </div>
                        </div>
                        
                        <!-- Monthly Details Container -->
                        <div id="year-details-{{ $loop->index }}" class="mt-4 hidden">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                                    สรุปรายเดือนในปี {{ $year->thai_year }}
                                </h4>
                                <div id="monthly-content-{{ $loop->index }}" class="space-y-3">
                                    <!-- Content will be loaded via AJAX -->
                                    <div class="text-center py-8">
                                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                                            <i class="fas fa-spinner fa-spin text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-500">กำลังโหลดข้อมูลรายเดือน...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 text-lg">ไม่พบข้อมูลค่าใช้จ่ายในช่วงปีที่เลือก</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        let openYearIndex = null;

        function toggleYearDetails(year, index) {
            const detailsContainer = document.getElementById(`year-details-${index}`);
            const icon = document.getElementById(`icon-year-${index}`);
            const monthlyContent = document.getElementById(`monthly-content-${index}`);

            // Close previously opened year
            if (openYearIndex !== null && openYearIndex !== index) {
                const prevDetails = document.getElementById(`year-details-${openYearIndex}`);
                const prevIcon = document.getElementById(`icon-year-${openYearIndex}`);
                if (prevDetails) {
                    prevDetails.classList.add('hidden');
                    prevIcon.classList.remove('rotate-180');
                }
            }

            if (detailsContainer.classList.contains('hidden')) {
                // Show loading and fetch data
                detailsContainer.classList.remove('hidden');
                icon.classList.add('rotate-180');
                openYearIndex = index;

                // Load monthly summaries via AJAX if not already loaded
                if (!monthlyContent.classList.contains('loaded')) {
                    loadMonthlySummaries(year, index);
                }
            } else {
                detailsContainer.classList.add('hidden');
                icon.classList.remove('rotate-180');
                openYearIndex = null;
            }
        }

        function loadMonthlySummaries(year, index) {
            const monthlyContent = document.getElementById(`monthly-content-${index}`);
            
            fetch(`/reports/year-monthly-summaries/${year}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        monthlyContent.innerHTML = '';
                        monthlyContent.classList.add('loaded');

                        if (data.monthly_summaries && data.monthly_summaries.length > 0) {
                            data.monthly_summaries.forEach(month => {
                                const monthElement = document.createElement('div');
                                monthElement.className = 'flex items-center justify-between p-4 bg-white rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors';
                                monthElement.onclick = () => showMonthDetails(month.year, month.month, month.thai_month);
                                
                                monthElement.innerHTML = `
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                        <div>
                                            <p class="font-medium text-gray-800">${month.thai_month}</p>
                                            <p class="text-sm text-gray-600">${month.monthly_count} รายการ</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="font-semibold text-gray-800">฿${month.monthly_total_formatted}</span>
                                        <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                                    </div>
                                `;
                                monthlyContent.appendChild(monthElement);
                            });
                        } else {
                            monthlyContent.innerHTML = `
                                <div class="text-center py-4 text-gray-500">
                                    <i class="fas fa-inbox text-2xl mb-2"></i>
                                    <p>ไม่มีรายการค่าใช้จ่ายในปีนี้</p>
                                </div>
                            `;
                        }
                    } else {
                        throw new Error(data.error || 'Failed to load data');
                    }
                })
                .catch(error => {
                    console.error('Error loading monthly summaries:', error);
                    monthlyContent.innerHTML = `
                        <div class="text-center py-4 text-red-500">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>เกิดข้อผิดพลาดในการโหลดข้อมูล: ${error.message}</p>
                        </div>
                    `;
                });
        }

        function showMonthDetails(year, month, thaiMonth) {
            alert(`แสดงรายละเอียดของเดือน: ${thaiMonth}\n\nคุณสามารถพัฒนาต่อโดย:\n1. สร้าง modal เพื่อแสดงรายการ\n2. ใช้หน้าใหม่สำหรับแสดงรายละเอียด\n3. ขยายข้อมูลในหน้าเดียวกัน`);
        }
    </script>
</body>
</html>