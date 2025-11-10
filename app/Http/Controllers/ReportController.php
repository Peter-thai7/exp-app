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
     * Display the hierarchical report form
     */
    public function hierarchicalForm()
    {
        // Get available years for dropdown
        $availableYears = $this->getAvailableYearsData();
        
        return view('reports.hierarchical.index', compact('availableYears'));
    }

    /**
     * Generate hierarchical expense report
     */
    public function generateHierarchicalReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:date,month,year,year_range',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $user = auth()->user();
        $reportType = $request->report_type;

        switch ($reportType) {
            case 'date':
                $startDate = $this->convertThaiDateToGregorian($request->start_date);
                $endDate = $this->convertThaiDateToGregorian($request->end_date);
                $reportData = $this->generateDateReport($user, $startDate, $endDate);
                $view = 'reports.hierarchical.date';
                break;
                
            case 'month':
                $startDate = $this->convertMonthYearToGregorian($request->start_date);
                $endDate = $this->convertMonthYearToGregorian($request->end_date, true);
                $reportData = $this->generateMonthReport($user, $startDate, $endDate);
                $view = 'reports.hierarchical.month';
                break;
                
            case 'year':
                $startDate = $this->convertYearToGregorian($request->start_date);
                $endDate = $this->convertYearToGregorian($request->start_date, true);
                $reportData = $this->generateYearReport($user, $startDate, $endDate);
                $view = 'reports.hierarchical.year';
                break;

            case 'year_range':
                $startDate = $this->convertYearToGregorian($request->start_date);
                $endDate = $this->convertYearToGregorian($request->end_date, true);
                $reportData = $this->generateYearRangeReport($user, $startDate, $endDate);
                $view = 'reports.hierarchical.year_range';
                break;
        }

        return view($view, compact('reportData', 'request'));
    }

    /**
     * Generate daily report with details
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
     * Generate monthly report with daily details
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
            
            // Calculate the start and end dates for this specific month
            $monthStartDate = Carbon::create($month->year, $month->month, 1)->format('Y-m-d');
            $monthEndDate = Carbon::create($month->year, $month->month, 1)->endOfMonth()->format('Y-m-d');
            
            // Get daily summaries for this month
            $month->daily_summaries = $user->expenses()
                ->select(
                    DB::raw('DATE(date) as expense_date'),
                    DB::raw('SUM(amount) as daily_total'),
                    DB::raw('COUNT(*) as daily_count')
                )
                ->whereBetween('date', [$monthStartDate, $monthEndDate])
                ->groupBy('expense_date')
                ->orderBy('expense_date', 'desc')
                ->get();

            // Convert dates to Thai format and format amounts
            foreach ($month->daily_summaries as $day) {
                $day->thai_date = $this->formatToThaiDate($day->expense_date);
                $day->daily_total_formatted = number_format($day->daily_total, 2);
            }
        }

        return [
            'summaries' => $monthlySummaries,
            'total_amount' => $monthlySummaries->sum('total_amount'),
            'total_count' => $monthlySummaries->sum('expense_count'),
            'period' => [
                'start' => $this->formatMonthYearToThai($startDate),
                'end' => $this->formatMonthYearToThai($endDate)
            ]
        ];
    }

    /**
     * Generate yearly report with monthly details (for single year)
     */
    private function generateYearReport($user, $startDate, $endDate)
    {
        // Get the year from start date
        $year = Carbon::parse($startDate)->year;
        
        // Get monthly summaries for the specific year
        $monthlySummaries = $user->expenses()
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as expense_count')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('month', 'desc')
            ->get();

        // Get daily details for each month
        foreach ($monthlySummaries as $month) {
            $month->thai_month = $this->getThaiMonthName($month->month) . ' ' . ($month->year + 543);
            $month->month_key = $month->year . '-' . str_pad($month->month, 2, '0', STR_PAD_LEFT);
            $month->total_amount_formatted = number_format($month->total_amount, 2);
            
            // Calculate the start and end dates for this specific month
            $monthStartDate = Carbon::create($month->year, $month->month, 1)->format('Y-m-d');
            $monthEndDate = Carbon::create($month->year, $month->month, 1)->endOfMonth()->format('Y-m-d');
            
            // Get daily summaries for this month
            $month->daily_summaries = $user->expenses()
                ->select(
                    DB::raw('DATE(date) as expense_date'),
                    DB::raw('SUM(amount) as daily_total'),
                    DB::raw('COUNT(*) as daily_count')
                )
                ->whereBetween('date', [$monthStartDate, $monthEndDate])
                ->groupBy('expense_date')
                ->orderBy('expense_date', 'desc')
                ->get();

            // Convert dates to Thai format and format amounts
            foreach ($month->daily_summaries as $day) {
                $day->thai_date = $this->formatToThaiDate($day->expense_date);
                $day->daily_total_formatted = number_format($day->daily_total, 2);
            }
        }

        $totalAmount = $monthlySummaries->sum('total_amount');
        $totalCount = $monthlySummaries->sum('expense_count');

        return [
            'summaries' => $monthlySummaries,
            'total_amount' => $totalAmount,
            'total_count' => $totalCount,
            'year' => $year + 543,
            'period' => [
                'start' => $this->formatYearToThai($startDate),
                'end' => $this->formatYearToThai($endDate)
            ]
        ];
    }

    /**
     * Generate year range report with yearly and monthly details
     */
    private function generateYearRangeReport($user, $startDate, $endDate)
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
            
            // Calculate the start and end dates for this specific year
            $yearStartDate = Carbon::create($year->year, 1, 1)->format('Y-m-d');
            $yearEndDate = Carbon::create($year->year, 12, 31)->format('Y-m-d');
            
            // Get monthly summaries for this year
            $year->monthly_summaries = $user->expenses()
                ->select(
                    DB::raw('YEAR(date) as year'),
                    DB::raw('MONTH(date) as month'),
                    DB::raw('SUM(amount) as monthly_total'),
                    DB::raw('COUNT(*) as monthly_count')
                )
                ->whereBetween('date', [$yearStartDate, $yearEndDate])
                ->groupBy('year', 'month')
                ->orderBy('month', 'desc')
                ->get();

            // Get daily details for each month
            foreach ($year->monthly_summaries as $month) {
                $month->thai_month = $this->getThaiMonthName($month->month) . ' ' . ($month->year + 543);
                $month->month_key = $month->year . '-' . str_pad($month->month, 2, '0', STR_PAD_LEFT);
                $month->monthly_total_formatted = number_format($month->monthly_total, 2);
                
                // Calculate the start and end dates for this specific month
                $monthStartDate = Carbon::create($month->year, $month->month, 1)->format('Y-m-d');
                $monthEndDate = Carbon::create($month->year, $month->month, 1)->endOfMonth()->format('Y-m-d');
                
                // Get daily summaries for this month
                $month->daily_summaries = $user->expenses()
                    ->select(
                        DB::raw('DATE(date) as expense_date'),
                        DB::raw('SUM(amount) as daily_total'),
                        DB::raw('COUNT(*) as daily_count')
                    )
                    ->whereBetween('date', [$monthStartDate, $monthEndDate])
                    ->groupBy('expense_date')
                    ->orderBy('expense_date', 'desc')
                    ->get();

                // Convert dates to Thai format and format amounts
                foreach ($month->daily_summaries as $day) {
                    $day->thai_date = $this->formatToThaiDate($day->expense_date);
                    $day->daily_total_formatted = number_format($day->daily_total, 2);
                }
            }
        }

        return [
            'summaries' => $yearlySummaries,
            'total_amount' => $yearlySummaries->sum('total_amount'),
            'total_count' => $yearlySummaries->sum('expense_count'),
            'period' => [
                'start' => $this->formatYearToThai($startDate),
                'end' => $this->formatYearToThai($endDate)
            ]
        ];
    }

    /**
     * Get daily details for a specific date (for drill down)
     */
    public function getDailyDetails($date)
    {
        $user = auth()->user();
        $gregorianDate = $this->convertThaiDateToGregorian($date);
        
        $expenses = $user->expenses()
            ->with('type.category')
            ->whereDate('date', $gregorianDate)
            ->orderBy('amount', 'desc')
            ->get();

        $totalAmount = $expenses->sum('amount');
        
        return response()->json([
            'success' => true,
            'date' => $date,
            'total_amount' => $totalAmount,
            'total_amount_formatted' => number_format($totalAmount, 2),
            'expenses' => $expenses->map(function($expense) {
                return [
                    'category' => $expense->type->category->name_th,
                    'type' => $expense->type->name_th,
                    'amount' => number_format($expense->amount, 2),
                    'description' => $expense->description,
                    'date' => $this->formatToThaiDate($expense->date)
                ];
            })
        ]);
    }

    /**
     * Get daily summaries for a specific month (for drill down)
     */
    public function getMonthDailySummaries($year, $month)
    {
        $user = auth()->user();
        
        $startDate = Carbon::create($year, $month, 1)->format('Y-m-d');
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');
        
        $dailySummaries = $user->expenses()
            ->select(
                DB::raw('DATE(date) as expense_date'),
                DB::raw('SUM(amount) as daily_total'),
                DB::raw('COUNT(*) as daily_count')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('expense_date')
            ->orderBy('expense_date', 'desc')
            ->get();

        foreach ($dailySummaries as $day) {
            $day->thai_date = $this->formatToThaiDate($day->expense_date);
            $day->daily_total_formatted = number_format($day->daily_total, 2);
        }

        return response()->json([
            'success' => true,
            'month' => $this->getThaiMonthName($month) . ' ' . ($year + 543),
            'daily_summaries' => $dailySummaries,
            'total_amount' => $dailySummaries->sum('daily_total'),
            'total_amount_formatted' => number_format($dailySummaries->sum('daily_total'), 2)
        ]);
    }

    /**
     * Get monthly summaries for a specific year (for drill down)
     */
    public function getYearMonthlySummaries($year)
    {
        $user = auth()->user();
        
        $startDate = Carbon::create($year, 1, 1)->format('Y-m-d');
        $endDate = Carbon::create($year, 12, 31)->format('Y-m-d');
        
        $monthlySummaries = $user->expenses()
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as monthly_total'),
                DB::raw('COUNT(*) as monthly_count')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('month', 'desc')
            ->get();

        foreach ($monthlySummaries as $month) {
            $month->thai_month = $this->getThaiMonthName($month->month) . ' ' . ($month->year + 543);
            $month->monthly_total_formatted = number_format($month->monthly_total, 2);
        }

        return response()->json([
            'success' => true,
            'year' => $year + 543,
            'monthly_summaries' => $monthlySummaries,
            'total_amount' => $monthlySummaries->sum('monthly_total'),
            'total_amount_formatted' => number_format($monthlySummaries->sum('monthly_total'), 2)
        ]);
    }

    /**
     * Convert Thai Buddhist date to Gregorian date (for daily format)
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
     * Convert Thai month/year to Gregorian date
     */
    private function convertMonthYearToGregorian(string $thaiMonthYear, bool $endOfMonth = false): string
    {
        $dateParts = explode('/', $thaiMonthYear);
        
        if (count($dateParts) === 2) {
            $month = (int)$dateParts[0];
            $buddhistYear = (int)$dateParts[1];
            $gregorianYear = $buddhistYear - 543;
            
            try {
                if ($endOfMonth) {
                    return Carbon::createFromDate($gregorianYear, $month, 1)->endOfMonth()->format('Y-m-d');
                }
                return Carbon::createFromDate($gregorianYear, $month, 1)->format('Y-m-d');
            } catch (\Exception $e) {
                return now()->format('Y-m-d');
            }
        }
        
        return now()->format('Y-m-d');
    }

    /**
     * Convert Thai year to Gregorian date
     */
    private function convertYearToGregorian(string $thaiYear, bool $endOfYear = false): string
    {
        $buddhistYear = (int)$thaiYear;
        $gregorianYear = $buddhistYear - 543;
        
        try {
            if ($endOfYear) {
                return Carbon::createFromDate($gregorianYear, 12, 31)->format('Y-m-d');
            }
            return Carbon::createFromDate($gregorianYear, 1, 1)->format('Y-m-d');
        } catch (\Exception $e) {
            return now()->format('Y-m-d');
        }
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
     * Format month/year to Thai format
     */
    private function formatMonthYearToThai($date)
    {
        $carbonDate = Carbon::parse($date);
        $thaiMonths = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];

        $month = $thaiMonths[$carbonDate->format('n')];
        $year = $carbonDate->format('Y') + 543;

        return "{$month} {$year}";
    }

    /**
     * Format year to Thai format
     */
    private function formatYearToThai($date)
    {
        $carbonDate = Carbon::parse($date);
        return $carbonDate->format('Y') + 543;
    }

    /**
     * Get Thai month name
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
     * Get available years for dropdown
     */
    private function getAvailableYearsData()
    {
        return Expense::where('user_id', auth()->id())
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->map(function($year) {
                return $year + 543;
            })
            ->toArray();
    }

    /**
     * Get available years API endpoint
     */
    public function getAvailableYears()
    {
        $years = $this->getAvailableYearsData();
        return response()->json($years);
    }
}