<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Employees;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'email',
        'phone'
    ];

    public function index(Request $request, Business $business)
    {
        $perPage = max(1, min((int) $request->input('itemsPerPage', 10), 100));
        
        if (!$business) {
            return response()->json(['message' => 'User does not belong to any business.'], 404);
        }

        $employees = Employees::where('business_id', $business->id)
            ->when($request->has('search'), function ($query) use ($request) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            })
            ->orderBy($request->get('sortBy', 'created_at'), $request->get('sortOrder', 'desc'));
        
        $response = $employees->paginate($perPage)->withQueryString();

        return $this->sendResponse($response, 'Employees retrieved successfully.');
    }
}
