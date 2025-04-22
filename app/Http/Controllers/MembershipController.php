<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $query = Membership::query()
            ->with(['member', 'bundle', 'transaction']);

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('member', function ($q) use ($request) {
                    $q->whereAny(['name', 'code'], 'like', "%{$request->q}%");
                })
                    ->orWhereHas('bundle', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->q}%");
                    });
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('membership/index', [
            'data' => $query->paginate(),
        ]);
    }
}
