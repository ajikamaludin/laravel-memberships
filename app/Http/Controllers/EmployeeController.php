<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class EmployeeController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Employee::query();

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('employee/index', [
            'data' => $query->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string',
            'basic_salary_per_session' => 'required|numeric',
        ]);

        Employee::create([
            'name' => $request->name,
            'position' => $request->position,
            'basic_salary_per_session' => $request->basic_salary_per_session,
        ]);

        return redirect()->route('employees.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been created']);
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string',
            'basic_salary_per_session' => 'required|numeric',
        ]);

        $employee->fill([
            'name' => $request->name,
            'position' => $request->position,
            'basic_salary_per_session' => $request->basic_salary_per_session,
        ]);

        $employee->save();

        return redirect()->route('employees.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been updated']);
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been deleted']);
    }
}
