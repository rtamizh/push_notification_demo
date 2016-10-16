<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Session;
use Config;
use Push;
use View;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createApp(Request $request)
    {
        $app_name = $request->input('app_name');
        $response = Push::createApp($app_name);
        // for demo purpose i added this configuration on runtime. create app and change the app_secret before running the package is advisible
        Session::put('app_secret', $response['app_secret']);
        Config::set('realtime-pusher.app_secret_id', $response['app_secret']);
        return $response;
    }

    public function createUser(Request $request)
    {
        $user_name = $request->input('user_name');
        $password = $request->input('password');
        $response = Push::createUser($user_name, $password);
        Session::put('user_secret', $response['user_secret']);
        return $response;
    }

    public function notificationDemo(Request $request)
    {
        $user_secret = $request->input('user_secret');
        $notification_server_url = Config::get('realtime-pusher.url').':'.Config::get('realtime-pusher.port');
        return view('welcome')->with('user_secret', $user_secret)->with('notification_server_url',$notification_server_url);
    }

    public function notify(Request $request)
    {
        $text = $request->input('text');
        $image = $request->input('image');
        $response = Push::Notify(Session::get('user_secret'), $text, $image);
        return $response;
    }
}
