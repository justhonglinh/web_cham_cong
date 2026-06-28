<?php

namespace App\Http\Controllers\Api\WorkSummary;

use App\Exports\WorkSummaryExport;
use App\Http\Controllers\Controller;
use App\Models\WorkSummary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WorkSummaryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $managerId = $request->user()->id;
        $month     = $request->input('month', now()->month);
        $year      = $request->input('year', now()->year);
        $search    = $request->input('search');

        $query = WorkSummary::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->with('user')
            ->where('month', $month)
            ->where('year', $year);

        if ($search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        return response()->json($query->paginate(20));
    }

    public function export(Request $request)
    {
        $month    = $request->input('month', now()->month);
        $year     = $request->input('year', now()->year);
        $fileName = "work-summary-{$month}-{$year}.xlsx";

        return Excel::download(new WorkSummaryExport($request), $fileName);
    }
}
