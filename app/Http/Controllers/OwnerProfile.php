<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OwnerProfile extends Controller
{
    public function index(){
        $user = auth('sanctum')->user();
        return response()->json([
            "fullName" => $user->name,
            "email" => $user->email,
            "businessName" => $user->business_name,
            "country" => $user->country,
            "gender" => $user->gender,
        ], 200);
    }
    public function update(Request $request)
    {
        $user = auth('sanctum')->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'business_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('profile_pic')) {
            // Remove the old image if it exists
            if ($user->profile_pic && Storage::disk('public')->exists($user->profile_pic)) {
            Storage::disk('public')->delete($user->profile_pic);
            }

            $image = $request->file('profile_pic');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('uploads/profile_pics', $imageName, 'public');
            $user->profile_pic = $path;
        }

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->business_name = $validatedData['business_name'] ?? $user->business_name;
        $user->country = $validatedData['country'] ?? $user->country;
        $user->gender = $validatedData['gender'] ?? $user->gender;
        $user->save();

        return response()->json([
            "message" => "User information updated successfully.",
            "user" => $user
        ], 200);
    }
}
