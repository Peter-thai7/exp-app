<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ระบบบันทึกค่าใช้จ่ายส่วนตัว">
    
    <title>สมัครสมาชิก - ระบบบันทึกค่าใช้จ่าย</title>
    
    <!-- Tailwind CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <!-- Google Fonts - Thai font support -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl p-8 card-shadow">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">สมัครสมาชิก</h2>
                <p class="mt-2 text-sm text-gray-600">เริ่มต้นบันทึกค่าใช้จ่ายของคุณ</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ชื่อผู้ใช้</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent input-focus transition duration-200"
                           placeholder="กรอกชื่อผู้ใช้">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">อีเมล</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent input-focus transition duration-200"
                           placeholder="กรอกอีเมลของคุณ">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">รหัสผ่าน</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent input-focus transition duration-200"
                           placeholder="กรอกรหัสผ่าน (อย่างน้อย 8 ตัวอักษร)">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">ยืนยันรหัสผ่าน</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent input-focus transition duration-200"
                           placeholder="กรอกรหัสผ่านอีกครั้ง">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Register Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white py-3 px-4 rounded-lg font-medium hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 transform hover:-translate-y-0.5">
                    สมัครสมาชิก
                </button>

                <!-- Login Link -->
                <div class="text-center pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600">
                        มีบัญชีแล้ว?
                        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 ml-1 transition duration-200">
                            เข้าสู่ระบบ
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <p class="text-white text-sm opacity-80">© 2024 ระบบบันทึกค่าใช้จ่าย. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
