<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use App\Services\FamilyMemberService;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FamilyMemberController extends Controller
{
    public function __construct(
        private FamilyMemberService $familyMemberService,
        private MembershipService $membership,
    ) {}

    public function index()
    {
        $user = auth()->user();
        $familyMembers = $user->familyMembers()->latest()->get();
        $activeSubscription = $this->membership->activeSubscription($user);
        $monthlyAddon = $this->familyMemberService->monthlyAddon();
        $approvedCount = $this->familyMemberService->approvedCount($user);

        return view('family-members.index', compact(
            'familyMembers',
            'activeSubscription',
            'monthlyAddon',
            'approvedCount',
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (! $this->membership->hasActiveMembership($user)) {
            return back()->with('error', 'ოჯახის წევრის დასამატებლად საჭიროა აქტიური წევრობა.');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'personal_number' => 'required|string|size:11|unique:family_members,personal_number',
            'relationship' => 'required|in:spouse,child,parent,sibling,other',
        ], [
            'first_name.required' => 'სახელის შეყვანა სავალდებულოა',
            'last_name.required' => 'გვარის შეყვანა სავალდებულოა',
            'personal_number.required' => 'პირადი ნომრის შეყვანა სავალდებულოა',
            'personal_number.size' => 'პირადი ნომერი უნდა შეიცავდეს 11 ციფრს',
            'personal_number.unique' => 'ეს პირადი ნომერი უკვე რეგისტრირებულია',
            'relationship.required' => 'კავშირის არჩევა სავალდებულოა',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $member = $user->familyMembers()->create([
            ...$request->only(['first_name', 'last_name', 'personal_number', 'relationship']),
            'status' => FamilyMember::STATUS_PENDING,
            'is_active' => false,
        ]);

        return redirect()->route('family-members.index')
            ->with('success', 'მოთხოვნა გაგზავნილია. ადმინისტრატორის დადასტურების შემდეგ თქვენს წევრობას დაემატება '.$this->familyMemberService->monthlyAddon().' ₾/თვე.');
    }

    public function update(Request $request, FamilyMember $familyMember)
    {
        if ($familyMember->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($familyMember->status === FamilyMember::STATUS_APPROVED) {
            return back()->with('error', 'დადასტურებული წევრის რედაქტირება შეუძლებელია. წაშალეთ და თავიდან დაამატეთ.');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'personal_number' => 'required|string|size:11|unique:family_members,personal_number,'.$familyMember->id,
            'relationship' => 'required|in:spouse,child,parent,sibling,other',
        ], [
            'first_name.required' => 'სახელის შეყვანა სავალდებულოა',
            'last_name.required' => 'გვარის შეყვანა სავალდებულოა',
            'personal_number.required' => 'პირადი ნომრის შეყვანა სავალდებულოა',
            'personal_number.size' => 'პირადი ნომერი უნდა შეიცავდეს 11 ციფრს',
            'personal_number.unique' => 'ეს პირადი ნომერი უკვე რეგისტრირებულია',
            'relationship.required' => 'კავშირის არჩევა სავალდებულოა',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $familyMember->update($request->only([
            'first_name',
            'last_name',
            'personal_number',
            'relationship',
        ]));

        if ($familyMember->status === FamilyMember::STATUS_REJECTED) {
            $this->familyMemberService->submit($familyMember);
        }

        return redirect()->route('family-members.index')
            ->with('success', 'ოჯახის წევრის ინფორმაცია განახლდა');
    }

    public function destroy(FamilyMember $familyMember)
    {
        if ($familyMember->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->familyMemberService->remove($familyMember);

        return redirect()->route('family-members.index')
            ->with('success', 'ოჯახის წევრი წაიშალა');
    }
}
