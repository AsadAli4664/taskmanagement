<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\User;

// Constants
use App\Constants\Message;
use App\Constants\ResponseCode;

// Carbon
use Carbon\Carbon;
//Mail
use App\Mail\SignUp;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] username
     * @param  [string] email
     * @param  [string] password
     * @return [string] message
     * @return [object] result
     */
    public function signup(Request $request)
    {

        $validator = validateData($request, 'SIGNUP');
        if ($validator['status'])
            return makeResponse(ResponseCode::NOT_SUCCESS, Message::VALIDATION_FAILED, null, $validator['errors']);

        $user = User::findUserByEmail($request->email);
        if (!empty($user))
            return makeResponse(ResponseCode::NOT_SUCCESS, "Email " . Message::ALREADY_EXIST);

        $user = User::createUser($request->name, $request->email, $request->password, '', 'Customer');
        try {
            $token = \Hash::make($request->email);
            $url = url('/verify_email', [$request->email]);
            Mail::to($request->email)->send(new SignUp($url));
        } catch (\Exception $e) {
            return makeResponse(ResponseCode::ERROR, $e->getMessage());
        }
        return attemptLogin($request);
    }

    /**
     * Login user and create token
     *
     * @param  [string] username
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] message
     * @return [object] result
     */
    public function login(Request $request)
    {
        $validator = validateData($request, 'LOGIN');
        if ($validator['status'])
            return makeResponse(ResponseCode::NOT_SUCCESS, Message::VALIDATION_FAILED, null, $validator['errors']);

        return attemptLogin($request);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $authUser = $request->user();
        $request->user()->token()->revoke();

        return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL);
    }

    /**
     * Delete & Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function delete(Request $request)
    {
        $authUser = $request->user();
        $request->user()->token()->revoke();

        User::where('id', $authUser->id)->update([
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL);
    }

    /**
     * Get the authenticated User
     *
     * @return [string] message
     * @return [object] result
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $result = [
            "info" => $user,
        ];
        return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);
    }

    /**
     * Update authenticated User
     *
     * @param  [string] email
     * @return [string] message
     * @return [object] result
     */
    public function profileUpdate(Request $request)
    {
        $validator = validateData($request, 'UPDATE_PROFILE');
        if ($validator['status'])
            return makeResponse(ResponseCode::NOT_SUCCESS, Message::VALIDATION_FAILED, null, $validator['errors']);

        $user = User::where('email', $request->email)->first();

        if (!empty($user) && auth()->user()->id != $user->id)
            return makeResponse(ResponseCode::NOT_SUCCESS, Message::ALREADY_EXIST);


        $user = $request->user()->update(["name" => $request->name, "email" => $request->email,  "phone" => $request->phone]);

        return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL);
    }
}
