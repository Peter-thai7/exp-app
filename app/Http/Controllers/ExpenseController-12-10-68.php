<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = auth()->user()->expenses()->with('type.category')->latest()->get();
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('types')->get();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Convert Thai Buddhist date to Gregorian date if provided
        if ($request->has('thai_date') && !empty($request->thai_date)) {
            $thaiDate = $request->thai_date;
            
            // Parse Thai date (format: DD/MM/YYYY where YYYY is Buddhist year)
            $dateParts = explode('/', $thaiDate);
            
            if (count($dateParts) === 3) {
                $day = (int)$dateParts[0];
                $month = (int)$dateParts[1];
                $buddhistYear = (int)$dateParts[2];
                
                // Convert Buddhist year to Gregorian year
                $gregorianYear = $buddhistYear - 543;
                
                // Create Carbon date object
                try {
                    $date = Carbon::createFromDate($gregorianYear, $month, $day);
                    $request->merge(['date' => $date->format('Y-m-d')]);
                } catch (\Exception $e) {
                    // If date conversion fails, fall back to current date
                    $request->merge(['date' => now()->format('Y-m-d')]);
                }
            }
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'type_id' => 'required|exists:types,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = auth()->id();
        Expense::create($validated);

        return redirect()->route('expenses.index')
                       ->with('success', 'บันทึกค่าใช้จ่ายเรียบร้อยแล้ว!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        // Authorization check - ensure user can only view their own expenses
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        // Authorization check - ensure user can only edit their own expenses
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }
        
        $categories = Category::with('types')->get();
        
        // Convert date to Thai format for editing
        $thaiDate = null;
        if ($expense->date) {
            $date = Carbon::parse($expense->date);
            $thaiYear = $date->year + 543;
            $thaiDate = $date->format('d/m/') . $thaiYear;
        }
        
        return view('expenses.edit', compact('expense', 'categories', 'thaiDate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Authorization check - ensure user can only update their own expenses
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Convert Thai Buddhist date to Gregorian date if provided
        if ($request->has('thai_date') && !empty($request->thai_date)) {
            $thaiDate = $request->thai_date;
            
            // Parse Thai date (format: DD/MM/YYYY where YYYY is Buddhist year)
            $dateParts = explode('/', $thaiDate);
            
            if (count($dateParts) === 3) {
                $day = (int)$dateParts[0];
                $month = (int)$dateParts[1];
                $buddhistYear = (int)$dateParts[2];
                
                // Convert Buddhist year to Gregorian year
                $gregorianYear = $buddhistYear - 543;
                
                // Create Carbon date object
                try {
                    $date = Carbon::createFromDate($gregorianYear, $month, $day);
                    $request->merge(['date' => $date->format('Y-m-d')]);
                } catch (\Exception $e) {
                    // If date conversion fails, fall back to current date
                    $request->merge(['date' => now()->format('Y-m-d')]);
                }
            }
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'type_id' => 'required|exists:types,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
                       ->with('success', 'อัพเดตค่าใช้จ่ายเรียบร้อยแล้ว!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        // Authorization check - ensure user can only delete their own expenses
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }
        
        $expense->delete();

        return redirect()->route('expenses.index')
                       ->with('success', 'ลบค่าใช้จ่ายเรียบร้อยแล้ว!');
    }
}