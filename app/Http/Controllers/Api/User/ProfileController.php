<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error', $validator->errors(), 403);
        }

        $user = $request->user();

        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return $this->sendResponse('Successfully', 'Password has been updated');
        } else {
            return $this->sendError('Error', 'Old password does not matched', 400);
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'birthday' => 'nullable|date',
            'description' => 'nullable',
            'interests' => 'nullable',
            'gender' => 'nullable',
            'school' => 'nullable',
            'avatar' => 'nullable|image|mimes:jpg,png,bmp'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error', $validator->errors(), 403);
        }

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $old_path = public_path() . '/uploads/avatar/' . $user->avatar;
                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }
            $avatar = 'avatar-' . time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('/uploads/avatar'), $avatar);
        } else {
            $avatar = $user->avatar;
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'description' => $request->description,
            'interests' => $request->interests,
            'gender' => $request->gender,
            'school' => $request->school,
            'avatar' => $avatar
        ]);

        return $this->sendResponse('Successfully', 'Profile has been updated');
    }
}
