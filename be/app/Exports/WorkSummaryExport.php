<?php
namespace App\Exports;

use App\Models\User;
use App\Models\WorkSummary;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorkSummaryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithCustomStartCell
{
    protected $user;
    protected $month;
    protected $year;

    // Constructor nhận các tham số tìm kiếm
    public function __construct($request)
    {
        $this->user = $request->user;    // Nhân viên
        $this->month = $request->month;  // Tháng
        $this->year = $request->year;    // Năm
    }

    // Phương thức collection lọc dữ liệu
    public function collection()
    {
        $managerId = Auth::user()->id;

        // Lấy danh sách user_id có manager là $managerId
        $userIds = User::where('manager', $managerId)->pluck('id');

        $query = WorkSummary::with('user:id,name,email')->whereIn('user_id', $userIds);

        // Lọc theo nhân viên (nếu có)
        if ($this->user) {
            $query->where(function($q) {
                $q->whereHas('user', function($query) {
                    $query->where('name', 'like', '%' . $this->user . '%')
                        ->orWhere('email', 'like', '%' . $this->user . '%'); // Tìm theo tên hoặc email
                });
            });
        }

        // Lọc theo tháng (nếu có)
        if ($this->month) {
            $query->where('month', $this->month);
        }

        // Lọc theo năm (nếu có)
        if ($this->year) {
            $query->where('year', $this->year);
        }

        return $query->get(); // Lấy dữ liệu đã lọc
    }

    // Phương thức map để xuất dữ liệu vào Excel
    public function map($workSummary): array
    {
        return [
            $workSummary->id,
            $workSummary->user->name ?? '',
            $workSummary->month . '/' . $workSummary->year,
            $workSummary->total_work_hours ?: '',
            $workSummary->total_overtime_hours ?: '',
            $workSummary->total_leave_days ?: '',
        ];
    }

    // Tiêu đề cột khi xuất Excel
    public function headings(): array
    {
        return [
            'ID',
            'Nhân viên',
            'Thời gian',
            'Tổng giờ làm',
            'Giờ làm thêm',
            'Ngày nghỉ',
        ];
    }

    // Tạo tên file Excel
    public function title(): string
    {
        $month = $this->month ?? now()->month;
        $year = $this->year ?? now()->year;
        return "Bảng Công Tháng {$month} Năm {$year}";
    }

    // Tùy chỉnh style cho Excel
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'A1:F1' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    // Bắt đầu từ ô A1
    public function startCell(): string
    {
        return 'A1';
    }
}
