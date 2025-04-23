<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportAccountController extends Controller
{
    public function balance_sheet(Request $request)
    {
        $accounts = Account::all();

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
        }

        // [
        //     'income' => [
        //         'A' => 10
        //         'B' => 10,
        //     ],
        //     'expense' => [
        //         'C' => 10
        //     ]
        //     'balance_sheet' => 10,
        // ];
        $data = [
            ['name' => 'Pemasukan', 'amount' => '', 'font' => 'font-bold'],
        ];
        $income = 0;
        $expense = 0;
        foreach ($accounts as $account) {
            $amount = $account->items()
                ->where('transaction_date', '>=', $startDate)
                ->where('transaction_date', '<=', $endDate)
                ->where('type', Journal::TYPE_IN)
                ->sum('amount');

            $name = 'Pemasukan ' . $account->name;
            $income += $amount;
            $data[] = ['name' => $name, 'amount' => $amount, 'font' => 'font-light'];
        }

        $data[] = ['name' => 'Pengeluaran', 'font' => 'font-bold'];
        foreach ($accounts as $account) {

            $amount = $account->items()
                ->where('transaction_date', '>=', $startDate)
                ->where('transaction_date', '<=', $endDate)
                ->where('type', Journal::TYPE_OUT)
                ->sum('amount');

            $name = 'Pengeluaran ' . $account->name;
            $expense += $amount;
            $data[] = ['name' => $name, 'amount' => $amount, 'font' => 'font-light'];
        }

        $data[] = ['name' => 'Laba/Rugi', 'amount' => $income - $expense, 'font' => 'font-bold'];

        return inertia('report/balance_sheet', [
            'data' => $data,
            '_start_date' => $startDate->format('Y-m-d'),
            '_end_date' => $endDate->format('Y-m-d'),
        ]);
    }
}
