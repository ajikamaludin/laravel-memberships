<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubjectSession;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SubjectSessionController extends Controller
{
    public function index(Request $request)
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

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->selected_date && $request->selected_date_end) {
            $selected_date = Carbon::parse($request->selected_date);
            $selected_date_end = Carbon::parse($request->selected_date_end);

            $query->where('session_date', '>=', $selected_date->format('Y-m-d'))
                ->where('session_date', '<=', $selected_date_end->format('Y-m-d'));
        }

        $query->orderBy('created_at', 'desc');

        if ($request->all == 'true') {
            return $query->get();
        }

        return $query->paginate();
    }
}
