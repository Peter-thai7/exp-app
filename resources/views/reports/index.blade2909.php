 <!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สรุปรายงานค่าใช้จ่าย - ระบบบันทึกค่าใช้จ่าย</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Noto Sans Thai', sans-serif; 
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">สรุปรายงานค่าใช้จ่าย</h1>
                    <div class="space-x-2">
                        <a href="{{ route('expenses.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                            ← กลับสู่รายการ
                        </a>
                        <a href="{{ route('expenses.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            + เพิ่มค่าใช้จ่ายใหม่
                        </a>
                    </div>
                </div>
            </div>

            <!-- Report Form -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <form action="{{ route('reports.generate') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Report Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ประเภทรายงาน</label>
                        <select name="report_type" id="report-type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- เลือกประเภทรายงาน --</option>
                            <option value="today">รายงานวันนี้</option>
                            <option value="date">รายงานตามวันที่เฉพาะ</option>
                            <option value="date_range">รายงานตามช่วงวันที่</option>
                            <option value="month">รายงานตามเดือน</option>
                            <option value="month_range">รายงานตามช่วงเดือน</option>
                            <option value="year">รายงานตามปี</option>
                        </select>
                    </div>

                    <!-- Dynamic Fields based on Report Type -->
                    <div id="report-fields">
                        <!-- Fields will be populated by JavaScript -->
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                            สร้างรายงาน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dynamic Form Fields -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reportType = document.getElementById('report-type');
            const reportFields = document.getElementById('report-fields');
            
            // Thai month names
            const thaiMonths = [
                'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 
                'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
                'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            ];

            // Current date values
            const currentYear = new Date().getFullYear();
            const currentMonth = new Date().getMonth() + 1;
            const thaiYear = currentYear + 543;

            // Field templates
            const fieldTemplates = {
                today: `
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-blue-700">แสดงรายงานค่าใช้จ่ายสำหรับวันนี้</p>
                    </div>
                `,
                
                date: `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เลือกวันที่</label>
                        <input type="date" name="specific_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ date('Y-m-d') }}">
                    </div>
                `,
                
                date_range: `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">วันที่เริ่มต้น</label>
                            <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ date('Y-m-01') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">วันที่สิ้นสุด</label>
                            <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                `,
                
                month: `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">เลือกเดือน</label>
                            <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                ${thaiMonths.map((month, index) => 
                                    `<option value="${index + 1}" ${currentMonth === index + 1 ? 'selected' : ''}>${month}</option>`
                                ).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">เลือกปี</label>
                            <select name="month_year" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                ${Array.from({length: 5}, (_, i) => {
                                    const year = currentYear - i;
                                    return `<option value="${year}" ${i === 0 ? 'selected' : ''}>${year + 543}</option>`;
                                }).join('')}
                            </select>
                        </div>
                    </div>
                `,
                
                month_range: `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">เดือนเริ่มต้น</label>
                                <select name="start_month" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    ${thaiMonths.map((month, index) => 
                                        `<option value="${index + 1}" ${currentMonth === index + 1 ? 'selected' : ''}>${month}</option>`
                                    ).join('')}
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">เดือนสิ้นสุด</label>
                                <select name="end_month" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    ${thaiMonths.map((month, index) => 
                                        `<option value="${index + 1}" ${currentMonth === index + 1 ? 'selected' : ''}>${month}</option>`
                                    ).join('')}
                                </join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">เลือกปี</label>
                            <select name="month_range_year" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                ${Array.from({length: 5}, (_, i) => {
                                    const year = currentYear - i;
                                    return `<option value="${year}" ${i === 0 ? 'selected' : ''}>${year + 543}</option>`;
                                }).join('')}
                            </select>
                        </div>
                    </div>
                `,
                
                year: `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เลือกปี</label>
                        <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            ${Array.from({length: 5}, (_, i) => {
                                const year = currentYear - i;
                                return `<option value="${year}" ${i === 0 ? 'selected' : ''}>${year + 543}</option>`;
                            }).join('')}
                        </select>
                    </div>
                `
            };

            // Update fields when report type changes
            reportType.addEventListener('change', function() {
                const selectedType = this.value;
                reportFields.innerHTML = fieldTemplates[selectedType] || '';
            });

            // Trigger change on page load if there's a value
            if (reportType.value) {
                reportType.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>