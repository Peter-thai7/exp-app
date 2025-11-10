<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HierarchicalReportExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected $reportData;
    protected $reportType;
    protected $request;

    public function __construct($reportData, $reportType, $request)
    {
        $this->reportData = $reportData;
        $this->reportType = $reportType;
        $this->request = $request;
    }

    public function array(): array
    {
        $data = [];
        
        switch ($this->reportType) {
            case 'date':
                $data = $this->formatDateReportData();
                break;
            case 'month':
                $data = $this->formatMonthReportData();
                break;
            case 'year':
                $data = $this->formatYearReportData();
                break;
            case 'year_range':
                $data = $this->formatYearRangeReportData();
                break;
        }
        
        return $data;
    }

    public function headings(): array
    {
        switch ($this->reportType) {
            case 'date':
                return ['วันที่', 'จำนวนรายการ', 'ยอดรวม', 'รายละเอียด'];
            case 'month':
                return ['เดือน', 'จำนวนรายการ', 'ยอดรวม', 'วันที่มีรายการ'];
            case 'year':
                return ['เดือน', 'จำนวนรายการ', 'ยอดรวม', 'วันที่มีรายการ'];
            case 'year_range':
                return ['ปี', 'จำนวนรายการ', 'ยอดรวม', 'เดือนที่มีรายการ'];
            default:
                return ['ลำดับ', 'ข้อมูล', 'จำนวน', 'ยอดรวม'];
        }
    }

    public function title(): string
    {
        $titles = [
            'date' => 'รายงานรายวัน',
            'month' => 'รายงานรายเดือน', 
            'year' => 'รายงานรายปี',
            'year_range' => 'รายงานหลายปี'
        ];
        
        return $titles[$this->reportType] ?? 'รายงาน';
    }

    private function formatDateReportData(): array
    {
        $data = [];
        
        foreach ($this->reportData['summaries'] as $summary) {
            $details = [];
            foreach ($summary->details as $expense) {
                $details[] = $expense->type->category->name_th . ' - ' . 
                           $expense->type->name_th . ' : ' . 
                           number_format($expense->amount, 2);
            }
            
            $data[] = [
                $summary->thai_date,
                $summary->expense_count,
                $summary->total_amount,
                implode("\n", $details)
            ];
        }
        
        return $data;
    }

    private function formatMonthReportData(): array
    {
        $data = [];
        
        foreach ($this->reportData['summaries'] as $summary) {
            $days = [];
            foreach ($summary->daily_summaries as $day) {
                $days[] = $day->thai_date . ' : ' . number_format($day->daily_total, 2);
            }
            
            $data[] = [
                $summary->thai_month,
                $summary->expense_count,
                $summary->total_amount,
                implode("\n", $days)
            ];
        }
        
        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:D' => ['alignment' => ['wrapText' => true]]
        ];
    }
}