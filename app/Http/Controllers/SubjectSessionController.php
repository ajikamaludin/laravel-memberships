<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Subject;
use App\Models\SubjectSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Response;

class SubjectSessionController extends Controller
{
    public function index(Request $request): Response
    {
        $query = SubjectSession::query()
            ->with(['subject', 'employee', 'trainingTime'])
            ->withCount('items');

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->whereHas('employee', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->q}%");
                })->orWhereHas('subject', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->q}%");
                });
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('subject-session/index', [
            'data' => $query->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return inertia('subject-session/form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'employee_id' => 'required|exists:employees,id',
            'training_time_id' => 'required|exists:training_times,id',
            'session_date' => 'required|date',
            'items' => 'nullable|array',
            'items.*.member_id' => 'required|exists:members,id',
        ]);

        DB::beginTransaction();
        $sesi = SubjectSession::create([
            'subject_id' => $request->subject_id,
            'employee_id' => $request->employee_id,
            'training_time_id' => $request->training_time_id,
            'session_date' => $request->session_date,
        ]);

        $subject = Subject::find($request->subject_id);
        foreach (collect($request->items) as $item) {
            $membership = Membership::query()
                ->where('member_id', $item['member_id'])
                ->where('expired_at', '>=', now())
                ->whereRaw('session_quote_used < session_quote')
                ->whereHas('bundle', function ($q) use ($subject) {
                    $q->whereHas('items', function ($q) use ($subject) {
                        $q->where('subject_id', $subject->id);
                    });
                })
                ->orderBy('expired_at', 'asc')
                ->first();

            $membership->increment('session_quote_used');

            $sesi->items()->create([
                'membership_id' => $membership->id,
                'member_id' => $item['member_id'],
            ]);
        }
        DB::commit();

        return redirect()->route('subject-sessions.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been created']);
    }

    public function edit(SubjectSession $subjectSession): Response
    {
        return inertia('subject-session/form', [
            'subjectSession' => $subjectSession->load(['items.member.category', 'items.membership', 'subject', 'employee', 'trainingTime']),
        ]);
    }

    public function update(Request $request, SubjectSession $subjectSession): RedirectResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'employee_id' => 'required|exists:employees,id',
            'training_time_id' => 'required|exists:training_times,id',
            'session_date' => 'required|date',
            'items' => 'nullable|array',
            'items.*.member_id' => 'required|exists:members,id',
        ]);

        DB::beginTransaction();
        // revert
        foreach ($subjectSession->items as $item) {
            $item->membership->decrement('session_quote_used');
        }
        $subjectSession->items()->delete();

        $subjectSession->fill([
            'subject_id' => $request->subject_id,
            'employee_id' => $request->employee_id,
            'training_time_id' => $request->training_time_id,
            'session_date' => $request->session_date,
        ]);

        $subjectSession->save();

        $subject = Subject::find($request->subject_id);
        foreach (collect($request->items) as $item) {
            $membership = Membership::query()
                ->where('member_id', $item['member_id'])
                ->where('expired_at', '>=', now())
                ->whereRaw('session_quote_used < session_quote')
                ->whereHas('bundle', function ($q) use ($subject) {
                    $q->whereHas('items', function ($q) use ($subject) {
                        $q->where('subject_id', $subject->id);
                    });
                })
                ->orderBy('expired_at', 'asc')
                ->first();

            $membership->increment('session_quote_used');

            $subjectSession->items()->create([
                'membership_id' => $membership->id,
                'member_id' => $item['member_id'],
            ]);
        }
        DB::commit();

        return redirect()->route('subject-sessions.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been updated']);
    }

    public function destroy(SubjectSession $subjectSession): RedirectResponse
    {
        DB::beginTransaction();
        // revert
        foreach ($subjectSession->items as $item) {
            $item->membership->decrement('session_quote_used');
        }
        $subjectSession->items()->delete();
        $subjectSession->delete();
        DB::commit();

        return redirect()->route('subject-sessions.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been deleted']);
    }
}
