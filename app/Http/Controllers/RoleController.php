<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRole() {
        return Role::orderBy('id', 'DESC')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addRole(RoleRequest $request) {
        return Role::create([
            'roleName' => $request->roleName
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editRole(RoleRequest $request) {
        return Role::whereId($request->id)->update([
            'roleName' => $request->roleName
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRole(Request $request) {
        return Role::whereId($request->id)->delete();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignPermission(Request $request) {
        return Role::whereId($request->id)->update([
            'permission' => $request->permission,
        ]);
    }
}
