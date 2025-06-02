<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function show()
    {
        $shifts = Shift::all();
        return view('shifts_management', compact('shifts',));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
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
}
