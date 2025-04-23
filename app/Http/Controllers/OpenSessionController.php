<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Member;
use App\Models\Membership;
use App\Models\OpenSession;
use App\Models\Subject;
use App\Models\SubjectSessionItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Response;

class OpenSessionController extends Controller
{
    public function index(Request $request): Response
    {
        $query = SubjectSessionItem::query()
            ->where('flag', 1)
            ->with(['member', 'subject', 'membership']);

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->whereHas('member', function ($q) use ($request) {
                    $q->whereAny(['code', 'name'], 'like', '%' . $request->q . '%');
                });
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('open-session/index', [
            'data' => $query->paginate(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $member = Member::whereAny(['code', 'phone'], '=', $request->code)->first();

        if (!$member) {
            $message = 'Member tidak ditemukan';
            return redirect()->route('open-sessions.index')
                ->with('message', ['type' => 'error', 'message' => $message]);
        }

        $subject = Subject::where('flag', 1)->first(); //gym

        $membership = Membership::query()
            ->where('member_id', $member->id)
            ->where('expired_at', '>=', now())
            ->whereRaw('session_quote_used < session_quote')
            ->whereHas('bundle', function ($q) use ($subject) {
                $q->whereHas('items', function ($q) use ($subject) {
                    $q->where('subject_id', $subject->id);
                });
            })
            ->orderBy('expired_at', 'asc')
            ->first();

        if ($membership == null) {
            $message = 'Member tidak memiliki membership untuk kelas gym';

            return redirect()->route('open-sessions.index')
                ->with('message', ['type' => 'error', 'message' => $message]);
        }

        DB::beginTransaction();
        $membership->increment('session_quote_used');

        SubjectSessionItem::create([
            'member_id' => $member->id,
            'subject_id' => $subject->id,
            'membership_id' => $membership->id,
            'session_date' => now()->format('Y-m-d'),
            'flag' => 1
        ]);
        DB::commit();

        return redirect()->route('open-sessions.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been created']);
    }

    public function destroy(SubjectSessionItem $openSession): RedirectResponse
    {
        $openSession->membership()->decrement('session_quote_used');
        $openSession->delete();

        return redirect()->route('open-sessions.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been deleted']);
    }
}
