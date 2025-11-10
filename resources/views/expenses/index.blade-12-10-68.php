<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการค่าใช้จ่าย - ระบบบันทึกค่าใช้จ่าย</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Noto Sans Thai', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800 text-center">รายการค่าใช้จ่ายทั้งหมด</h1>
                <div class="text-center mt-4">
                    <a href="{{ route('expenses.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        + เพิ่มค่าใช้จ่ายใหม่
                    </a>
                </div>
            </div>

<!-- Add this navigation section at the top of your expenses index page -->
<div class="flex flex-wrap gap-4 mb-6">
    <a href="{{ route('reports.index') }}" class="bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600 transition duration-200 flex items-center">
        <i class="fas fa-chart-bar mr-2"></i>
        รายงาน
    </a>
    <a href="{{ route('expenses.create') }}" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition duration-200 flex items-center">
        <i class="fas fa-plus mr-2"></i>
        เพิ่มค่าใช้จ่ายใหม่
    </a>
    @if(auth()->check() && auth()->user()->isAdmin())
    <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-200 flex items-center">
        <i class="fas fa-cog mr-2"></i>
        จัดการหมวดหมู่
    </a>
    @endif
</div>




            <!-- Expenses List -->
            <div class="bg-white rounded-xl shadow-md p-6">
                @if($expenses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
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
                                    <td class="px-4 py-2 text-right">{{ $expense->date }}</td>
                                    <td class="px-4 py-2">{{ $expense->type->category->name_th }}</td>
                                    <td class="px-4 py-2">{{ $expense->type->name_th }}</td>
                                    <td class="px-4 py-2 text-right text-green-600">{{ number_format($expense->amount, 2) }} บาท</td>
                                    <td class="px-4 py-2">{{ $expense->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>ยังไม่มีรายการค่าใช้จ่าย</p>
                        <a href="{{ route('expenses.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                            เพิ่มรายการแรกของคุณ
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
