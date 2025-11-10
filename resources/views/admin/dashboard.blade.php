 <!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการระบบ - ระบบบันทึกค่าใช้จ่าย</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
        }
        .card {
            transition: all 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .btn {
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="text-center">
                <h1 class="text-2xl font-semibold text-gray-800 mb-2">จัดการระบบ</h1>
                <p class="text-gray-600 mb-4">ระบบจัดการหมวดหมู่และรายการค่าใช้จ่าย</p>
                <a href="{{ route('expenses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    กลับหน้ารายการ
                </a>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            <!-- Add Category Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 card">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    เพิ่มหมวดหมู่ใหม่
                </h2>
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อหมวดหมู่ (ไทย)</label>
                        <input type="text" name="name_th" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อหมวดหมู่ (English)</label>
                        <input type="text" name="name_en" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">คำอธิบาย</label>
                        <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 btn">
                        เพิ่มหมวดหมู่
                    </button>
                </form>
            </div>

            <!-- Add Type Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 card">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    เพิ่มรายการใหม่
                </h2>
                <form action="{{ route('admin.types.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">เลือกหมวดหมู่</label>
                        <select name="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- เลือกหมวดหมู่ --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name_th }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อรายการ (ไทย)</label>
                        <input type="text" name="name_th" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อรายการ (English)</label>
                        <input type="text" name="name_en" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 btn">
                        เพิ่มรายการ
                    </button>
                </form>
            </div>
        </div>

        <!-- Current Categories & Types -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                หมวดหมู่และรายการทั้งหมด
            </h2>
            <div class="space-y-4" id="categories-list">
                @foreach($categories as $category)
                <div class="border border-gray-200 rounded-lg p-4 card">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $category->name_th }}</h3>
                            @if($category->name_en)
                            <p class="text-sm text-gray-600 mt-1">{{ $category->name_en }}</p>
                            @endif
                            @if($category->description)
                            <p class="text-sm text-gray-500 mt-1">{{ $category->description }}</p>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <!-- Edit Category Button -->
                            <button class="edit-category text-blue-600 hover:text-blue-800 p-1 rounded"
                                    data-category-id="{{ $category->id }}"
                                    title="แก้ไขหมวดหมู่">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <!-- Delete Category Button -->
                            <button class="delete-category text-red-600 hover:text-red-800 p-1 rounded"
                                    data-category-id="{{ $category->id }}"
                                    data-category-name="{{ $category->name_th }}"
                                    title="ลบหมวดหมู่">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    @if($category->types->count() > 0)
                    <div class="border-t pt-3 mt-3">
                        <div class="space-y-2">
                            @foreach($category->types as $type)
                            <div class="flex justify-between items-center bg-gray-50 rounded px-3 py-2">
                                <span class="text-gray-700 text-sm">• {{ $type->name_th }}</span>
                                <div class="flex space-x-2">
                                    <!-- Edit Type Button -->
                                    <button class="edit-type text-blue-600 hover:text-blue-800"
                                            data-type-id="{{ $type->id }}"
                                            title="แก้ไขรายการ">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <!-- Delete Type Button -->
                                    <button class="delete-type text-red-600 hover:text-red-800"
                                            data-type-id="{{ $type->id }}"
                                            data-type-name="{{ $type->name_th }}"
                                            title="ลบรายการ">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                
                @if($categories->count() == 0)
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    <p>ยังไม่มีหมวดหมู่</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">แก้ไขหมวดหมู่</h3>
            <form id="editCategoryForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="category_id" id="edit_category_id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">ชื่อหมวดหมู่ (ไทย)</label>
                        <input type="text" name="name_th" id="edit_name_th" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">ชื่อหมวดหมู่ (English)</label>
                        <input type="text" name="name_en" id="edit_name_en"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">คำอธิบาย</label>
                        <textarea name="description" id="edit_description" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        ยกเลิก
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Category Confirmation Modal -->
    <div id="deleteCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-red-600">ยืนยันการลบหมวดหมู่</h3>
            <p class="mb-4">คุณแน่ใจว่าต้องการลบหมวดหมู่ "<span id="delete_category_name"></span>"?</p>
            <p class="text-sm text-red-600 mb-4">⚠️ การกระทำนี้ไม่สามารถยกเลิกได้</p>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeDeleteCategoryModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    ยกเลิก
                </button>
                <button type="button" onclick="confirmDeleteCategory()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    ลบ
                </button>
            </div>
            <input type="hidden" id="delete_category_id">
        </div>
    </div>

    <!-- Delete Type Confirmation Modal -->
    <div id="deleteTypeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-red-600">ยืนยันการลบรายการ</h3>
            <p class="mb-4">คุณแน่ใจว่าต้องการลบรายการ "<span id="delete_type_name"></span>"?</p>
            <p class="text-sm text-red-600 mb-4">⚠️ การกระทำนี้ไม่สามารถยกเลิกได้</p>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeDeleteTypeModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    ยกเลิก
                </button>
                <button type="button" onclick="confirmDeleteType()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    ลบ
                </button>
            </div>
            <input type="hidden" id="delete_type_id">
        </div>
    </div>

    <!-- Edit Type Modal -->
    <div id="editTypeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">แก้ไขรายการ</h3>
            <form id="editTypeForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="type_id" id="edit_type_id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">เลือกหมวดหมู่</label>
                        <select name="category_id" id="edit_type_category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">-- เลือกหมวดหมู่ --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name_th }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">ชื่อรายการ (ไทย)</label>
                        <input type="text" name="name_th" id="edit_type_name_th" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">ชื่อรายการ (English)</label>
                        <input type="text" name="name_en" id="edit_type_name_en" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeEditTypeModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        ยกเลิก
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Edit Category Modal Functions
        function openEditModal(categoryId) {
            console.log("Opening modal for category ID:", categoryId);
            fetch(`/admin/categories/${categoryId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(category => {
                    document.getElementById('edit_category_id').value = category.id;
                    document.getElementById('edit_name_th').value = category.name_th;
                    document.getElementById('edit_name_en').value = category.name_en || '';
                    document.getElementById('edit_description').value = category.description || '';
                    document.getElementById('editCategoryModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching category data:', error);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูลหมวดหมู่');
                });
        }

        function closeEditModal() {
            document.getElementById('editCategoryModal').classList.add('hidden');
        }

        // Edit Type Modal Functions
        function openEditTypeModal(typeId) {
            console.log("Opening modal for type ID:", typeId);
            fetch(`/admin/types/${typeId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(type => {
                    document.getElementById('edit_type_id').value = type.id;
                    document.getElementById('edit_type_category_id').value = type.category_id;
                    document.getElementById('edit_type_name_th').value = type.name_th;
                    document.getElementById('edit_type_name_en').value = type.name_en || '';
                    document.getElementById('editTypeModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching type data:', error);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูลรายการ');
                });
        }

        function closeEditTypeModal() {
            document.getElementById('editTypeModal').classList.add('hidden');
        }

        // Delete Modal Functions
        function openDeleteCategoryModal(categoryId, categoryName) {
            document.getElementById('delete_category_id').value = categoryId;
            document.getElementById('delete_category_name').textContent = categoryName;
            document.getElementById('deleteCategoryModal').classList.remove('hidden');
        }

        function closeDeleteCategoryModal() {
            document.getElementById('deleteCategoryModal').classList.add('hidden');
        }

        function openDeleteTypeModal(typeId, typeName) {
            document.getElementById('delete_type_id').value = typeId;
            document.getElementById('delete_type_name').textContent = typeName;
            document.getElementById('deleteTypeModal').classList.remove('hidden');
        }

        function closeDeleteTypeModal() {
            document.getElementById('deleteTypeModal').classList.add('hidden');
        }

        function confirmDeleteCategory() {
            const categoryId = document.getElementById('delete_category_id').value;

            fetch(`/admin/categories/${categoryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        closeDeleteCategoryModal();
                        location.reload();
                    } else if (data.error) {
                        alert(data.error);
                        closeDeleteCategoryModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการลบข้อมูล');
                    closeDeleteCategoryModal();
                });
        }

        function confirmDeleteType() {
            const typeId = document.getElementById('delete_type_id').value;

            fetch(`/admin/types/${typeId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        closeDeleteTypeModal();
                        location.reload();
                    } else if (data.error) {
                        alert(data.error);
                        closeDeleteTypeModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการลบข้อมูล');
                    closeDeleteTypeModal();
                });
        }

        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log("DOM loaded. Attaching event listeners...");

            // 1. Edit Category Form Submission
            const editForm = document.getElementById('editCategoryForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log("Edit form submitted");

                    const categoryId = document.getElementById('edit_category_id').value;
                    const formData = new FormData(this);

                    fetch(`/admin/categories/${categoryId}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-HTTP-Method-Override': 'PUT'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.success);
                                closeEditModal();
                                location.reload();
                            } else if (data.error) {
                                alert('Error: ' + data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('เกิดข้อผิดพลาดในการอัพเดตข้อมูล');
                        });
                });
            }

            // 2. Edit Type Form Submission
            const editTypeForm = document.getElementById('editTypeForm');
            if (editTypeForm) {
                editTypeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log("Edit TYPE form submitted");

                    const typeId = document.getElementById('edit_type_id').value;
                    const formData = new FormData(this);

                    fetch(`/admin/types/${typeId}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-HTTP-Method-Override': 'PUT'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.success);
                                closeEditTypeModal();
                                location.reload();
                            } else if (data.error) {
                                alert('Error: ' + data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('เกิดข้อผิดพลาดในการอัพเดตข้อมูลรายการ');
                        });
                });
            }

            // 3. EVENT DELEGATION: Attach a single listener to the parent container
            const categoriesList = document.getElementById('categories-list');
            if (categoriesList) {
                categoriesList.addEventListener('click', function(event) {
                    // Check if the clicked element is an edit CATEGORY button
                    const editCategoryButton = event.target.closest('.edit-category');
                    if (editCategoryButton) {
                        const categoryId = editCategoryButton.getAttribute('data-category-id');
                        console.log("Edit CATEGORY button clicked for ID:", categoryId);
                        openEditModal(categoryId);
                    }

                    // Check if the clicked element is an edit TYPE button
                    const editTypeButton = event.target.closest('.edit-type');
                    if (editTypeButton) {
                        const typeId = editTypeButton.getAttribute('data-type-id');
                        console.log("Edit TYPE button clicked for ID:", typeId);
                        openEditTypeModal(typeId);
                    }

                    // Check for DELETE CATEGORY button
                    const deleteCategoryButton = event.target.closest('.delete-category');
                    if (deleteCategoryButton) {
                        const categoryId = deleteCategoryButton.getAttribute('data-category-id');
                        const categoryName = deleteCategoryButton.getAttribute('data-category-name');
                        console.log("Delete CATEGORY button clicked for:", categoryName);
                        openDeleteCategoryModal(categoryId, categoryName);
                    }

                    // Check for DELETE TYPE button
                    const deleteTypeButton = event.target.closest('.delete-type');
                    if (deleteTypeButton) {
                        const typeId = deleteTypeButton.getAttribute('data-type-id');
                        const typeName = deleteTypeButton.getAttribute('data-type-name');
                        console.log("Delete TYPE button clicked for:", typeName);
                        openDeleteTypeModal(typeId, typeName);
                    }
                });
            }

            // 4. Close modals when clicking outside
            const categoryModal = document.getElementById('editCategoryModal');
            if (categoryModal) {
                categoryModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeEditModal();
                    }
                });
            }

            const typeModal = document.getElementById('editTypeModal');
            if (typeModal) {
                typeModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeEditTypeModal();
                    }
                });
            }

            const deleteCategoryModal = document.getElementById('deleteCategoryModal');
            if (deleteCategoryModal) {
                deleteCategoryModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeDeleteCategoryModal();
                    }
                });
            }

            const deleteTypeModal = document.getElementById('deleteTypeModal');
            if (deleteTypeModal) {
                deleteTypeModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeDeleteTypeModal();
                    }
                });
            }
        });
    </script>

</body>

</html>