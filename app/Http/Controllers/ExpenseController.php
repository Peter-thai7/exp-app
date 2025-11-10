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
        $expenses = auth()->user()
            ->expenses()
            ->with('type.category')
            ->latest()
            ->paginate(10);

        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Expense::class);
        
        $categories = Category::with('types')->get();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Expense::class);
        
        $validatedData = $this->validateRequest($request);
        $validatedData['user_id'] = auth()->id();
        
        Expense::create($validatedData);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'บันทึกค่าใช้จ่ายเรียบร้อยแล้ว!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        
        $categories = Category::with('types')->get();
        $thaiDate = $this->convertToThaiDate($expense->date);
        
        return view('expenses.edit', compact('expense', 'categories', 'thaiDate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);
        
        $validatedData = $this->validateRequest($request);
        $expense->update($validatedData);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'อัพเดตค่าใช้จ่ายเรียบร้อยแล้ว!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'ลบค่าใช้จ่ายเรียบร้อยแล้ว!');
    }

    /**
     * Validate the request data with common rules
     */
    private function validateRequest(Request $request): array
    {
        // Convert Thai date to Gregorian date if provided
        if ($request->filled('thai_date')) {
            $request->merge(['date' => $this->convertThaiDateToGregorian($request->thai_date)]);
        }

        return $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'type_id' => 'required|exists:types,id',
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'description' => 'nullable|string|max:500',
        ]);
    }

    /**
     * Convert Thai Buddhist date to Gregorian date
     */
    private function convertThaiDateToGregorian(?string $thaiDate): string
    {
        if (empty($thaiDate)) {
            return now()->format('Y-m-d');
        }

        $dateParts = explode('/', $thaiDate);
        
        if (count($dateParts) !== 3) {
            return now()->format('Y-m-d');
        }

        [$day, $month, $buddhistYear] = $dateParts;

        // Validate date components
        if (!is_numeric($day) || !is_numeric($month) || !is_numeric($buddhistYear)) {
            return now()->format('Y-m-d');
        }

        $day = (int)$day;
        $month = (int)$month;
        $buddhistYear = (int)$buddhistYear;

        // Convert Buddhist year to Gregorian year
        $gregorianYear = $buddhistYear - 543;

        // Validate date
        if (!checkdate($month, $day, $gregorianYear)) {
            return now()->format('Y-m-d');
        }

        try {
            return Carbon::createFromDate($gregorianYear, $month, $day)
                ->format('Y-m-d');
        } catch (\Exception $e) {
            return now()->format('Y-m-d');
        }
    }

    /**
     * Convert Gregorian date to Thai Buddhist date for display
     */
    private function convertToThaiDate(?string $gregorianDate): ?string
    {
        if (empty($gregorianDate)) {
            return null;
        }

        try {
            $date = Carbon::parse($gregorianDate);
            $thaiYear = $date->year + 543;
            return $date->format('d/m/') . $thaiYear;
        } catch (\Exception $e) {
            return null;
        }
    }
}