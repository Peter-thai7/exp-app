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
     * NEW: Generate hierarchical expense report
     */
    public function generateHierarchicalReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:date,month,year',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $user = auth()->user();
        $reportType = $request->report_type;
        
        // Convert Thai dates to Gregorian
        $startDate = $this->convertThaiDateToGregorian($request->start_date);
        $endDate = $this->convertThaiDateToGregorian($request->end_date);

        switch ($reportType) {
            case 'date':
                $reportData = $this->generateDateReport($user, $startDate, $endDate);
                $view = 'reports.hierarchical.date';
                break;
                
            case 'month':
                $reportData = $this->generateMonthReport($user, $startDate, $endDate);
                $view = 'reports.hierarchical.month';
                break;
                
            case 'year':
                $reportData = $this->generateYearReport($user, $startDate, $endDate);
                $view = 'reports.hierarchical.year';
                break;
        }

        return view($view, compact('reportData', 'request'));
    }

    /**
     * NEW: Generate daily report with details
     */
    private function generateDateReport($user, $startDate, $endDate)
    {
        // Get daily summaries
        $dailySummaries = $user->expenses()
            ->select(
                DB::raw('DATE(date) as expense_date'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as expense_count')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('expense_date')
            ->orderBy('expense_date', 'desc')
            ->get();

        // Get details for each day
        foreach ($dailySummaries as $day) {
            $day->details = $user->expenses()
                ->with('type.category')
                ->whereDate('date', $day->expense_date)
                ->orderBy('amount', 'desc')
                ->get();
            
            // Convert to Thai date for display
            $day->thai_date = $this->formatToThaiDate($day->expense_date);
            $day->total_amount_formatted = number_format($day->total_amount, 2);
        }

        return [
            'summaries' => $dailySummaries,
            'total_amount' => $dailySummaries->sum('total_amount'),
            'total_count' => $dailySummaries->sum('expense_count'),
            'period' => [
                'start' => $this->formatToThaiDate($startDate),
                'end' => $this->formatToThaiDate($endDate)
            ]
        ];
    }

    /**
     * NEW: Generate monthly report with daily details
     */
    private function generateMonthReport($user, $startDate, $endDate)
    {
        // Get monthly summaries
        $monthlySummaries = $user->expenses()
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as expense_count')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Get daily details for each month
        foreach ($monthlySummaries as $month) {
            $month->thai_month = $this->getThaiMonthName($month->month) . ' ' . ($month->year + 543);
            $month->month_key = $month->year . '-' . str_pad($month->month, 2, '0', STR_PAD_LEFT);
            $month->total_amount_formatted = number_format($month->total_amount, 2);
            
            // Get daily summaries for this month
            $month->daily_details = $user->expenses()
                ->select(
                    DB::raw('DATE(date) as expense_date'),
                    DB::raw('SUM(amount) as daily_total'),
                    DB::raw('COUNT(*) as daily_count')
                )
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->groupBy('expense_date')
                ->orderBy('expense_date', 'desc')
                ->get();

            // Convert dates to Thai format and format amounts
            foreach ($month->daily_details as $day) {
                $day->thai_date = $this->formatToThaiDate($day->expense_date);
                $day->daily_total_formatted = number_format($day->daily_total, 2);
            }
        }

        return [
            'summaries' => $monthlySummaries,
            'total_amount' => $monthlySummaries->sum('total_amount'),
            'total_count' => $monthlySummaries->sum('expense_count'),
            'period' => [
                'start' => $this->formatToThaiDate($startDate),
                'end' => $this->formatToThaiDate($endDate)
            ]
        ];
    }

    /**
     * NEW: Generate yearly report with monthly details
     */
    private function generateYearReport($user, $startDate, $endDate)
    {
        // Get yearly summaries
        $yearlySummaries = $user->expenses()
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as expense_count')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // Get monthly details for each year
        foreach ($yearlySummaries as $year) {
            $year->thai_year = $year->year + 543;
            $year->total_amount_formatted = number_format($year->total_amount, 2);
            
            // Get monthly summaries for this year
            $year->monthly_details = $user->expenses()
                ->select(
                    DB::raw('YEAR(date) as year'),
                    DB::raw('MONTH(date) as month'),
                    DB::raw('SUM(amount) as monthly_total'),
                    DB::raw('COUNT(*) as monthly_count')
                )
                ->whereYear('date', $year->year)
                ->groupBy('year', 'month')
                ->orderBy('month', 'desc')
                ->get();

            // Convert to Thai month names and format amounts
            foreach ($year->monthly_details as $month) {
                $month->thai_month = $this->getThaiMonthName($month->month) . ' ' . ($month->year + 543);
                $month->monthly_total_formatted = number_format($month->monthly_total, 2);
            }
        }

        return [
            'summaries' => $yearlySummaries,
            'total_amount' => $yearlySummaries->sum('total_amount'),
            'total_count' => $yearlySummaries->sum('expense_count'),
            'period' => [
                'start' => $this->formatToThaiDate($startDate),
                'end' => $this->formatToThaiDate($endDate)
            ]
        ];
    }

    /**
     * NEW: Convert Thai Buddhist date to Gregorian date
     */
    private function convertThaiDateToGregorian(string $thaiDate): string
    {
        $dateParts = explode('/', $thaiDate);
        
        if (count($dateParts) === 3) {
            $day = (int)$dateParts[0];
            $month = (int)$dateParts[1];
            $buddhistYear = (int)$dateParts[2];
            $gregorianYear = $buddhistYear - 543;
            
            try {
                return Carbon::createFromDate($gregorianYear, $month, $day)->format('Y-m-d');
            } catch (\Exception $e) {
                return now()->format('Y-m-d');
            }
        }
        
        return now()->format('Y-m-d');
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
     * NEW: Get Thai month name
     */
    private function getThaiMonthName(int $month): string
    {
        $thaiMonths = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        
        return $thaiMonths[$month] ?? 'ไม่ทราบ';
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