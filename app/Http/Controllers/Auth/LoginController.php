<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'required'
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if($user->role->isAdmin === 0) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect login details.'
                ], 401);    
            }
            return response()->json([
                'msg' => 'You are logged in.',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'msg' => 'Incorrect login details..'
            ], 401);
        }
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
