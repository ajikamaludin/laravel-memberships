<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\BundleItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Response;

class BundleController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Bundle::query();

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('bundle/index', [
            'data' => $query->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return inertia('bundle/form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'session_quote' => 'required|numeric',
            'active_period_days' => 'required|numeric',
            'price' => 'required|numeric',
            'items.*.subject_id' => 'required|exists:subjects,id',
        ]);

        DB::beginTransaction();
        $bundle = Bundle::create([
            'name' => $request->name,
            'session_quote' => $request->session_quote,
            'active_period_days' => $request->active_period_days,
            'price' => $request->price,
        ]);

        $bundle->items()->saveMany(collect($request->items)->mapInto(BundleItem::class));
        DB::commit();

        return redirect()->route('bundles.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed created']);
    }

    public function edit(Bundle $bundle): Response
    {
        return inertia('bundle/form', [
            'bundle' => $bundle->load(['items.subject']),
        ]);
    }

    public function update(Request $request, Bundle $bundle): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'session_quote' => 'required|numeric',
            'active_period_days' => 'required|numeric',
            'price' => 'required|numeric',
            'items.*.subject_id' => 'required|exists:subjects,id',
        ]);

        DB::beginTransaction();
        $bundle->items()->delete();
        $bundle->update([
            'name' => $request->name,
            'session_quote' => $request->session_quote,
            'active_period_days' => $request->active_period_days,
            'price' => $request->price,
        ]);

        $bundle->items()->saveMany(collect($request->items)->mapInto(BundleItem::class));
        DB::commit();

        return redirect()->route('bundles.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }

    public function destroy(Bundle $bundle): RedirectResponse
    {
        $bundle->delete();

        return redirect()->route('bundles.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
