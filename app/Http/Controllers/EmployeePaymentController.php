<?php

namespace App\Http\Controllers;

use App\Models\EmployeePayment;
use App\Models\Journal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Response;

class EmployeePaymentController extends Controller
{
    public function index(Request $request): Response
    {
        $query = EmployeePayment::query()
            ->with(['employee']);

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', "%{$request->q}%")
                    ->orWhereHas('employee', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->q}%");
                    });
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('employee-payment/index', [
            'data' => $query->paginate(),
        ]);
    }

    public function create(): Response
    {
        return inertia('employee-payment/form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'employee_id' => 'required|exists:employees,id',
            'payment_date' => 'required|date',
            'payment_date_end' => 'required|date',
            'basic_salary_per_session' => 'required|numeric',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'items' => 'required|array',
            'items.*.subject_session_id' => 'required|exists:subject_sessions,id',
            'items.*.subject_id' => 'required|exists:subjects,id',
            'items.*.person_amount' => 'required|numeric',
            'items.*.employee_fee_per_person' => 'required|numeric',
        ]);

        DB::beginTransaction();
        $payment = EmployeePayment::create([
            'account_id' => $request->account_id,
            'employee_id' => $request->employee_id,
            'payment_date' => $request->payment_date,
            'payment_date_end' => $request->payment_date_end,
            'basic_salary_per_session' => $request->basic_salary_per_session,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        foreach ($request->items as $item) {
            $payment->items()->create([
                'subject_session_id' => $item['subject_session_id'],
                'subject_id' => $item['subject_id'],
                'person_amount' => $item['person_amount'],
                'employee_fee_per_person' => $item['employee_fee_per_person'],
            ]);
        }

        Journal::create([
            'account_id' => $request->account_id,
            'type' => Journal::TYPE_OUT,
            'amount' => $request->amount,
            'transaction_date' => $request->payment_date,
            'description' => 'Pembayaran Gaji : ' . $payment->employee->name,
            'model' => EmployeePayment::class,
            'model_id' => $payment->id,
        ]);

        DB::commit();

        return redirect()->route('employee-payments.show', $payment)
            ->with('message', ['type' => 'success', 'message' => 'Item has been created']);
    }

    public function show(EmployeePayment $employeePayment): Response
    {
        return inertia('employee-payment/show', [
            'employeePayment' => $employeePayment->load([
                'items.subject',
                'items.subjectSession.trainingTime',
                'items.subjectSession.employee',
                'employee',
                'account'
            ]),
        ]);
    }

    public function edit(EmployeePayment $employeePayment): Response
    {
        return inertia('employee-payment/form', [
            'employeePayment' => $employeePayment->load([
                'items.subject',
                'items.subjectSession.trainingTime',
                'items.subjectSession.employee',
                'employee',
                'account'
            ]),
        ]);
    }

    public function update(Request $request, EmployeePayment $employeePayment): RedirectResponse
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'employee_id' => 'required|exists:employees,id',
            'payment_date' => 'required|date',
            'payment_date_end' => 'required|date',
            'basic_salary_per_session' => 'required|numeric',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'items' => 'required|array',
            'items.*.subject_session_id' => 'required|exists:subject_sessions,id',
            'items.*.subject_id' => 'required|exists:subjects,id',
            'items.*.person_amount' => 'required|numeric',
            'items.*.employee_fee_per_person' => 'required|numeric',
        ]);

        DB::beginTransaction();
        // revert 
        Journal::where([
            ['model', '=', EmployeePayment::class],
            ['model_id', '=', $employeePayment->id],
        ])->delete();

        $employeePayment->items()->delete();

        $employeePayment->update([
            'account_id' => $request->account_id,
            'employee_id' => $request->employee_id,
            'payment_date' => $request->payment_date,
            'payment_date_end' => $request->payment_date_end,
            'basic_salary_per_session' => $request->basic_salary_per_session,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        foreach ($request->items as $item) {
            $employeePayment->items()->create([
                'subject_session_id' => $item['subject_session_id'],
                'subject_id' => $item['subject_id'],
                'person_amount' => $item['person_amount'],
                'employee_fee_per_person' => $item['employee_fee_per_person'],
            ]);
        }

        Journal::create([
            'account_id' => $request->account_id,
            'type' => Journal::TYPE_OUT,
            'amount' => $request->amount,
            'transaction_date' => $request->payment_date,
            'description' => 'Pembayaran Gaji : ' . $employeePayment->employee->name,
            'model' => EmployeePayment::class,
            'model_id' => $employeePayment->id,
        ]);

        DB::commit();

        return redirect()->route('employee-payments.show', $employeePayment)
            ->with('message', ['type' => 'success', 'message' => 'Item has been updated']);
    }

    public function destroy(EmployeePayment $employeePayment): RedirectResponse
    {
        DB::beginTransaction();
        Journal::where([
            ['model', '=', EmployeePayment::class],
            ['model_id', '=', $employeePayment->id],
        ])->delete();

        $employeePayment->items()->delete();

        $employeePayment->delete();
        DB::commit();

        return redirect()->route('employee-payments.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been deleted']);
    }

    public function print(EmployeePayment $employeePayment)
    {
        return view('prints.payment', [
            'payment' => $employeePayment->load([
                'items.subject',
                'items.subjectSession.trainingTime',
                'items.subjectSession.employee',
                'employee',
                'account'
            ]),
        ]);
    }
}
