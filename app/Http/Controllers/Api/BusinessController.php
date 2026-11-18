<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->input('itemsPerPage', 10), 100));
        $user = $request->user();
        $businesses = Business::with(['user' => function ($query) {
            return $query->select('id', 'email');
        }])->where('user_id', $user->id);

        $response = $businesses->paginate($perPage)->withQueryString();

        return $this->sendResponse($response, "Businesses retrieved successfully.");
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $business = $user->business()->select('name');
        
        if (!$business) {            
            return $this->sendError("Businesses not found", 404);
        }

        return $this->sendResponse($busines, "Businesses retrieved successfully.");
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:businesses,email',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            // Add other fields as necessary
        ]);

        try {
            DB::beginTransaction();
            $business = $user->businesses()->create($validatedData);
            $business->userRole()->create([
                'user_id' => $user->id,
                'role' => 'owner'
            ]);
            DB::commit();
            return $this->sendResponse($business, "Business created successfully.");
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($e->getMessage(), [], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $business = $user->businesses()->find($id);

        if (!$business) {
            return response()->json(['message' => 'Business not found.'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:businesses,email,' . $business->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            // Add other fields as necessary
        ]);

        $business->update($validatedData);

        return $this->sendResponse($business, "Business updated successfully.");
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $business = $user->businesses()->find($id);

        if (!$business) {
            return response()->json(['message' => 'Business not found.'], 404);
        }

        $business->delete();

        return $this->sendResponse(null, "Business deleted successfully.");
    }
}
