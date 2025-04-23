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

        if ($request->selected_date) {
            $selected_date = Carbon::parse($request->selected_date);
            $query->whereMonth('session_date', $selected_date->month)
                ->whereYear('session_date', $selected_date->year);
        }

        $query->orderBy('created_at', 'desc');

        if ($request->all == 'true') {
            return $query->get();
        }

        return $query->paginate();
    }
}
