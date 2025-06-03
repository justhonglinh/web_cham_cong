<?php

namespace App\Exports;

use App\Models\User;
use App\Models\WorkSummary;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorkSummaryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $managerId = Auth::user()->id;

        // Lấy danh sách user_id có manager là $managerId
        $userIds = User::where('manager', $managerId)->pluck('id');

        // Lấy WorkSummary của các user này kèm theo thông tin user
        return WorkSummary::with('user:id,name,email')
            ->whereIn('user_id', $userIds)
            ->get();
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
