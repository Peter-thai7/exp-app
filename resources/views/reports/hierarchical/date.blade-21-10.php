<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานตามวันที่ - ระบบบันทึกค่าใช้จ่าย</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Noto Sans Thai', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .card-shadow { box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                    <i class="fas fa-calendar-day text-2xl text-blue-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">รายงานตามวันที่</h1>
                <p class="text-white text-opacity-90">ระหว่างวันที่ {{ $reportData['period']['start'] }} ถึง {{ $reportData['period']['end'] }}</p>
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
                        <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">จำนวนวัน</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ count($reportData['summaries']) }} วัน</p>
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

            <!-- Daily Reports -->
            <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-list text-blue-500 mr-2"></i>
                        สรุปรายวัน
                    </h2>
                </div>
                
                <div class="divide-y">
                    @forelse($reportData['summaries'] as $day)
                    <div class="p-6">
                        <div class="flex items-center justify-between cursor-pointer" onclick="toggleDetails('day-{{ $loop->index }}')">
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-100 text-blue-600 p-3 rounded-lg">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $day->thai_date }}</h3>
                                    <p class="text-sm text-gray-600">{{ $day->expense_count }} รายการ</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-xl font-bold text-gray-800">฿{{ $day->total_amount_formatted }}</span>
                                <i class="fas fa-chevron-down text-gray-400 transition-transform" id="icon-day-{{ $loop->index }}"></i>
                            </div>
                        </div>
                        
                        <!-- Details -->
                        <div id="day-{{ $loop->index }}" class="mt-4 hidden">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-800 mb-3">รายการค่าใช้จ่าย</h4>
                                <div class="space-y-3">
                                    @forelse($day->details as $expense)
                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $expense->type->name_th }}</p>
                                                <p class="text-sm text-gray-600">{{ $expense->type->category->name_th }}</p>
                                                @if($expense->description)
                                                <p class="text-sm text-gray-500 mt-1">{{ $expense->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="font-semibold text-gray-800">฿{{ number_format($expense->amount, 2) }}</span>
                                    </div>
                                    @empty
                                    <div class="text-center py-4 text-gray-500">
                                        <i class="fas fa-inbox text-2xl mb-2"></i>
                                        <p>ไม่มีรายการค่าใช้จ่ายในวันนี้</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 text-lg">ไม่พบข้อมูลค่าใช้จ่ายในช่วงวันที่เลือก</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDetails(id) {
            const element = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                element.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }
    </script>
</body>
</html>