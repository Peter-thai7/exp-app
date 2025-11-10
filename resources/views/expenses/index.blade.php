<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการค่าใช้จ่าย - ระบบบันทึกค่าใช้จ่าย</title>
    
    <!-- Tailwind CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <!-- Google Fonts - Thai font support -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
    </style>
</head>

<body class="gradient-bg">
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                    <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">รายการค่าใช้จ่ายทั้งหมด</h1>
                <p class="text-white text-opacity-90">จัดการรายการค่าใช้จ่ายของคุณ</p>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <a href="{{ route('reports.index') }}" class="bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600 transition duration-200 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    รายงาน
                </a>
                <a href="{{ route('expenses.create') }}" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    เพิ่มค่าใช้จ่ายใหม่
                </a>
                <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-200 flex items-center">
                    <i class="fas fa-cog mr-2"></i>
                    จัดการหมวดหมู่
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Expenses List Card -->
            <div class="bg-white rounded-2xl p-6 card-shadow">
                @if($expenses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">วันที่</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">หมวดหมู่</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">รายการ</th>
                                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">จำนวนเงิน</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">รายละเอียด</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($expense->date)->format('d/m/') . (\Carbon\Carbon::parse($expense->date)->format('Y') + 543) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $expense->type->category->name_th }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $expense->type->name_th }}</td>
                                    <td class="px-4 py-3 text-sm text-right text-green-600 font-medium">
                                        {{ number_format($expense->amount, 2) }} บาท
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $expense->description ?: '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <div class="flex justify-center space-x-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('expenses.edit', $expense->id) }}" 
                                               class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition duration-200 flex items-center text-sm">
                                                <i class="fas fa-edit mr-1"></i>
                                                แก้ไข
                                            </a>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" 
                                                  class="inline" 
                                                  onsubmit="return confirm('คุณแน่ใจว่าต้องการลบรายการนี้?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-200 flex items-center text-sm">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    ลบ
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination (if needed) -->
                    @if($expenses->hasPages())
                    <div class="mt-6">
                        {{ $expenses->links() }}
                    </div>
                    @endif

                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 text-lg mb-2">ยังไม่มีรายการค่าใช้จ่าย</p>
                        <p class="text-gray-500 mb-6">เริ่มต้นโดยการเพิ่มรายการค่าใช้จ่ายแรกของคุณ</p>
                        <a href="{{ route('expenses.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            เพิ่มรายการแรก
                        </a>
                    </div>
                @endif
            </div>

            <!-- Summary Card -->
            @if($expenses->count() > 0)
            <div class="bg-white rounded-2xl p-6 card-shadow mt-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div>
                        <div class="text-2xl font-bold text-green-600">
                            {{ number_format($expenses->sum('amount'), 2) }} บาท
                        </div>
                        <div class="text-gray-600">รวมค่าใช้จ่ายทั้งหมด</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-600">
                            {{ $expenses->count() }} รายการ
                        </div>
                        <div class="text-gray-600">จำนวนรายการทั้งหมด</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-600">
                            {{ number_format($expenses->avg('amount'), 2) }} บาท
                        </div>
                        <div class="text-gray-600">ค่าใช้จ่ายเฉลี่ยต่อรายการ</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-hide success message after 5 seconds
        @if(session('success'))
        setTimeout(function() {
            const alert = document.querySelector('.bg-green-100');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000);
        @endif
    </script>
</body>
</html>