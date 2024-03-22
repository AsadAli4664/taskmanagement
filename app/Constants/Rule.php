<?php

namespace App\Constants;

class Rule
{
    // Rules According to API's
    private static $rules = [
        'LOGIN' => [
            'email' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ],
        'SIGNUP' => [
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string'
        ],
        'ADD_DEVICE_TOKEN' => [
            'value' => 'required',
        ],
        'CHANGE_PASSWORD' => [
            'old_password' => 'required',
            'new_password' => 'required',
        ],
        'FORGOT_PASSWORD' => [
            'email' => 'required',
        ],
        'RESET_PASSWORD' => [
            'email' => 'required',
            'password' => 'required',
        ],
        'UPDATE_PROFILE' => [
            'email' => 'required',
            'name' => 'required',
        ],
        'VERIFY_OTP' => [
            'user_id' => 'required',
            'code' => 'required',
        ],


        // Solar
        'ADD_SOLAR' => [
            'name' => 'required',
            'sn' => 'required',
            'power' => 'required',
            'unit' => 'required',
            'api_key' => 'required',
        ],
        'UPDATE_SOLAR' => [
            'name' => 'required',
            'sn' => 'required',
            'power' => 'required',
            'unit' => 'required',
            'api_key' => 'required',
        ],

        // Car
        'ADD_CAR' => [
            'title' => 'required',
            'company' => 'required',
            'model' => 'required',
            'number' => 'required',
            'color' => 'required',
            'battery' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            // 'extra_field_a' => 'required',
            // 'extra_field_b' => 'required',
            // 'extra_field_c' => 'required',
        ],
        'UPDATE_CAR' => [
            'title' => 'required',
            'company' => 'required',
            'model' => 'required',
            'number' => 'required',
            'color' => 'required',
            'battery' => 'required',
            // 'extra_field_a' => 'required',
            // 'extra_field_b' => 'required',
            // 'extra_field_c' => 'required',
        ],


        // Charger
        'ADD_CHARGER' => [
            'charger_key' => 'required',
            'title' => 'required',
            'make' => 'required',
            'location' => 'required',
            'speed' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            'pricing_variable' => 'required',
            'price' => 'required',
            'everyone_allowed' => 'required',
            'tenants_allowed' => 'required',
            'address' => 'required',
            'contact_no' => 'required',
        ],
        'UPDATE_CHARGER' => [
            'charger_key' => 'required',
            'title' => 'required',
            'make' => 'required',
            'location' => 'required',
            'speed' => 'required',
            'image' => 'mimes:jpg,jpeg,png|max:2048',
            'pricing_variable' => 'required',
            'price' => 'required',
            'everyone_allowed' => 'required',
            'tenants_allowed' => 'required',
            'address' => 'required',
            'contact_no' => 'required',
        ],
        'UPDATE_CHARGER_ISACTIVE' => [
            'is_active' => 'required',
        ],


        // Charging Request
        'ADD_CHARGING_REQUEST' => [
            'charger_id' => 'required',
            'vehicle_type' => 'required',
            'vehicle_model' => 'required',
            'vehicle_charge_type' => 'required',
            'amount' => 'required',
            'time' => 'required',
        ],
        'UPDATE_CHARGING_REQUEST' => [
            'vehicle_type' => 'required',
            'vehicle_model' => 'required',
            'vehicle_charge_type' => 'required',
            'amount' => 'required',
            'time' => 'required',
        ],
        'UPDATE_CHARGING_REQUEST_STATUS' => [
            'status' => 'required',
            'get_payment' => 'required',
        ],
        'CHARGE_REQUEST_PAYMENT' => [
            'stripe_token' => 'required',
            'amount' => 'required',
            'description' => 'required',
            'charging_request_id' => 'required',
        ],


        
        'UPDATE_TOKEN' => [
            'fcm_token' => 'required',
        ],
    ];

    public static function get($api){
      return self::$rules[$api];
  }
}