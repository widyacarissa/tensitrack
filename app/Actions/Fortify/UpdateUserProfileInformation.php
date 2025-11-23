<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],

            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'profession' => ['required', 'string', 'max:255'],

        ])->validateWithBag('updateProfileInformation');

        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
        ])->save();

        $user->profile->address = $input['address'];
        $user->profile->city = $input['city'];
        $user->profile->province = $input['province'];
        $user->profile->profession = $input['profession'];
        $user->profile->save();
    }
}
