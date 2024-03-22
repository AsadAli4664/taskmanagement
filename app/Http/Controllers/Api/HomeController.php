<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Cart;


// Models
use App\Models\Group;
use App\Models\Category;

// Constants
use App\Models\Wishlist;
use App\Constants\Message;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Constants\ResponseCode;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function counters(){
        $auth_user = request()->user();

        $result = [
            "whishlist" => Wishlist::where('user_id', $auth_user->id)->count(),
            "cart" => Cart::where('user_id', $auth_user->id)->count(),
        ];
        return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);
    }
    public function filter(){
        $auth_user = request()->user();

        $result = [
            "categories" => Category::all(),
            "tags" => Tag::all(),
            "groups" => Group::all(),

            "applications" => Application::all(),

        ];
        return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);
    }
}
