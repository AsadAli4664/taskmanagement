<?php

use App\Models\User;
use App\Models\Notification;
use App\Models\DeviceToken;
use App\Models\ChargingRequest;
use App\Models\Charger;

// Twilio
use Twilio\Rest\Client;


function sendSms($to_number, $message)
{
    try {

        $account_sid = env("TWILIO_SID");
        $auth_token = env("TWILIO_TOKEN");
        $twilio_number = env("TWILIO_FROM");

        $client = new Client($account_sid, $auth_token);
        $client->messages->create($to_number, [
            'from' => $twilio_number, 
            'body' => $message]);
        // dd('SMS Sent Successfully.');
        return true;
    } catch (\Exception $e) {
        // dd("Error: ". $e->getMessage());
        return false;
    }
}


function statusColor($status){

	if($status == 'Pending')
		return 'info';
	else if($status == 'Approved')
		return 'success';
	else if($status == 'Declined')
		return 'danger';
}


function startChargingHelper($pile_sn, $key, $limit_time, $limit_kwh, $type)
{

	// 0 - Parameter cannot be empty
	// 1 - success
	// 11 - Key value not found
	// 12 - Please do not request frequently
	// 13 - This key value is disabled
	// 14 - Charge point number does not exist
	// 15 - Charge point number does not match merchant key
	// 16 - Startup failed
	// 17 - Not connect to car
	try{
		$data = [
            "pile_sn" => $pile_sn,
            "key" => $key,
        ];

        $headers = [
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fr.wydq.tech/weeyuen/api.php/interface/Start_charge');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);

        return json_decode($response);
	}
	catch(\Exception $e)
	{
		return ["code" => 16, "msg" => "Startup failed" ];
	}
}

function stopChargingHelper($pile_sn, $key)
{

	// 0 - Parameter cannot be empty
	// 1 - success
	// 11 - Key value not found
	// 12 - Please do not request frequently
	// 13 - This key value is disabled
	// 14 - Charge point number does not exist
	// 15 - Charge point number does not match merchant key
	// 16 - operation failed
	try{
		$data = [
            "pile_sn" => $pile_sn,
            "key" => $key,
        ];

        $headers = [
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fr.wydq.tech/weeyuen/api.php/interface/Stop_charge');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);

        return json_decode($response);
	}
	catch(\Exception $e)
	{
		return ["code" => 16, "msg" => "Operation Failed" ];
	}
}


function savePostFile($file, $fileName)
{
	$file->move(storage_path('/app/public/posts'), $fileName);
}

function savePawtaiProfileImage($file, $fileName)
{
	$file->move(storage_path('/app/public/pawtaiProfile'), $fileName);
}


function makeUniqueFileName($file, $uniqueKey)
{
	return (time() . $uniqueKey . '.' .$file->getClientOriginalExtension());	
}

function makeFileName($file)
{
	return (time() . '.' .$file->getClientOriginalExtension());	
}

function deleteFiles($files)
{
	\Storage::delete($files);
}

function sendPushNotification($body ,$device_tokens , $badge = 0, $additional_info = Null)
{
	$puserId;
	$fbResult = [];
	$fcm_server_api_key = env("FCM_SERVER_API_KEY");
	$data = [
		"registration_ids" => $device_tokens,
		"priority" => "normal",
		"notification" => [
			"title" => "Solar Glow",
			"body"  => $body,
			"sound" => "default",
			"color" => "#FF5757",
			// "badge" => $badge
		],
		"data" => [
			"title" => "Solar Glow",
			"body"  => $body,
			"additional_info"  => $additional_info,
			
		]
	];
	$dataString = json_encode($data);
	$headers = [
		'Authorization: key=' . $fcm_server_api_key,
		'Content-Type: application/json',
	];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
	$result = curl_exec($ch);

	// 	echo $result;
}

function chargingRequestAcceptedNotifcation($charging_request_id, $from_user_id, $payment_status){

	$charging_request = ChargingRequest::where('id', $charging_request_id)->with('user')->first();

	$from_user = User::find($from_user_id);

	$type = $payment_status == 1? 'ChargingRequestPaid' : 'ChargingRequestFree';


	if($payment_status == 1)
		$body = "Request accepted for free of charge, connect your car!";
	else
		$body = "Request accepted, proceed to payment.";

	$device_tokens = DeviceToken::where('user_id', $charging_request->user_id)->pluck('fcm_token')->toArray();

	$notification = notificationCreate($charging_request->user_id, $type,  $body, $charging_request, $charging_request->id);
	$additional_info = [
		"type" => $type,
		"notification" => $notification,
	];
	sendPushNotification($body, $device_tokens, 0, $additional_info);
}

function chargingRequestRejectedNotifcation($charging_request_id, $from_user_id){

	$charging_request = ChargingRequest::where('id', $charging_request_id)->with('user')->first();

	$from_user = User::find($from_user_id);

	$type = 'ChargingRequestRejected';

	$body = $from_user->name." has rejected the request.";

	$device_tokens = DeviceToken::where('user_id', $charging_request->user_id)->pluck('fcm_token')->toArray();

	$notification = notificationCreate($charging_request->user_id, $type,  $body, $charging_request, $charging_request->id);
	$additional_info = [
		"type" => $type,
		"notification" => $notification,
	];
	sendPushNotification($body, $device_tokens, 0, $additional_info);
}

function chargerStarted($charging_request_id, $from_user_id){

	$charging_request = ChargingRequest::where('id', $charging_request_id)->with('user')->first();

	$from_user = User::find($from_user_id);

	$type = 'ChargerStarted';

	$body = $from_user->name." has started charger for charging.";

	$device_tokens = DeviceToken::where('user_id', $charging_request->user_id)->pluck('fcm_token')->toArray();

	$notification = notificationCreate($charging_request->user_id, $type,  $body, $charging_request, $charging_request->id);
	$additional_info = [
		"type" => $type,
		"notification" => $notification,
	];
	sendPushNotification($body, $device_tokens, 0, $additional_info);
}

function chargerStopped($charging_request_id, $from_user_id){

	$charging_request = ChargingRequest::where('id', $charging_request_id)->with('user')->first();

	$from_user = User::find($from_user_id);

	$type = 'ChargerStopped';

	$body = $from_user->name." has stopped charger for charging.";

	$device_tokens = DeviceToken::where('user_id', $charging_request->user_id)->pluck('fcm_token')->toArray();

	$notification = notificationCreate($charging_request->user_id, $type,  $body, $charging_request, $charging_request->id);
	$additional_info = [
		"type" => $type,
		"notification" => $notification,
	];
	sendPushNotification($body, $device_tokens, 0, $additional_info);
}

function chargingRequestNotification($charging_request_id, $from_user_id){

	$charging_request = ChargingRequest::where('id', $charging_request_id)->with('user')->first();
	$charger = Charger::where('id', $charging_request->charger_id)->first();

	$from_user = User::find($from_user_id);

	$type = 'ChargingRequest';


	$body = $from_user->name." has requested for charge.";

	$device_tokens = DeviceToken::where('user_id', $charger->user_id)->pluck('fcm_token')->toArray();

	$notification = notificationCreate($charger->user_id, $type,  $body, $charging_request, $charging_request->id);
	$additional_info = [
		"type" => $type,
		"notification" => $notification,
	];
	sendPushNotification($body, $device_tokens, 0, $additional_info);
}


function chargingRequestPaymentCompletedNotification($charging_request_id, $from_user_id){

	$charging_request = ChargingRequest::where('id', $charging_request_id)->with('user')->first();
	$charger = Charger::where('id', $charging_request->charger_id)->first();

	$from_user = User::find($from_user_id);

	$type = 'ChargingRequestPaymentSuccess';


	$body = "Payment received against Request no. ".$charging_request_id;

	$device_tokens = DeviceToken::where('user_id', $charger->user_id)->pluck('fcm_token')->toArray();

	$notification = notificationCreate($charger->user_id, $type,  $body, $charging_request, $charging_request->id);
	$additional_info = [
		"type" => $type,
		"notification" => $notification,
	];
	sendPushNotification($body, $device_tokens, 0, $additional_info);
}


function notificationCreate($user_id, $type, $message, $additional_data, $data_id, $resource_type = 'ChargingRequest'){
	return $notification = Notification::create([
		"user_id" => $user_id,
		"type" => $type,
		"message" => $message,
		"additional_data" => json_encode($additional_data),
		"data_id" => $data_id,
		"resource_type" => $resource_type,
	]);
}

function check_permission($permission)
{
    if (!Auth::user()->hasPermissionTo($permission)) {
        // Log a message for debugging
        \Log::error('Permission check failed for user: ' . Auth::user()->id);

        // Redirect with an error message
        return redirect()->route('dashboard')->with('error', 'You do not have the required permission');
    }
}