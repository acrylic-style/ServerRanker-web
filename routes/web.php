<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(Request $request) {
    return view('welcome')->with('request', $request);
});

Route::get('/login', function (Request $request) {
    $cid = env('CLIENT_ID', '');
    $redirect_uri = env('REDIRECT_URI', 'http://localhost:8000/callback');
    return Redirect::to("https://discordapp.com/oauth2/authorize?client_id=$cid&redirect_uri=$redirect_uri&response_type=code&scope=identify%20guilds");
})->name('login');

Route::get('/logout', function (Request $request) {
    @$request->session()->forget("access_token");
    return Redirect::to("/");
})->name('logout');

Route::any('/callback', function (Request $request) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (isset($_GET["error"])) {
        echo json_encode(array("message" => "Authorization Error"));
    } elseif (isset($_GET["code"])) {
        $redirect_uri = env('REDIRECT_URI', 'http://localhost:8000/callback');
        $token_request = "https://discordapp.com/api/v6/oauth2/token";
        $client_secret = env('CLIENT_SECRET', '');
        $client_id = env('CLIENT_ID', '');
        $code = $_GET['code'];
        $token = curl_init($token_request);
        curl_setopt_array($token, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                "client_id" => $client_id,
                "client_secret" => $client_secret,
                "redirect_uri" => $redirect_uri,
                "code" => $_GET["code"],
                "scope" => "identif guilds",
                "grant_type" => "authorization_code",
            ]),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ));
        $resp = json_decode(curl_exec($token));
        curl_close($token);
        if (isset($resp->access_token)) {
            $access_token = $resp->access_token;
            $request->session()->put('access_token', $access_token);
            return Redirect::to("/dashboard");
            //echo "<h1>Hello, {$user->username}#{$user->discriminator}.</h1><br><h2>{$user->id}</h2><br><img src='https://discordapp.com/api/v6/users/{$user->id}/avatars/{$user->avatar}.jpg' /><br><br>Dashboard Token: {$access_token}";
        } else {
            echo json_encode(array("message" => "Authentication Error"));
        }
    } else {
        echo json_encode(array("message" => "No Code Provided"));
    }
});

require_once('dashboard.php');
require_once('leaderboard.php');
