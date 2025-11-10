<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;

class AdminController extends Controller
{

// Show the form for editing the specified type (item).

public function editType(Type $type)
{
    // This will return the type data as JSON for the modal form
    return response()->json($type);
}

/**
 * Update the specified type (item) in storage.
 */
public function updateType(Request $request, Type $type)
{
    $request->validate([
        'name_th' => 'required|string|max:255',
        'name_en' => 'nullable|string|max:255',
        'category_id' => 'required|exists:categories,id' // Ensure the new category exists
    ]);

    $type->update($request->only(['category_id', 'name_th', 'name_en']));

    return response()->json(['success' => 'อัพเดตรายการเรียบร้อยแล้ว']);
}

// Delete the specified category.

public function deleteCategory(Category $category)
{
    // Check if category has types before deleting
    if ($category->types()->count() > 0) {
        return response()->json([
            'error' => 'ไม่สามารถลบหมวดหมู่ได้เนื่องจากมีรายการใช้งานอยู่ กรุณาลบรายการทั้งหมดก่อน'
        ], 422);
    }

    $category->delete();

    return response()->json(['success' => 'ลบหมวดหมู่เรียบร้อยแล้ว']);
}

// Delete the specified type (item).
 
public function deleteType(Type $type)
{
    $type->delete();

    return response()->json(['success' => 'ลบรายการเรียบร้อยแล้ว']);
}

   
// Show the form for editing the specified category.
 
public function editCategory(Category $category)
{
    // This will return the category data as JSON for the modal form
    return response()->json($category);
}

// Update the specified category in storage.
public function updateCategory(Request $request, Category $category)
{
    $request->validate([
        'name_th' => 'required|string|max:255',
        'name_en' => 'nullable|string|max:255',
        'description' => 'nullable|string'
    ]);

    $category->update($request->only(['name_th', 'name_en', 'description']));

    return response()->json(['success' => 'อัพเดตหมวดหมู่เรียบร้อยแล้ว']);
}

    public function dashboard()
    {
        return view('admin.dashboard', [
            'categories' => Category::with('types')->get()
        ]);
    }

    // Category Management
    public function createCategory(Request $request)
    {
        $request->validate([
            'name_th' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        Category::create($request->only(['name_th', 'name_en', 'description']));

        return redirect()->route('admin.dashboard')->with('success', 'เพิ่มหมวดหมู่เรียบร้อยแล้ว');
    }

    // Type Management
    public function createType(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_th' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255'
        ]);

        Type::create($request->only(['category_id', 'name_th', 'name_en']));

        return redirect()->route('admin.dashboard')->with('success', 'เพิ่มรายการเรียบร้อยแล้ว');
    }
}
