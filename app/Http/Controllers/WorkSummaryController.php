<?php

namespace App\Http\Controllers;

use App\Exports\WorkSummaryExport;
use App\Models\User;
use App\Models\WorkSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class WorkSummaryController extends Controller
{
    public function show()
    {
        $managerId = Auth::user()->id;

        // Lấy danh sách năm từ bảng work_summary
        $years = WorkSummary::select('year')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year')
            ->filter() // loại bỏ null nếu có
            ->toArray();

        // Lấy danh sách user_id của những nhân viên có manager = $managerId
        $userIds = User::where('manager', $managerId)->pluck('id');

        // Lấy work summaries của các user có trong $userIds, đồng thời lấy thông tin user liên quan
        $workSummaries = WorkSummary::with('user:id,name,email')
            ->whereIn('user_id', $userIds)
            ->get();

        return view('work_summary_management', compact('workSummaries', 'years'));
    }


    public function export(Request $request)
    {
        // Truyền các tham số tìm kiếm vào class WorkSummaryExport
        return Excel::download(new WorkSummaryExport($request), 'work_summary.xlsx');
    }

    public function search(Request $request)
    {
        $managerId = Auth::user()->id;

        // Lấy danh sách user_id có manager là $managerId
        $userIds = User::where('manager', $managerId)->pluck('id');

        $query = WorkSummary::with('user:id,name,email')
            ->whereIn('user_id', $userIds); // Lọc theo manager

        // Lọc theo user (có thể là ID hoặc tên)
        if ($request->filled('user')) {
            $userInput = $request->input('user');
            $query->whereHas('user', function($q) use ($userInput) {
                if (is_numeric($userInput)) {
                    $q->where('id', $userInput);
                } else {
                    $q->where('name', 'like', '%' . $userInput . '%');
                }
            });
        }

        // Lọc theo tháng
        if ($request->filled('month')) {
            $month = (int) $request->input('month');
            $query->where('month', $month);
        }

        // Lọc theo năm
        if ($request->filled('year')) {
            $year = (int) $request->input('year');
            $query->where('year', $year);
        }

        // Lọc theo ngày
        if ($request->filled('date')) {
            $date = $request->input('date');
            // Kiểm tra định dạng ngày và áp dụng bộ lọc
            $query->whereDate('created_at', '=', $date); // Hoặc thay 'created_at' bằng cột ngày mà bạn sử dụng trong bảng WorkSummary
        }

        $workSummaries = $query->get();

        $years = WorkSummary::select('year')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year')
            ->filter()
            ->toArray();

        return view('work_summary_management', compact('workSummaries', 'years'));
    }

}
