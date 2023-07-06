<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\GeneralJsonException;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            // throw ValidationException::withMessages([
            //     'email' => ['The provided credentials are incorrect.'],
            // ]);
            Log::error('email or password wrong');
            throw new CustomException('email or password wrong', 404);

        }
        return $user->createToken('User Login')->plainTextToken;
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return "logout success";
    }
}
