<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FamilyMemberController extends Controller
{
    /**
     * Display a listing of the family members.
     */
    public function index()
    {
        $familyMembers = auth()->user()->familyMembers()->latest()->get();

        return view('family-members.index', compact('familyMembers'));
    }

    /**
     * Store a newly created family member in storage.
     */
    public function store(Request $request)
    {
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

        auth()->user()->familyMembers()->create($request->only([
            'first_name',
            'last_name',
            'personal_number',
            'relationship'
        ]));

        return redirect()->route('family-members.index')
            ->with('success', 'ოჯახის წევრი წარმატებით დაემატა');
    }

    /**
     * Update the specified family member in storage.
     */
    public function update(Request $request, FamilyMember $familyMember)
    {
        // Ensure the family member belongs to the authenticated user
        if ($familyMember->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'personal_number' => 'required|string|size:11|unique:family_members,personal_number,' . $familyMember->id,
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
            'relationship'
        ]));

        return redirect()->route('family-members.index')
            ->with('success', 'ოჯახის წევრის ინფორმაცია განახლდა');
    }

    /**
     * Remove the specified family member from storage.
     */
    public function destroy(FamilyMember $familyMember)
    {
        // Ensure the family member belongs to the authenticated user
        if ($familyMember->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $familyMember->delete();

        return redirect()->route('family-members.index')
            ->with('success', 'ოჯახის წევრი წაიშალა');
    }
}

