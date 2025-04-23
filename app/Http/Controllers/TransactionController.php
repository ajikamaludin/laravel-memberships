<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Default\Setting;
use App\Models\Journal;
use App\Models\Membership;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Response;

class TransactionController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Transaction::query()->with(['member', 'account']);

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->whereHas('member', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->q}%");
                });
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('transaction/index', [
            'data' => $query->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return inertia('transaction/form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'account_id' => 'required|exists:accounts,id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.bundle_id' => 'nullable|exists:bundles,id',
        ]);

        DB::beginTransaction();
        $transaction = Transaction::create([
            'member_id' => $request->member_id,
            'account_id' => $request->account_id,
            'transaction_date' => $request->transaction_date,
            'amount' => $request->amount,
            'discount' => $request->discount,
        ]);

        foreach ($request->items as $item) {
            $transaction->items()->create([
                'name' => $item['name'],
                'price' => $item['price'],
                'bundle_id' => $item['bundle_id'],
            ]);

            if ($item['bundle_id'] != null) {
                $bundle = Bundle::find($item['bundle_id']);

                Membership::create([
                    'transaction_id' => $transaction->id,
                    'member_id' => $request->member_id,
                    'bundle_id' => $bundle->id,
                    'session_quote' => $bundle->session_quote,
                    'session_quote_used' => 0,
                    'active_period_days' => $bundle->active_period_days,
                    'expired_at' => Carbon::parse($request->transaction_date)->addDays($bundle->active_period_days),
                ]);
            }
        }

        Journal::create([
            'account_id' => $request->account_id,
            'type' => Journal::TYPE_IN,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'description' => 'Pendapatan Transaksi Membership',
            'model' => Transaction::class,
            'model_id' => $transaction->id,
        ]);
        DB::commit();

        return redirect()->route('transactions.show', $transaction)
            ->with('message', ['type' => 'success', 'message' => 'Item has been created']);
    }

    public function show(Transaction $transaction): Response
    {
        return inertia('transaction/show', [
            'transaction' => $transaction->load(['items.bundle', 'member', 'account']),
        ]);
    }

    public function edit(Transaction $transaction): Response
    {
        return inertia('transaction/form', [
            'transaction' => $transaction->load(['items.bundle', 'member', 'account']),
        ]);
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'account_id' => 'required|exists:accounts,id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.bundle_id' => 'nullable|exists:bundles,id',
        ]);

        DB::beginTransaction();
        // revert membership
        Membership::where('transaction_id', $transaction->id)->delete();

        // revert item 
        $transaction->items()->delete();

        // revert journal
        Journal::where([
            ['model', '=', Transaction::class],
            ['model_id', '=', $transaction->id]
        ])->delete();

        $transaction->update([
            'member_id' => $request->member_id,
            'account_id' => $request->account_id,
            'transaction_date' => $request->transaction_date,
            'amount' => $request->amount,
            'discount' => $request->discount,
        ]);

        foreach ($request->items as $item) {
            $transaction->items()->create([
                'name' => $item['name'],
                'price' => $item['price'],
                'bundle_id' => $item['bundle_id'],
            ]);

            if ($item['bundle_id'] != null) {
                $bundle = Bundle::find($item['bundle_id']);

                Membership::create([
                    'transaction_id' => $transaction->id,
                    'member_id' => $request->member_id,
                    'bundle_id' => $bundle->id,
                    'session_quote' => $bundle->session_quote,
                    'session_quote_used' => 0,
                    'active_period_days' => $bundle->active_period_days,
                    'expired_at' => Carbon::parse($request->transaction_date)->addDays($bundle->active_period_days),
                ]);
            }
        }

        Journal::create([
            'account_id' => $request->account_id,
            'type' => Journal::TYPE_IN,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'description' => 'Pendapatan Transaksi Membership',
            'model' => Transaction::class,
            'model_id' => $transaction->id,
        ]);
        DB::commit();

        return redirect()->route('transactions.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been updated']);
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        DB::beginTransaction();
        // revert membership
        Membership::where('transaction_id', $transaction->id)->delete();

        // revert item 
        $transaction->items()->delete();

        // revert journal
        Journal::where([
            ['model', '=', Transaction::class],
            ['model_id', '=', $transaction->id]
        ])->delete();

        $transaction->delete();
        DB::commit();

        return redirect()->route('transactions.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been deleted']);
    }

    public function print(Transaction $transaction)
    {
        $package = $transaction->items()->where('bundle_id', '!=', null)->first();
        $join_fee = $transaction->items()->where('bundle_id', null)->first();

        return view('prints.invoices', [
            'member' => $transaction->member,
            'transaction' => $transaction->load(['member', 'account']),
            'package' => $package?->bundle->name ?? ' - ',
            'package_price' => formatIDR($package?->price ?? ''),
            'join_fee' => formatIDR($join_fee?->price ?? ''),
            'setting' => new Setting(),
        ]);
    }
}
