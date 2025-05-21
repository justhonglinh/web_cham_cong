<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
{
    $shifts = Shift::all();
    return view('shifts_management', compact('shifts'));
}


    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i',
    ]);

    Shift::create([
        'name' => $request->name,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
    ]);

    return redirect()->route('shifts.index')->with('success', 'Thêm ca làm thành công');
}
    public function edit($id)
{
    $shift = Shift::findOrFail($id);
    $shifts = Shift::all(); // Để hiển thị danh sách bên dưới
    return view('shifts_management', [
        'shift' => $shift,
        'shifts' => $shifts,
        'editMode' => true
    ]);
}
public function update(Request $request, $id)
{
    // Validate dữ liệu
    $request->validate([
        'name' => 'required|string|max:255',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i',
    ]);

    // Tìm ca làm theo ID
    $shift = Shift::findOrFail($id);

    // Cập nhật thông tin
    $shift->name = $request->name;
    $shift->start_time = $request->start_time;
    $shift->end_time = $request->end_time;
    $shift->save();

    // Quay lại trang danh sách với thông báo
    return redirect()->route('shifts.edit', $shift->id)->with('success', 'Cập nhật thành công!');
}

public function destroy($id)
{
    $shift = Shift::findOrFail($id);
    $shift->delete();

    return redirect()->route('shifts.index')->with('success', 'Xóa ca làm thành công.');
}

}
