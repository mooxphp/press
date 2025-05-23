<?php

namespace Moox\Press\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Moox\Press\Http\Resources\WpUserResource;
use Moox\Press\Models\WpUser;

class WpUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = WpUser::with('userMeta')->get();

        return WpUserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_login' => 'required|string|max:255',
            'user_pass' => 'required|string|max:255',
            'user_nicename' => 'required|string|max:255',
            'user_email' => 'required|string|email|max:255',
            'nickname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $wpUser = new WpUser;

        $wpUser->fill($request->all());

        $wpUser->save();

        return new WpUserResource($wpUser);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): WpUserResource
    {
        $user = WpUser::with('userMeta')->findOrFail($id);

        return new WpUserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $wpUser = new WpUser;
        $request->only($wpUser->getFillable());
        $request->except($wpUser->getFillable());

        $validator = Validator::make($request->all(), [
            'user_login' => 'required|string|max:255',
            'user_pass' => 'required|string|max:255',
            'user_nicename' => 'required|string|max:255',
            'user_email' => 'required|string|email|max:255',
            'nickname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $wpUser = WpUser::findOrFail($id);

        $wpUser->fill($request->all());
        $wpUser->save();

        return new WpUserResource($wpUser);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($wpUser)
    {
        $wpUser = WpUser::findOrFail($wpUser);
        $wpUser->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
