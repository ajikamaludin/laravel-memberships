<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class MemberController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Member::query()->with('category');

        if ($request->q) {
            // multi columns search 
            $query->where(function ($q) use ($request) {
                $q->whereAny(['name', 'code', 'description', 'phone'], 'like', "%{$request->q}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return inertia('member/index', [
            'data' => $query->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'member_category_id' => 'required|exists:member_categories,id',
            'gender' => 'required|string',
            'phone' => 'required|string|unique:members,phone',
            'photo' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $member = Member::create([
            'name' => $request->name,
            'member_category_id' => $request->member_category_id,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'photo' => $request->photo,
            'description' => $request->description,
        ]);

        session()->flash('data', ['member' => $member]);
        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed created']);
    }

    public function show(Member $member): Response
    {
        // TODO
        return inertia('member/show', [
            'data' => $member,
        ]);
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'member_category_id' => 'required|exists:member_categories,id',
            'gender' => 'required|string',
            'phone' => 'required|string|unique:members,phone,' . $member->id,
            'photo' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $member->fill([
            'name' => $request->name,
            'member_category_id' => $request->member_category_id,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'photo' => $request->photo,
            'description' => $request->description,
        ]);

        $member->save();

        return redirect()->route('members.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }

    public function print(Member $member)
    {
        // TODO: implement print card member
    }
}
