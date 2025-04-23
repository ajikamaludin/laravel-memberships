<?php

namespace App\Http\Controllers;

use App\Models\TrainingTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class TrainingTimeController extends Controller
{
    public function index(Request $request): Response
    {
        $query = TrainingTime::query();

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('training-time/index', [
            'data' => $query->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        TrainingTime::create([
            'name' => $request->name
        ]);

        return redirect()->route('training-times.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been created']);
    }

    public function update(Request $request, TrainingTime $trainingTime): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $trainingTime->fill([
            'name' => $request->name,
        ]);

        $trainingTime->save();

        return redirect()->route('training-times.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been updated']);
    }

    public function destroy(TrainingTime $trainingTime): RedirectResponse
    {
        $trainingTime->delete();

        return redirect()->route('training-times.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been deleted']);
    }
}
