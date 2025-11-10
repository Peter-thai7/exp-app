 <!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกค่าใช้จ่ายใหม่ - ระบบบันทึกค่าใช้จ่าย</title>
    
    <!-- Tailwind CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <!-- Google Fonts - Thai font support -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- jQuery (required for thaidatepicker) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Thai Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/thaidatepicker@1.0.2/dist/thaidatepicker.min.css">
    <script src="https://cdn.jsdelivr.net/npm/thaidatepicker@1.0.2/dist/thaidatepicker.min.js"></script>
    
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
        /* Thai Date Picker Custom Styles */
        .thai-datepicker {
            font-family: 'Noto Sans Thai', sans-serif;
        }
        .thai-datepicker .calendar {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="gradient-bg">
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl p-6 card-shadow mb-6">
                <div class="text-center">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">บันทึกค่าใช้จ่ายใหม่</h1>
                    <p class="text-gray-600 mt-2">กรอกข้อมูลค่าใช้จ่ายของคุณ</p>
                </div>
            </div>

            <!-- Expense Form Card -->
            <div class="bg-white rounded-2xl p-6 card-shadow">
                <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Date Input with Thai Date Picker -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">วันที่ (รูปแบบไทย)</label>
                        <div class="relative">
                            <input type="text" name="thai_date" id="thai-date-input" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 transition duration-200"
                                   placeholder="คลิกเพื่อเลือกวันที่ (วว/ดด/ปปปป)"
                                   readonly
                                   value="{{ old('thai_date') }}">
                            <input type="hidden" name="date" id="english-date-input" value="{{ old('date', now()->format('Y-m-d')) }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            <span class="text-green-600">รูปแบบ: วัน/เดือน/ปี (พ.ศ.)</span>
                        </p>
                    </div>

                    <!-- Category Dropdown -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">หมวดหมู่</label>
                        <select name="category_id" id="category-select" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 transition duration-200" required>
                            <option value="">-- เลือกหมวดหมู่ --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_th }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Dropdown (will be populated by JavaScript) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">รายการ</label>
                        <select name="type_id" id="type-select" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 transition duration-200" required>
                            <option value="">-- เลือกหมวดหมู่ก่อน --</option>
                        </select>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">จำนวนเงิน (บาท)</label>
                        <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 transition duration-200" 
                               placeholder="0.00" required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">รายละเอียด (Optional)</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 transition duration-200" 
                                  placeholder="เพิ่มรายละเอียดเกี่ยวกับรายจ่ายนี้">{{ old('description') }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-4 pt-4">
                        <a href="{{ route('expenses.index') }}" 
                           class="flex-1 bg-gray-500 text-white py-3 px-4 rounded-lg font-medium text-center hover:bg-gray-600 transition duration-200">
                            กลับ
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-green-600 to-teal-600 text-white py-3 px-4 rounded-lg font-medium hover:from-green-700 hover:to-teal-700 transition duration-200">
                            บันทึกค่าใช้จ่าย
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Initialize Thai Date Picker
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Thai date picker
            $('#thai-date-input').thaidatepicker({
                format: 'dd/mm/yyyy',
                yearOffset: 543, // Convert to Buddhist year
                language: 'th',
                autoclose: true,
                todayHighlight: true,
                todayBtn: true,
                clearBtn: true
            });

            // Update hidden English date field when Thai date is selected
            $('#thai-date-input').on('change', function() {
                if (this.value) {
                    // Convert Thai date to English date
                    const thaiDateParts = this.value.split('/');
                    if (thaiDateParts.length === 3) {
                        const day = thaiDateParts[0];
                        const month = thaiDateParts[1];
                        const thaiYear = thaiDateParts[2];
                        const englishYear = parseInt(thaiYear) - 543;
                        
                        // Format as YYYY-MM-DD for database
                        const englishDate = `${englishYear}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
                        $('#english-date-input').val(englishDate);
                    }
                }
            });

            // Set initial value if there's old input
            @if(old('thai_date'))
                $('#thai-date-input').val("{{ old('thai_date') }}");
            @else
                // Set default to today in Thai format
                const today = new Date();
                const thaiYear = today.getFullYear() + 543;
                const thaiDate = `${today.getDate().toString().padStart(2, '0')}/${(today.getMonth() + 1).toString().padStart(2, '0')}/${thaiYear}`;
                $('#thai-date-input').val(thaiDate);
                // Trigger change to update the hidden field
                $('#thai-date-input').trigger('change');
            @endif
        });

        // JavaScript for dynamic type dropdown
        document.getElementById('category-select').addEventListener('change', function() {
            const categoryId = this.value;
            const typeSelect = document.getElementById('type-select');

            // Clear existing options
            typeSelect.innerHTML = '<option value="">-- เลือกรายการ --</option>';

            if (categoryId) {
                // Fetch types for selected category
                fetch(`/api/categories/${categoryId}/types`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(types => {
                        types.forEach(type => {
                            const option = document.createElement('option');
                            option.value = type.id;
                            option.textContent = type.name_th;
                            typeSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching types:', error);
                        typeSelect.innerHTML = '<option value="">Error loading types</option>';
                    });
            }
        });

        // Initialize category select if there's a old value
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category-select');
            const oldCategoryId = "{{ old('category_id') }}";
            
            if (oldCategoryId) {
                categorySelect.value = oldCategoryId;
                // Trigger change event to load types
                const event = new Event('change');
                categorySelect.dispatchEvent(event);
                
                // Also set the type if there's an old value
                const oldTypeId = "{{ old('type_id') }}";
                if (oldTypeId) {
                    // We need to wait for the types to load, so we'll use a small delay
                    setTimeout(() => {
                        const typeSelect = document.getElementById('type-select');
                        typeSelect.value = oldTypeId;
                    }, 500);
                }
            }
        });
    </script>
</body>
</html>