<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftsController extends Controller
{
    public function index(Request $request, Business $business)
    {
        $user_id = $request->user()->id;
        $perPage = max(1, min((int) $request->input('itemsPerPage', 10), 100));
        
        if (!$business) {
            return $this->sendError("This function does not belong to this business.", [], 404);
        }

        $shifts = Shift::where('business_id', $business->id)
            ->orWhere(function ($query) use ($user_id) {
                return $query->where('user_id', $user_id)
                ->where('is_general_shift', true);
            })
            ->orderBy($request->input('sortBy', 'created_at'), $request->input('sortOrder', 'desc'));

        $response = $shifts->paginate($perPage)->withQueryString();

        return $this->sendResponse($response, "");
    }

    public function store(Request $request, Business $business) {
        $validatedData = $request->validate([
            'code' => 'required|string',
            'description' => 'nullable|string',
            'day_of_week' => 'required',
            'clock_in' => 'required',
            'clock_out' => 'required',
            'break_in' => 'nullable',
            'break_out' => 'nullable',
            'allow_break' => 'required',
            'is_general_shift' => 'required'
        ]);

        $user_id = $request->user()->id;

        $arr_insert_shift = [
            'user_id' => $user_id,
            ...$validatedData
        ];
        foreach ($request->input('day_of_week') as $day_of_week => $string_value) {
            $arr_insert_shift['day_of_week'] = $day_of_week;
            $shift = $business->shifts()->create($arr_insert_shift);
        }

        $this->sendResponse($shift, "Successful create shift.");
    }

    public function getShiftsForSelection(Request $request, Business $business)
    {
        $user_id = $request->user()->id;

        $shifts = Shift::select(['code'])
            ->where('business_id', $business->id)
            ->orWhere(function ($query) use ($user_id) {
                return $query->where('user_id', $user_id)
                ->where('is_general_shift', true);
            })
            ->groupBy('code')
            ->get();

        return $this->sendResponse($shifts, "Success");
    }

    public function showShiftDetail(Request $request) 
    {
        $code = $request->input('code');
        $user_id = $request->user()->id;
        
        $shifts = Shift::where('user_id', $user_id)
            ->where('code', $code)
            ->get();

        return $this->sendResponse($shifts, "Success");
    }
}
