 <?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes - ALL admin routes should be inside this group
    Route::middleware(['auth'])->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/categories', [AdminController::class, 'createCategory'])->name('admin.categories.store');
        Route::post('/admin/types', [AdminController::class, 'createType'])->name('admin.types.store');
        
        // EDIT routes
        Route::get('/admin/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('admin.categories.edit');
        Route::put('/admin/categories/{category}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
        Route::get('/admin/types/{type}/edit', [AdminController::class, 'editType'])->name('admin.types.edit');
        Route::put('/admin/types/{type}', [AdminController::class, 'updateType'])->name('admin.types.update');
        
        // DELETE routes - MUST be inside this group
        Route::delete('/admin/categories/{category}', [AdminController::class, 'deleteCategory'])->name('admin.categories.destroy');
        Route::delete('/admin/types/{type}', [AdminController::class, 'deleteType'])->name('admin.types.destroy');
    });

    // Expense routes - This one line creates ALL CRUD routes
    Route::resource('expenses', ExpenseController::class);

    // API route for fetching types by category
    Route::get('/api/categories/{category}/types', function ($categoryId) {
        $types = \App\Models\Type::where('category_id', $categoryId)->get();
        return response()->json($types);
    })->middleware('auth');
});

require __DIR__.'/auth.php';