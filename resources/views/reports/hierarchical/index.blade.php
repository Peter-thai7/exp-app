<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานแบบลำดับชั้น - ระบบบันทึกค่าใช้จ่าย</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card-shadow {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .report-type-option {
            transition: all 0.3s ease;
        }
        .report-type-option:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                    <i class="fas fa-sitemap text-2xl text-purple-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">รายงานแบบลำดับชั้น</h1>
                <p class="text-white text-opacity-90">รายงานค่าใช้จ่ายแบบละเอียดพร้อมการขยายดูข้อมูล</p>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <a href="{{ route('expenses.index') }}" class="bg-white bg-opacity-20 text-white px-6 py-3 rounded-lg hover:bg-opacity-30 transition duration-200 flex items-center">
                    <i class="fas fa-list mr-2"></i>
                    รายการค่าใช้จ่าย
                </a>
                <a href="{{ route('reports.index') }}" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition duration-200 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    รายงานหลัก
                </a>
                <a href="{{ route('expenses.create') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    เพิ่มค่าใช้จ่าย
                </a>
            </div>

            <!-- Report Selection Card -->
            <div class="bg-white rounded-2xl p-6 card-shadow">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-filter text-blue-500 mr-2"></i>
                    เลือกประเภทรายงานแบบลำดับชั้น
                </h2>
                
                <!-- SIMPLE HTML FORM - NO JAVASCRIPT -->
                <form action="{{ route('reports.hierarchical.generate') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Report Type Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <label class="report-type-option flex items-center p-4 border-2 border-blue-300 rounded-lg cursor-pointer">
                            <input type="radio" name="report_type" value="date" class="mr-3 text-blue-600" checked>
                            <div>
                                <div class="font-medium text-gray-800">รายงานตามวันที่</div>
                                <div class="text-sm text-gray-600">สรุปรายวัน + รายละเอียด</div>
                            </div>
                        </label>

                        <label class="report-type-option flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-300 transition">
                            <input type="radio" name="report_type" value="month" class="mr-3 text-orange-600">
                            <div>
                                <div class="font-medium text-gray-800">รายงานตามเดือน</div>
                                <div class="text-sm text-gray-600">สรุปเดือน + รายวัน</div>
                            </div>
                        </label>

                        <label class="report-type-option flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-300 transition">
                            <input type="radio" name="report_type" value="year" class="mr-3 text-red-600">
                            <div>
                                <div class="font-medium text-gray-800">รายงานตามปี</div>
                                <div class="text-sm text-gray-600">สรุปปี + รายเดือน</div>
                            </div>
                        </label>

                        <label class="report-type-option flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-300 transition">
                            <input type="radio" name="report_type" value="year_range" class="mr-3 text-green-600">
                            <div>
                                <div class="font-medium text-gray-800">รายงานตามช่วงปี</div>
                                <div class="text-sm text-gray-600">สรุปหลายปี + รายเดือน</div>
                            </div>
                        </label>
                    </div>

                    <!-- Date Input Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                วันที่เริ่มต้น
                            </label>
                            <input type="text" name="start_date" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="01/10/2568" 
                                   required>
                            <p class="text-xs text-gray-500 mt-1">รูปแบบ: วัน/เดือน/ปี (เช่น 01/10/2568)</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                วันที่สิ้นสุด
                            </label>
                            <input type="text" name="end_date" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="20/10/2568" 
                                   required>
                            <p class="text-xs text-gray-500 mt-1">รูปแบบ: วัน/เดือน/ปี (เช่น 20/10/2568)</p>
                        </div>
                    </div>

                    <!-- Example Dates -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-800 mb-2 flex items-center">
                            <i class="fas fa-lightbulb mr-2"></i>
                            ตัวอย่างรูปแบบวันที่
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 text-sm">
                            <div>
                                <span class="font-medium text-blue-700">รายงานตามวันที่:</span>
                                <div class="text-blue-600">01/10/2568 ถึง 20/10/2568</div>
                            </div>
                            <div>
                                <span class="font-medium text-orange-700">รายงานตามเดือน:</span>
                                <div class="text-orange-600">09/2568 ถึง 10/2568</div>
                            </div>
                            <div>
                                <span class="font-medium text-red-700">รายงานตามปี:</span>
                                <div class="text-red-600">2568 ถึง 2568</div>
                            </div>
                            <div>
                                <span class="font-medium text-green-700">รายงานตามช่วงปี:</span>
                                <div class="text-green-600">2566 ถึง 2568</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4 pt-4">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 px-4 rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-sitemap mr-2"></i>
                            สร้างรายงานแบบลำดับชั้น
                        </button>
                        <a href="{{ route('reports.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            กลับไปรายงานหลัก
                        </a>
                    </div>
                </form>
            </div>

            <!-- Features Explanation -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl p-6 card-shadow text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                        <i class="fas fa-expand-arrows-alt text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">ขยายดูข้อมูล</h3>
                    <p class="text-gray-600 text-sm">คลิกที่แต่ละรายการเพื่อขยายดูข้อมูลแบบละเอียด</p>
                </div>

                <div class="bg-white rounded-2xl p-6 card-shadow text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                        <i class="fas fa-layer-group text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">หลายระดับ</h3>
                    <p class="text-gray-600 text-sm">ดูข้อมูลในระดับ ปี → เดือน → วัน → รายการ</p>
                </div>

                <div class="bg-white rounded-2xl p-6 card-shadow text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-3">
                        <i class="fas fa-print text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">รองรับการพิมพ์</h3>
                    <p class="text-gray-600 text-sm">พิมพ์รายงานได้อย่างสวยงามและเป็นระเบียบ</p>
                </div>

                <div class="bg-white rounded-2xl p-6 card-shadow text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-orange-100 rounded-full mb-3">
                        <i class="fas fa-calendar-alt text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">ช่วงเวลายืดหยุ่น</h3>
                    <p class="text-gray-600 text-sm">เลือกรายงานตามวัน เดือน ปี หรือช่วงปี</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple script to update placeholder examples based on report type
        document.addEventListener('DOMContentLoaded', function() {
            const reportTypeRadios = document.querySelectorAll('input[name="report_type"]');
            const startDateInput = document.querySelector('input[name="start_date"]');
            const endDateInput = document.querySelector('input[name="end_date"]');
            
            reportTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    switch(this.value) {
                        case 'date':
                            startDateInput.value = '01/10/2568';
                            endDateInput.value = '20/10/2568';
                            break;
                        case 'month':
                            startDateInput.value = '09/2568';
                            endDateInput.value = '10/2568';
                            break;
                        case 'year':
                            startDateInput.value = '2568';
                            endDateInput.value = '2568';
                            break;
                        case 'year_range':
                            startDateInput.value = '2566';
                            endDateInput.value = '2568';
                            break;
                    }
                });
            });
        });
    </script>
</body>
</html>