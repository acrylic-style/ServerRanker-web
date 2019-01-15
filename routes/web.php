<?php

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

Route::view('/', 'welcome');

Route::get('/login', function () {
    $cid = env('CLIENT_ID', '');
    $redirect_uri = env('REDIRECT_URI', 'http://localhost:8000/callback');
    return Redirect::to("https://discordapp.com/oauth2/authorize?client_id=$cid&redirect_uri=$redirect_uri&response_type=code&scope=identify");
})->name('login');

Route::any('/callback', function () {
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
        $token = curl_init();
        curl_setopt_array($token, array(
            CURLOPT_URL => $token_request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_POSTFIELDS => array(
                "grant_type" => "authorization_code",
                "client_id" => $client_id,
                "client_secret" => $client_secret,
                "redirect_uri" => $redirect_uri,
                "code" => $_GET["code"],
                "scope" => "identify",
            ),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $data = curl_exec($token);
        echo '"'.$data.'"';
        $oauth = new OAuth($client_id, $client_secret);
        $oauth->setToken($code);
        echo $oauth->getAccessToken("https://discordapp.com/api/v6/oauth2/token");
        $resp = json_decode($data);
        curl_close($token);
        if (isset($resp->access_token)) {
            $access_token = $resp->access_token;
            $info_request = "https://discordapp.com/api/users/@me";
            $info = curl_init();
            curl_setopt_array($info, array(
                CURLOPT_URL => $info_request,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$access_token}"
                ),
                CURLOPT_RETURNTRANSFER => true
            ));
            $user = json_decode(curl_exec($info));
            curl_close($info);
            echo "<h1>Hello, {$user->username}#{$user->discriminator}.</h1><br><h2>{$user->id}</h2><br><img src='https://discordapp.com/api/v6/users/{$user->id}/avatars/{$user->avatar}.jpg' /><br><br>Dashboard Token: {$access_token}";
        } else {
            echo json_encode(array("message" => "Authentication Error"));
        }
    } else {
        echo json_encode(array("message" => "No Code Provided"));
    }
});
