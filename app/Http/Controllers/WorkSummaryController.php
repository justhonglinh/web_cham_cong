<?php

namespace App\Http\Controllers;

use App\Exports\WorkSummaryExport;
use App\Models\WorkSummary;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WorkSummaryController extends Controller
{
    public function show()
    {
        $years = WorkSummary::select('year')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year')
            ->filter() // loại bỏ null nếu có
            ->toArray();

        $workSummaries = WorkSummary::with('user:id,name,email')->get();

        return view('work_summary_management', compact('workSummaries', 'years'));
    }

    public function export()
    {
        return Excel::download(new WorkSummaryExport, 'work_summary.xlsx');
    }

    public function search(Request $request)
    {
        $query = WorkSummary::with('user:id,name,email');
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
            // Giả sử bạn có trường 'month' hoặc 'work_date' trong work_summary
            $query->where('month', $month);
        }

        // Lọc theo năm
        if ($request->filled('year')) {
            $year = (int) $request->input('year');
            // Giả sử bạn có trường 'year' hoặc 'work_date' trong work_summary
            $query->where('year', $year);
        }

        $workSummaries = $query->get();

        $years = WorkSummary::select('year')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year')
            ->filter() // loại bỏ null nếu có
            ->toArray();

        return view('work_summary_management', compact('workSummaries', 'years'));
    }

}
