 <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Add Navigation Buttons -->
            <div class="flex flex-wrap gap-4 mb-6">
                <a href="{{ route('reports.index') }}" class="bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600 transition duration-200 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    รายงาน
                </a>
                <a href="{{ route('expenses.index') }}" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition duration-200 flex items-center">
                    <i class="fas fa-list mr-2"></i>
                    รายการค่าใช้จ่าย
                </a>
                <a href="{{ route('expenses.create') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    เพิ่มค่าใช้จ่ายใหม่
                </a>
                @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-200 flex items-center">
                    <i class="fas fa-cog mr-2"></i>
                    จัดการหมวดหมู่
                </a>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>