<?php

namespace App\Http\Controllers\Api;

use App\Exports\WorkSummaryExport;
use App\Http\Controllers\Controller;
use App\Models\WorkSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ApiWorkSummaryController extends Controller
{
    public function index(Request $request)
    {
        $managerId = Auth::user()->id;
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $search = $request->input('search');

        $query = WorkSummary::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->with('user')
            ->where('month', $month)
            ->where('year', $year);

        if ($search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%"));
        }

        return response()->json($query->paginate(20));
    }

    public function export(Request $request)
    {
        $fileName = 'work-summary-' . ($request->input('month', now()->month)) . '-' . ($request->input('year', now()->year)) . '.xlsx';
        return Excel::download(new WorkSummaryExport($request), $fileName);
    }
}
