<?php

namespace App\Http\Controllers;

use App\Models\MemberCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class MemberCategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $query = MemberCategory::query();

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('member-category/index', [
            'data' => $query->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'join_fee' => 'nullable|numeric',
        ]);

        MemberCategory::create([
            'name' => $request->name,
            'join_fee' => $request->join_fee,
        ]);

        session()->flash('message', ['type' => 'success', 'message' => 'Item has been created']);
    }

    public function update(Request $request, MemberCategory $memberCategory): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'join_fee' => 'nullable|numeric',
        ]);

        $memberCategory->fill([
            'name' => $request->name,
            'join_fee' => $request->join_fee,
        ]);

        $memberCategory->save();

        return redirect()->route('member-categories.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been updated']);
    }

    public function destroy(MemberCategory $memberCategory): RedirectResponse
    {
        $memberCategory->delete();

        return redirect()->route('member-categories.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been deleted']);
    }
}
