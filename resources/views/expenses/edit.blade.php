<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขค่าใช้จ่าย - ระบบบันทึกค่าใช้จ่าย</title>
    
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
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }
        .thai-month-select {
            font-family: 'Noto Sans Thai', sans-serif;
        }
        .clickable-date {
            cursor: pointer;
            background-color: #f9fafb;
        }
        .clickable-date:hover {
            background-color: #f3f4f6;
            border-color: #667eea;
        }
    </style>
</head>

<body class="gradient-bg">
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl p-6 card-shadow mb-6">
                <div class="text-center">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-edit text-white text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">แก้ไขค่าใช้จ่าย</h1>
                    <p class="text-gray-600 mt-2">แก้ไขข้อมูลค่าใช้จ่ายของคุณ</p>
                </div>
            </div>

            <!-- Expense Form Card -->
            <div class="bg-white rounded-2xl p-6 card-shadow">
                <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Date Input with Manual Thai Date Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                            วันที่ (รูปแบบไทย)
                        </label>
                        
                        <!-- Manual Date Selection -->
                        <div class="grid grid-cols-3 gap-4 mb-3">
                            <!-- Day -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">วัน</label>
                                <select id="thai-day" class="w-full px-3 py-2 border border-gray-300 rounded-lg thai-month-select">
                                    @for($i = 1; $i <= 31; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <!-- Month -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">เดือน</label>
                                <select id="thai-month" class="w-full px-3 py-2 border border-gray-300 rounded-lg thai-month-select">
                                    @php
                                        $thaiMonths = [
                                            '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', 
                                            '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน',
                                            '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน',
                                            '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
                                        ];
                                    @endphp
                                    @foreach($thaiMonths as $num => $name)
                                        <option value="{{ $num }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Year -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">ปี (พ.ศ.)</label>
                                <select id="thai-year" class="w-full px-3 py-2 border border-gray-300 rounded-lg thai-month-select">
                                    @for($i = date('Y') + 543; $i >= (date('Y') + 543 - 10); $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        
                        <!-- Date Display Field -->
                        <div class="relative mb-3">
                            <input type="text" name="thai_date" id="thai-date-input" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 transition duration-200 clickable-date"
                                   placeholder="คลิกที่นี่เพื่อเลือกวันที่"
                                   readonly
                                   value="{{ $thaiDate ?? '' }}"
                                   autocomplete="off">
                            <input type="hidden" name="date" id="english-date-input" value="{{ $expense->date }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-500 mt-2">
                            <span class="text-green-600">รูปแบบ: วัน/เดือน/ปี (พ.ศ.) เช่น 22/09/2568</span>
                        </p>
                        <p id="date-conversion-display" class="text-sm text-blue-600 mt-1"></p>
                        
                        <!-- Quick Date Buttons -->
                        <div class="flex space-x-2 mt-3">
                            <button type="button" onclick="setThaiDate(0)" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded text-sm hover:bg-blue-200 transition duration-200">
                                <i class="fas fa-calendar-day mr-1"></i> วันนี้
                            </button>
                            <button type="button" onclick="setThaiDate(-1)" class="flex-1 bg-gray-100 text-gray-700 py-2 px-3 rounded text-sm hover:bg-gray-200 transition duration-200">
                                <i class="fas fa-chevron-left mr-1"></i> เมื่อวาน
                            </button>
                            <button type="button" onclick="showSimpleDatePicker()" class="flex-1 bg-green-100 text-green-700 py-2 px-3 rounded text-sm hover:bg-green-200 transition duration-200">
                                <i class="fas fa-calendar-alt mr-1"></i> เลือกวันที่
                            </button>
                        </div>
                    </div>

                    <!-- Category Dropdown -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-folder text-blue-500 mr-2"></i>
                            หมวดหมู่
                        </label>
                        <select name="category_id" id="category-select"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 transition duration-200" required>
                            <option value="">-- เลือกหมวดหมู่ --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ $expense->type->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_th }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Dropdown -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-list-alt text-blue-500 mr-2"></i>
                            รายการ
                        </label>
                        <select name="type_id" id="type-select"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 transition duration-200" required>
                            <option value="">-- เลือกหมวดหมู่ก่อน --</option>
                        </select>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-coins text-blue-500 mr-2"></i>
                            จำนวนเงิน (บาท)
                        </label>
                        <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount', $expense->amount) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 transition duration-200"
                               placeholder="0.00" required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left text-blue-500 mr-2"></i>
                            รายละเอียด (Optional)
                        </label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:ring-2 focus:ring-indigo-500 transition duration-200"
                                  placeholder="เพิ่มรายละเอียดเกี่ยวกับรายจ่ายนี้">{{ old('description', $expense->description) }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-4 pt-4">
                        <a href="{{ route('expenses.index') }}"
                           class="flex-1 bg-gray-500 text-white py-3 px-4 rounded-lg font-medium text-center hover:bg-gray-600 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            กลับ
                        </a>
                        <button type="submit"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            อัพเดตค่าใช้จ่าย
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Function to update the Thai date display
        function updateThaiDate() {
            const day = document.getElementById('thai-day').value;
            const month = document.getElementById('thai-month').value;
            const year = document.getElementById('thai-year').value;
            
            const thaiDate = day + '/' + month + '/' + year;
            document.getElementById('thai-date-input').value = thaiDate;
            updateDateConversion(thaiDate);
        }

        // Function to set date relative to today
        function setThaiDate(daysOffset) {
            const date = new Date();
            date.setDate(date.getDate() + daysOffset);
            
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const thaiYear = date.getFullYear() + 543;
            
            document.getElementById('thai-day').value = day;
            document.getElementById('thai-month').value = month;
            document.getElementById('thai-year').value = thaiYear;
            
            updateThaiDate();
        }

        // Simple and reliable date picker function
        function showSimpleDatePicker() {
            const tempInput = document.createElement('input');
            tempInput.type = 'date';
            tempInput.style.position = 'fixed';
            tempInput.style.left = '50%';
            tempInput.style.top = '50%';
            tempInput.style.transform = 'translate(-50%, -50%)';
            tempInput.style.zIndex = '10000';
            
            const currentThaiDate = document.getElementById('thai-date-input').value;
            if (currentThaiDate) {
                const parts = currentThaiDate.split('/');
                if (parts.length === 3) {
                    const englishYear = parseInt(parts[2]) - 543;
                    const englishDate = englishYear + '-' + parts[1] + '-' + parts[0];
                    tempInput.value = englishDate;
                }
            }
            
            document.body.appendChild(tempInput);
            tempInput.focus();
            
            if (tempInput.showPicker) {
                tempInput.showPicker();
            }
            
            tempInput.addEventListener('change', function() {
                if (this.value) {
                    const date = new Date(this.value);
                    const day = date.getDate().toString().padStart(2, '0');
                    const month = (date.getMonth() + 1).toString().padStart(2, '0');
                    const thaiYear = date.getFullYear() + 543;
                    
                    document.getElementById('thai-day').value = day;
                    document.getElementById('thai-month').value = month;
                    document.getElementById('thai-year').value = thaiYear;
                    updateThaiDate();
                }
                
                if (document.body.contains(tempInput)) {
                    document.body.removeChild(tempInput);
                }
            });
            
            tempInput.addEventListener('blur', function() {
                setTimeout(() => {
                    if (document.body.contains(tempInput)) {
                        document.body.removeChild(tempInput);
                    }
                }, 100);
            });
            
            tempInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (document.body.contains(tempInput)) {
                        document.body.removeChild(tempInput);
                    }
                }
            });
            
            setTimeout(() => {
                if (document.body.contains(tempInput)) {
                    document.body.removeChild(tempInput);
                }
            }, 10000);
        }

        // Function to update date conversion display
        function updateDateConversion(thaiDate) {
            if (thaiDate) {
                const thaiDateParts = thaiDate.split('/');
                if (thaiDateParts.length === 3) {
                    const day = thaiDateParts[0];
                    const month = thaiDateParts[1];
                    const thaiYear = thaiDateParts[2];
                    const englishYear = parseInt(thaiYear) - 543;
                    
                    const englishDate = englishYear + '-' + month + '-' + day;
                    document.getElementById('english-date-input').value = englishDate;
                    
                    const displayElement = document.getElementById('date-conversion-display');
                    if (displayElement) {
                        displayElement.textContent = 'วันที่ในระบบ: ' + englishDate + ' (ค.ศ.)';
                    }
                }
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Set event listeners for dropdown changes
            document.getElementById('thai-day').addEventListener('change', updateThaiDate);
            document.getElementById('thai-month').addEventListener('change', updateThaiDate);
            document.getElementById('thai-year').addEventListener('change', updateThaiDate);
            
            // Make the date input field clickable
            document.getElementById('thai-date-input').addEventListener('click', function() {
                showSimpleDatePicker();
            });
            
            // Set initial date from existing expense
            const currentThaiDate = "{{ $thaiDate }}";
            if (currentThaiDate) {
                const parts = currentThaiDate.split('/');
                if (parts.length === 3) {
                    document.getElementById('thai-day').value = parts[0];
                    document.getElementById('thai-month').value = parts[1];
                    document.getElementById('thai-year').value = parts[2];
                    updateThaiDate();
                }
            }
        });

        // JavaScript for dynamic type dropdown
        document.getElementById('category-select').addEventListener('change', function() {
            const categoryId = this.value;
            const typeSelect = document.getElementById('type-select');

            typeSelect.innerHTML = '<option value="">-- เลือกรายการ --</option>';

            if (categoryId) {
                fetch('/api/categories/' + categoryId + '/types')
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
                        
                        // Set the current type as selected
                        const currentTypeId = "{{ $expense->type_id }}";
                        if (currentTypeId) {
                            typeSelect.value = currentTypeId;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching types:', error);
                        typeSelect.innerHTML = '<option value="">Error loading types</option>';
                    });
            }
        });

        // Initialize category select and trigger change event to load types
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category-select');
            const currentCategoryId = "{{ $expense->type->category_id }}";
            
            if (currentCategoryId) {
                categorySelect.value = currentCategoryId;
                const event = new Event('change');
                categorySelect.dispatchEvent(event);
            }
        });
    </script>
</body>
</html>