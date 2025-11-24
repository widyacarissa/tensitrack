<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'user' => User::where('id', auth()->user()->id)->with('profile')->first(),
        ];

        return response()->json($data);
    }

    public function updateUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'gender' => 'nullable|in:male,female,other',
            'age' => 'nullable|integer|min:1|max:120',
            'height' => 'nullable|numeric|min:50|max:250',
            'weight' => 'nullable|numeric|min:20|max:250',
        ]);

        try {
            $user = User::find(auth()->user()->id);

            // Update user data
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->save();

            // Update or create user profile
            if (! $user->profile) {
                $profile = new UserProfile;
                $profile->user_id = $user->id;
            } else {
                $profile = $user->profile;
            }

            $profile->gender = $data['gender'] ?? null;
            $profile->age = $data['age'] ?? null;
            $profile->weight = $data['weight'] ?? null;
            $profile->height = $data['height'] ?? null;

            // Calculate BMI automatically
            if ($data['weight'] && $data['height']) {
                $profile->calculateBMI();
            }

            $profile->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                'message' => 'Profile failed to update',
                'error' => $th->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Edit Profil Berhasil',
        ]);
    }
}
