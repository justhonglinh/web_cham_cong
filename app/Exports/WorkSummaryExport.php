<?php

namespace App\Exports;

use App\Models\WorkSummary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorkSummaryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return WorkSummary::with('user:id,name,email')->get();
    }

    public function map($workSummary): array
    {
        return [
            $workSummary->id,
            $workSummary->user->name ?? '',
            $workSummary->total_work_hours,
            $workSummary->total_overtime_hours,
            $workSummary->total_leave_days,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nhân viên',
            'Tổng giờ làm',
            'Giờ làm thêm',
            'Ngày nghỉ',
        ];
    }
}
