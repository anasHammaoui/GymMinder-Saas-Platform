<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::all();
        return response()->json(["message"=> "Success", "members" => $members],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,png',
            'mobile_number' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'plan' => 'required|in:monthly,yearly',
        ]);
       } catch (ValidationException $e){
        return response() -> json(["errors"=> $e -> errors()],422);
       }
       if($request -> hasFile('profile_picture')){
        $image = $request -> file('profile_picture');
        $imageName = time() ."_". uniqid().".".$image -> getClientOriginalExtension();
        $path = $image -> storeAs('uploads/profile_pics', $imageName,"public");
       }
        $member = Member::create(
            [
                'user_id' => $validated['user_id'],
                'name' => $validated['name'],
                'profile_picture' => $request->hasFile('profile_picture') ? $path : null,
                'mobile_number' => $validated['mobile_number'],
                'email' => $validated['email'],
                'plan' => $validated['plan'],
            ]
        );
        return response()->json(["message"=> "member added with success", "member"=> $member],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = Member::findOrFail($id);
        return response()->json($member);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $member = Member::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        try {
            $validated = $request->validate([
                'user_id' => 'sometimes|exists:users,id',
                'name' => 'sometimes|string|max:255',
                'profile_picture' => 'nullable|image|mimes:jpg,png',
                'mobile_number' => 'sometimes|string|max:15',
                'email' => 'nullable|email|max:255',
                'plan' => 'sometimes|in:monthly,yearly',
            ]);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }

        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if it exists
            if ($member->profile_picture && Storage::disk('public')->exists($member->profile_picture)) {
                Storage::disk('public')->delete($member->profile_picture);
            }

            // Store the new profile picture
            $image = $request->file('profile_picture');
            $imageName = time() . "_" . uniqid() . "." . $image->getClientOriginalExtension();
            $path = $image->storeAs('uploads/profile_pics', $imageName, "public");
            $validated['profile_picture'] = $path;
        }

        $member->update([
            'user_id' => $validated['user_id'] ?? $member->user_id,
            'name' => $validated['name'] ?? $member->name,
            'profile_picture' => $validated['profile_picture'] ?? $member->profile_picture,
            'mobile_number' => $validated['mobile_number'] ?? $member->mobile_number,
            'email' => $validated['email'] ?? $member->email,
            'plan' => $validated['plan'] ?? $member->plan,
        ]);

        return response()->json(["message" => "Member updated successfully", "member" => $member], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $member = Member::findOrFail($id);
        $member->delete();
        return response()->json(['message' => 'Member deleted successfully']);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Member not found'], 404);
        }
    }
}
