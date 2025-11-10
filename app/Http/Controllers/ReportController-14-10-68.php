<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the main report dashboard
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Get expense report data based on filters
     */
    public function getReportData(Request $request)
    {
        $user = auth()->user();
        
        // Base query
        $query = Expense::with(['type.category'])
            ->where('user_id', $user->id);

        // Apply date filters
        $query = $this->applyDateFilters($query, $request);

        // Get the expenses
        $expenses = $query->orderBy('date', 'desc')->get();

        // Format data for response
        $formattedExpenses = $expenses->map(function ($expense) {
            return [
                'id' => $expense->id,
                'date' => $this->formatToThaiDate($expense->date),
                'date_raw' => $expense->date,
                'category' => $expense->type->category->name_th,
                'type' => $expense->type->name_th,
                'amount' => floatval($expense->amount),
                'description' => $expense->description,
            ];
        });

        // Calculate summary statistics
        $totalAmount = $expenses->sum('amount');
        $averageAmount = $expenses->count() > 0 ? $expenses->avg('amount') : 0;
        $expenseCount = $expenses->count();

        // Get category breakdown for chart
        $categoryBreakdown = $this->getCategoryBreakdown($expenses);

        return response()->json([
            'success' => true,
            'expenses' => $formattedExpenses,
            'summary' => [
                'total_amount' => $totalAmount,
                'average_amount' => round($averageAmount, 2),
                'expense_count' => $expenseCount,
                'total_amount_formatted' => number_format($totalAmount, 2),
                'average_amount_formatted' => number_format($averageAmount, 2),
            ],
            'charts' => [
                'category_breakdown' => $categoryBreakdown,
            ],
            'filters' => $request->all(),
        ]);
    }

    /**
     * Apply date filters to the query
     */
    private function applyDateFilters($query, $request)
    {
        $reportType = $request->get('report_type', 'today');

        switch ($reportType) {
            case 'today':
                $query->whereDate('date', today());
                break;

            case 'specific_date':
                if ($request->has('specific_date') && $request->specific_date) {
                    $query->whereDate('date', $request->specific_date);
                }
                break;

            case 'date_range':
                if ($request->has('start_date') && $request->start_date) {
                    $query->whereDate('date', '>=', $request->start_date);
                }
                if ($request->has('end_date') && $request->end_date) {
                    $query->whereDate('date', '<=', $request->end_date);
                }
                break;

            case 'monthly':
                $year = $request->get('year', date('Y'));
                $month = $request->get('month', date('m'));
                $query->whereYear('date', $year)
                      ->whereMonth('date', $month);
                break;

            case 'month_range':
                $startYear = $request->get('start_year', date('Y'));
                $startMonth = $request->get('start_month', '01');
                $endYear = $request->get('end_year', date('Y'));
                $endMonth = $request->get('end_month', '12');
                
                $startDate = "{$startYear}-{$startMonth}-01";
                $endDate = date('Y-m-t', strtotime("{$endYear}-{$endMonth}-01"));
                
                $query->whereBetween('date', [$startDate, $endDate]);
                break;

            case 'yearly':
                $year = $request->get('year', date('Y'));
                $query->whereYear('date', $year);
                break;
        }

        return $query;
    }

    /**
     * Format date to Thai format
     */
    private function formatToThaiDate($date)
    {
        $carbonDate = Carbon::parse($date);
        $thaiMonths = [
            '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', 
            '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน',
            '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน',
            '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
        ];
        
        $day = $carbonDate->format('d');
        $month = $thaiMonths[$carbonDate->format('m')];
        $year = $carbonDate->format('Y') + 543;
        
        return "{$day} {$month} {$year}";
    }

    /**
     * Get category breakdown for charts
     */
    private function getCategoryBreakdown($expenses)
    {
        $breakdown = [];
        
        foreach ($expenses as $expense) {
            $categoryName = $expense->type->category->name_th;
            $amount = floatval($expense->amount);
            
            if (!isset($breakdown[$categoryName])) {
                $breakdown[$categoryName] = 0;
            }
            
            $breakdown[$categoryName] += $amount;
        }
        
        // Format for Chart.js
        $labels = [];
        $data = [];
        $backgroundColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
        ];
        
        $i = 0;
        foreach ($breakdown as $category => $amount) {
            $labels[] = $category;
            $data[] = $amount;
            $i++;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'backgroundColors' => array_slice($backgroundColors, 0, count($labels)),
        ];
    }

    /**
     * Get available years for dropdown
     */
    public function getAvailableYears()
    {
        $years = Expense::where('user_id', auth()->id())
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        return response()->json($years);
    }
}