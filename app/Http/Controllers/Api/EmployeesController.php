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

    public function store(Request $request, Business $business)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $business->employees()->create($validatedData);

        return $this->sendResponse($employee, 'Employee created successfully.');
    }

    public function update(Request $request, Business $business, Employees $employee)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $employee->update($validatedData);

        return $this->sendResponse($employee, 'Employee updated successfully.');
    }

    public function destroy(Business $business, Employees $employee)
    {
        $employee->delete();

        return $this->sendResponse(null, 'Employee deleted successfully.');
    }
}
