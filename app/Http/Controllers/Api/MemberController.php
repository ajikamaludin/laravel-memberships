<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query()->with(['category']);

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->whereAny(['name', 'code', 'description', 'phone'], 'like', "%{$request->q}%");
            });
        }

        if ($request->subject_id) {
            // will filter only member has memberships of package subject, not expired and has quote
            $query->whereHas('memberships', function ($q) use ($request) {
                $q->whereHas('bundle.items', function ($q) use ($request) {
                    $q->where('subject_id', $request->subject_id);
                })
                    ->where('expired_at', '>=', now())
                    ->whereRaw('session_quote_used < session_quote');
            });

            $query->with([
                'memberships' => function ($q) use ($request) {
                    $q->whereHas('bundle.items', function ($q) use ($request) {
                        $q->where('subject_id', $request->subject_id);
                    })
                        ->where('expired_at', '>=', now())
                        ->whereRaw('session_quote_used < session_quote')
                        ->orderBy('created_at', 'asc');
                },
                // 'memberships.bundle.items'
            ]);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate();
    }
}
