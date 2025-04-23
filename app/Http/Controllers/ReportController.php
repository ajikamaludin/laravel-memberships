<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SubjectSession;
use App\Models\SubjectSessionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function member_session(Request $request)
    {
        $query = SubjectSessionItem::query()
            ->with(['subject', 'subjectSession.trainingTime', 'membership.bundle', 'member']);

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('member', function ($q) use ($request) {
                    $q->whereAny(['code', 'name'], 'like', "%{$request->q}%");
                })
                    ->orWhereHas('subject', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->q}%");
                    })
                    ->orWhereHas('membership.bundle', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->q}%");
                    });
            });
        }
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
        }

        $query->where('session_date', '>=', $startDate->format('Y-m-d'))
            ->where('session_date', '<=', $endDate->format('Y-m-d'));

        $query->orderBy('session_date', 'desc');

        return inertia('report/member_session', [
            'data' => $query->paginate(),
            '_start_date' => $startDate->format('Y-m-d'),
            '_end_date' => $endDate->format('Y-m-d'),
        ]);
    }

    public function member_accumulate_per_day(Request $request)
    {
        $query = SubjectSessionItem::query();

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
        }

        $query->where('session_date', '>=', $startDate->format('Y-m-d'))
            ->where('session_date', '<=', $endDate->format('Y-m-d'));

        $query->orderBy('session_date', 'desc');

        $query->selectRaw('session_date, count(session_date) as session_count')
            ->groupBy('session_date');

        return inertia('report/member_accumulate_per_day', [
            'data' => $query->paginate(),
            '_start_date' => $startDate->format('Y-m-d'),
            '_end_date' => $endDate->format('Y-m-d'),
        ]);
    }

    public function employee_accumulate_session(Request $request)
    {
        $year = now()->year;

        if ($request->year) {
            $year = $request->year;
        }

        $data = [];
        foreach (range(1, 12) as $month) {
            $employees = Employee::query()
                ->withCount(['subjectSessions' => function ($q) use ($year, $month) {
                    $q->whereYear('session_date', $year)
                        ->whereMonth('session_date', $month);
                }])->get();

            foreach ($employees as $employee) {
                $data[] = ['employee' => $employee, 'month' => now()->month($month)->translatedFormat('F')];
            }
        }

        return inertia('report/employee_accumulate_session', [
            'data' => $data,
            '_year' => $year,
            'years' => [
                $year - 1,
                $year,
                $year + 1
            ],
        ]);
    }

    public function employee_accumulate_fee(Request $request)
    {
        $year = now()->year;

        if ($request->year) {
            $year = $request->year;
        }

        $data = [];
        foreach (range(1, 12) as $month) {
            $employees = Employee::query()
                ->with(['subjectSessions.items', 'subjectSessions.subject'])
                ->withCount([
                    'subjectSessions' => function ($q) use ($year, $month) {
                        $q->whereYear('session_date', $year)
                            ->whereMonth('session_date', $month);
                    },
                ])

                ->get();

            foreach ($employees as $employee) {
                $fee = $employee->basic_salary_per_session * $employee->subject_sessions_count;
                $subjectSessions = $employee->subjectSessions()
                    ->whereYear('session_date', $year)
                    ->whereMonth('session_date', $month)
                    ->get();

                foreach ($subjectSessions as $subjectSession) {
                    $fee += $subjectSession->items()->count() * $subjectSession->subject->employee_fee_per_person;
                }

                $data[] = [
                    'employee' => $employee,
                    'fee' => $fee,
                    'month' => now()->month($month)->translatedFormat('F')
                ];
                $fee = 0;
            }
        }

        return inertia('report/employee_accumulate_fee', [
            'data' => $data,
            '_year' => $year,
            'years' => [
                $year - 1,
                $year,
                $year + 1
            ],
        ]);
    }

    public function member_accumulate_session(Request $request)
    {
        $query = SubjectSession::query()
            ->with(['subject', 'employee', 'trainingTime'])
            ->withCount(['items']);

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->orWhereHas('subject', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->q}%");
                })
                    ->orWhereHas('employee', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->q}%");
                    });
            });
        }

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
        }
        $query->where('session_date', '>=', $startDate->format('Y-m-d'))
            ->where('session_date', '<=', $endDate->format('Y-m-d'));

        $query->orderBy('session_date', 'desc');

        return inertia('report/member_accumulate_session', [
            'data' => $query->paginate(),
            '_start_date' => $startDate->format('Y-m-d'),
            '_end_date' => $endDate->format('Y-m-d'),
        ]);
    }

    public function employee_fee(Request $request)
    {
        $query = SubjectSession::query()
            ->with(['subject', 'employee', 'trainingTime'])
            ->withCount(['items']);

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->orWhereHas('subject', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->q}%");
                })
                    ->orWhereHas('employee', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->q}%");
                    });
            });
        }

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
        }

        $query->where('session_date', '>=', $startDate->format('Y-m-d'))
            ->where('session_date', '<=', $endDate->format('Y-m-d'));

        $query->orderBy('session_date', 'desc');

        return inertia('report/employee_fee', [
            'data' => $query->paginate(),
            '_start_date' => $startDate->format('Y-m-d'),
            '_end_date' => $endDate->format('Y-m-d'),
        ]);
    }
}
