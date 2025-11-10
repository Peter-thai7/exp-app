 <!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลลัพธ์รายงาน - ระบบบันทึกค่าใช้จ่าย</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Noto Sans Thai', sans-serif; 
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
                        <p class="text-gray-600">สร้างเมื่อ: {{ now()->format('d/m/') }}{{ now()->year + 543 }}</p>
                    </div>
                    <div class="space-x-2 no-print">
                        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            🖨️ พิมพ์รายงาน
                        </button>
                        <a href="{{ route('reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                            ← สร้างรายงานใหม่
                        </a>
                        <a href="{{ route('expenses.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            📋 ดูรายการทั้งหมด
                        </a>
                    </div>
                </div>
            </div>

            <!-- Report Summary -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-600">จำนวนรายการ</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $expenses->count() }}</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-600">ยอดรวมทั้งหมด</p>
                        <p class="text-2xl font-bold text-green-700">{{ number_format($totalAmount, 2) }} บาท</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-purple-600">ค่าใช้จ่ายเฉลี่ยต่อรายการ</p>
                        <p class="text-2xl font-bold text-purple-700">
                            {{ $expenses->count() > 0 ? number_format($totalAmount / $expenses->count(), 2) : 0 }} บาท
                        </p>
                    </div>
                </div>
            </div>

            <!-- Expenses List -->
            <div class="bg-white rounded-xl shadow-md p-6">
                @if($expenses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full" id="report-table">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-right">วันที่</th>
                                    <th class="px-4 py-2">หมวดหมู่</th>
                                    <th class="px-4 py-2">รายการ</th>
                                    <th class="px-4 py-2 text-right">จำนวนเงิน</th>
                                    <th class="px-4 py-2">รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                <tr class="border-t">
                                    <td class="px-4 py-2 text-right date-cell" data-date="{{ $expense->date }}">
                                        {{ $expense->date }}
                                    </td>
                                    <td class="px-4 py-2">{{ $expense->type->category->name_th }}</td>
                                    <td class="px-4 py-2">{{ $expense->type->name_th }}</td>
                                    <td class="px-4 py-2 text-right text-green-600">{{ number_format($expense->amount, 2) }} บาท</td>
                                    <td class="px-4 py-2">{{ $expense->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 font-bold">
                                    <td colspan="3" class="px-4 py-2 text-right">รวมทั้งหมด</td>
                                    <td class="px-4 py-2 text-right text-green-600">{{ number_format($totalAmount, 2) }} บาท</td>
                                    <td class="px-4 py-2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p class="text-lg">ไม่พบรายการค่าใช้จ่ายในช่วงเวลาที่เลือก</p>
                        <a href="{{ route('expenses.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                            เพิ่มรายการค่าใช้จ่ายใหม่
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript to convert dates to Thai format -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Thai month names
            const thaiMonths = [
                'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 
                'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
                'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            ];

            // Convert all date cells to Thai format
            const dateCells = document.querySelectorAll('.date-cell');
            dateCells.forEach(cell => {
                const rawDate = cell.getAttribute('data-date');
                if (rawDate) {
                    const date = new Date(rawDate);
                    const day = date.getDate();
                    const month = thaiMonths[date.getMonth()];
                    const year = date.getFullYear() + 543;
                    
                    cell.textContent = `${day} ${month} ${year}`;
                }
            });
        });
    </script>
</body>
</html>