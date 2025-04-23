<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class JournalController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Journal::query()
            ->with(['account']);

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', "%{$request->q}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('journal/index', [
            'data' => $query->paginate(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'description' => 'nullable|string',
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:in,out',
            'amount' => 'required|numeric',
            'transaction_date' => 'required|date',
        ]);

        Journal::create([
            'description' => $request->description,
            'account_id' => $request->account_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
        ]);

        return redirect()->route('journals.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been created']);
    }

    public function update(Request $request, Journal $journal): RedirectResponse
    {
        $request->validate([
            'description' => 'nullable|string',
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:in,out',
            'amount' => 'required|numeric',
            'transaction_date' => 'required|date',
        ]);

        $journal->fill([
            'description' => $request->description,
            'account_id' => $request->account_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
        ]);

        $journal->save();

        return redirect()->route('journals.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been updated']);
    }

    public function destroy(Journal $journal): RedirectResponse
    {
        $journal->delete();

        return redirect()->route('journals.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been deleted']);
    }
}
