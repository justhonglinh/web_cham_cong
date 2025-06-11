<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function show()
    {
        $userId = Auth::user()->id;
        $shifts = Shift::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('shifts_management', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'user_id' => 'required|exists:users,id',
        ]);

        Shift::create($request->all());
        return redirect()->route('shifts.index')->with('success', 'Thêm ca làm thành công');
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $shift->update($request->all());

        return redirect()->route('shifts.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Xóa ca làm thành công.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $userId = Auth::user()->id;

        $shifts = Shift::where('user_id', $userId)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('start_time', 'like', "%{$query}%")
                  ->orWhere('end_time', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($shifts);
    }
}
