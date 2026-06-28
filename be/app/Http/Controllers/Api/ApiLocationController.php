<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLocationController extends Controller
{
    public function index()
    {
        $locations = Location::where('user_id', Auth::id())->get();
        return response()->json($locations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'address'     => 'required|string|max:500',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'radius'      => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        $location = Location::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'address'     => $request->address,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'radius'      => $request->radius,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Tạo địa điểm thành công', 'location' => $location], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'address'     => 'sometimes|required|string|max:500',
            'latitude'    => 'sometimes|required|numeric|between:-90,90',
            'longitude'   => 'sometimes|required|numeric|between:-180,180',
            'radius'      => 'sometimes|required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        $location = Location::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $location->update($request->only(['name', 'address', 'latitude', 'longitude', 'radius', 'description']));

        return response()->json(['message' => 'Cập nhật thành công', 'location' => $location]);
    }

    public function destroy($id)
    {
        $location = Location::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $location->delete();
        return response()->json(['message' => 'Xóa địa điểm thành công']);
    }

    public function toggle(Request $request, $id)
    {
        $location = Location::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $location->is_active = !$location->is_active;
        $location->save();
        return response()->json(['message' => 'Cập nhật trạng thái thành công', 'location' => $location]);
    }
}
