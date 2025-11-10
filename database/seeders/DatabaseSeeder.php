<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Type;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@expense.com',
            'password' => Hash::make('password'), // Change this later!
            'role' => 'admin',
        ]);

        // Create Sample Categories and Types in Thai
        $categories = [
            'อาหาร' => ['ข้าวเช้า', 'ข้าวเที่ยง', 'ข้าวเย็น', 'ของว่าง'],
            'การเดินทาง' => ['น้ำมัน', 'ค่าที่จอดรถ', 'บริการรถโดยสาร', 'ค่าตั๋วเครื่องบิน'],
            'ค่าสาธารณูปโภค' => ['ค่าน้ำ', 'ค่าไฟฟ้า', 'ค่าโทรศัพท์', 'อินเทอร์เน็ต'],
            'การช้อปปิ้ง' => ['เสื้อผ้า', 'เครื่องใช้ในบ้าน', 'ของขวัญ'],
        ];

        foreach ($categories as $catName => $types) {
            $category = Category::create(['name_th' => $catName]);

            foreach ($types as $typeName) {
                Type::create([
                    'category_id' => $category->id,
                    'name_th' => $typeName
                ]);
            }
        }

        // You can create sample regular users here too
        User::factory(3)->create(['role' => 'user']);
    }
}
