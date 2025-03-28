<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
class ForgetPassController extends Controller
{
    public function forgetPassword(Request $request){
        try{
            $request->validate(['email' => 'required|email']);
        } catch (ValidationException $e){
            return response() -> json(["errors" => $e -> errors()],422);
        }
 
        $status = Password::sendResetLink(
            $request->only('email')
        );
     
        return $status === Password::ResetLinkSent
            ? response()-> json(['status' => __($status)],200)
            :  response()-> json(['email' => __($status)],422);
    }
    public function updatePassword(Request $request){

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        return $status === Password::PasswordReset
            ?response()-> json(['status'=> __($status)],200)
            : response()-> json(['email' => [__($status)],422]);
    }
}
