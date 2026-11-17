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
}
