<?php

namespace App\Http\Controllers;

use App\Models\Default\Setting;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            'address' => 'nullable|string',
        ]);

        $member = Member::create([
            'name' => $request->name,
            'member_category_id' => $request->member_category_id,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'photo' => $request->photo,
            'description' => $request->description,
            'address' => $request->address,
        ]);

        session()->flash('data', ['member' => $member->load('category')]);
        session()->flash('message', ['type' => 'success', 'message' => 'Item has been created']);
    }

    public function show(Member $member): Response
    {
        return inertia('member/show', [
            'member' => $member->load(['memberships.bundle', 'memberships.transaction', 'category']),
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
            'address' => 'nullable|string',
        ]);

        $member->fill([
            'name' => $request->name,
            'member_category_id' => $request->member_category_id,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'photo' => $request->photo,
            'description' => $request->description,
            'address' => $request->address,
        ]);

        $member->save();

        return redirect()->route('members.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been updated']);
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has been deleted']);
    }

    public function print(Member $member)
    {
        return view('prints.member-card', [
            'member' => $member,
            'setting' => new Setting(),
            'qr_code' => QrCode::size(80)->generate($member->code),
        ]);
    }
}
