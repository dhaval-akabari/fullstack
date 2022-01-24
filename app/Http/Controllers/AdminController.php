<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request) {
        // If user not loggedin & access other route then redirect to login page
        if(!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }

        // If user not loggedin & route is login then return admin view
        if(!Auth::check() && $request->path() == 'login') {
            return view('admin');
        }
        
        // If user is loggedIn then redirect to dashboard
        if ($request->path() == 'login') {
            return redirect('/dashboard');
        }

        return $this->checkForPermission($request->path());
    }

    public function checkForPermission($path) {
        $user = Auth::user();
        $permission = json_decode($user->role->permission);
        $hasPermission = false;

        // if permission is not set then return access of all pages.
        if(!$permission) {
            return view('admin');
        }

        foreach($permission as $p) {
            if($p->name === $path) {
                if($p->read) {
                    $hasPermission = true;
                }
            }
        }
        
        if($hasPermission) {
            return view('admin');
        }

        return view('admin');
        return view('notfound');

    }

    public function getAuthUser() {
        if(Auth::check()) {
            return Auth::user();
        }
        return null;
    }

    public function getAuthUserPermission() {
        if(Auth::check()) {
            return Auth::user()->role->permission;
        }
        return null;
    }

}
