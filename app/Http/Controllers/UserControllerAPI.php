<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\UserResource as UserResource;
use Illuminate\Support\Facades\DB;

use App\User;
use App\StoreUserRequest;
use Hash;

class UserControllerAPI extends Controller
{
    public function getUsers(Request $request)
    {
        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }
    }

    public function getUser($id)
    {
        return new UserResource(User::find($id));
    }

    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'newAdminEmail' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->passes()) {
            if (!Hash::check($request->input('password'), $request->user()->password)) {
                return response()->json(
                    ['password' => 'Password incorrect.'], 400);
            }

            $request->user()->email = $request['newAdminEmail'];
            $request->user()->save();

            return response()->json(['msg' => 'Email changed with success.']);
        } else {
            return response()->json(['msg' => 'Invalid request.'], 400);
        }
    }


    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required',
            'newPassword' => 'required|confirmed|string|min:6'
        ]);

        if ($validator->passes()) {
            if (!Hash::check($request->input('currentPassword'), $request->user()->password)) {
                return response()->json(
                    ['currentPassword' => 'Old Password incorrect.'], 400);
            }

            $request->user()->password = Hash::make($request->input('newPassword'));
            $request->user()->save();

            return response()->json(['msg' => 'Password changed with success.']);
        } else {
            return response()->json(['msg' => 'Invalid request.'], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,'.$id,
                'nickname' => 'required|string|max:20|unique:users,nickname,'.$id,
                'reason_blocked' => 'nullable|string|max:250|min:6',
                'reason_reactivated' => 'nullable|string|max:250|min:6'
            ]);

        $user = User::findOrFail($id);
        $user->update($request->all());
        return new UserResource($user);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }
}
