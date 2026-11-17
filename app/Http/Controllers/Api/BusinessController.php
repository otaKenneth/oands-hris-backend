<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $businesses = $user->businesses;

        if (!$businesses) {
            return response()->json(['message' => 'Businesses not found.'], 404);
        }

        return $this->sendResponse($businesses, "Businesses retrieved successfully.");
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

        $business = $user->businesses()->create($validatedData);

        return $this->sendResponse($business, "Business created successfully.");
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
