<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class AccountController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Account::query();

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('account/index', [
            'data' => $query->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance_start' => 'nullable|numeric',
        ]);

        Account::create([
            'name' => $request->name,
            'balance_start' => $request->balance_start,
            'balance_amount' => $request->balance_start,
        ]);

        return redirect()->route('accounts.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed created']);
    }

    public function update(Request $request, Account $account): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance_start' => 'nullable|numeric',
        ]);

        $account->fill([
            'name' => $request->name,
            'balance_start' => $request->balance_start,
            'balance_amount' => $request->balance_amount - $account->balance_start + $request->balance_start,
        ]);

        $account->save();

        return redirect()->route('accounts.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }

    public function destroy(Account $account): RedirectResponse
    {
        $account->delete();

        return redirect()->route('accounts.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
