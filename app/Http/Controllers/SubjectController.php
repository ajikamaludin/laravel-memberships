<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class SubjectController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Subject::query();

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('subject/index', [
            'data' => $query->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employee_fee_per_person' => 'required|numeric',
        ]);

        Subject::create([
            'name' => $request->name,
            'description' => $request->description,
            'employee_fee_per_person' => $request->employee_fee_per_person,
        ]);

        return redirect()->route('subjects.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed created']);
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employee_fee_per_person' => 'required|numeric',
        ]);

        $subject->fill([
            'name' => $request->name,
            'description' => $request->description,
            'employee_fee_per_person' => $request->employee_fee_per_person,
        ]);

        $subject->save();

        return redirect()->route('subjects.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
