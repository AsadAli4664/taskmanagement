<?php

// Carbon
use Carbon\Carbon;

// Constants
use App\Constants\Message;
use App\Constants\ResponseCode;



function attemptLogin($request){
	$credentials = request(['email', 'password']);
	if(!Auth::attempt($credentials))
	    return response()->json(['message' => Message::INVALID_CREDENTIALS], 401);
	
	$user = $request->user();
	if($user->is_deleted)
		return response()->json(['message' => Message::UNAUTHORIZED], 401);


	// Check if the user has the "customer" role
    if (!($user->role == 'Customer')) {
        return response()->json(['message' => Message::UNAUTHORIZED], 401);
    }

	$tokenResult = $user->createToken('Personal Access Token');

	$token = $tokenResult->token;
	if ($request->remember_me)
	    $token->expires_at = Carbon::now()->addWeeks(1);
	$token->save();

	
	$result = [
		'id' => $user->id,
		'name' => $user->name,
		'email' => $user->email,
		'token_type' => 'Bearer',
		'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
		'access_token' => $tokenResult->accessToken,
		'email_verified_at' => $user->email_verified_at,
	];
    return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);

}