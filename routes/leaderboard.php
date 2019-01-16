<?php

use Illuminate\Http\Request;

Route::get('/leaderboard', function(Request $request) {
    if (!$request->session()->has('access_token')) return Redirect::to("/login");
    $options = [
        CURLOPT_HTTPHEADER => ["Authorization: Bearer {$request->session()->get('access_token')}"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ];
    $info = curl_init("https://discordapp.com/api/users/@me");
    curl_setopt_array($info, $options);
    $user = json_decode(curl_exec($info));
    curl_close($info);
    $info = curl_init("https://discordapp.com/api/users/@me/guilds");
    curl_setopt_array($info, $options);
    $guilds = json_decode(curl_exec($info));
    curl_close($info);
    return view("leaderboard")->with([
        "user"        => $user,
        "guilds"      => $guilds,
    ]);
});

Route::get("/leaderboard/server", function(Request $request) {
    if (!$request->session()->has('access_token')) return Redirect::to("/");
    $options = [
        CURLOPT_HTTPHEADER => ["Authorization: Bearer {$request->session()->get('access_token')}"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ];
    $info = curl_init("https://discordapp.com/api/users/@me");
    curl_setopt_array($info, $options);
    $user = json_decode(curl_exec($info));
    curl_close($info);
    $info = curl_init("https://discordapp.com/api/users/@me/guilds");
    curl_setopt_array($info, $options);
    $guilds = json_decode(curl_exec($info));
    curl_close($info);
    return view('leaderboard-server')->with([
        "user" => $user,
        "guilds" => $guilds,
    ]);
});

Route::get("/leaderboard/user", function(Request $request) {
    if (!$request->session()->has('access_token')) return Redirect::to("/");
    $options = [
        CURLOPT_HTTPHEADER => ["Authorization: Bearer {$request->session()->get('access_token')}"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ];
    $info = curl_init("https://discordapp.com/api/users/@me");
    curl_setopt_array($info, $options);
    $user = json_decode(curl_exec($info));
    curl_close($info);
    $info = curl_init("https://discordapp.com/api/users/@me/guilds");
    curl_setopt_array($info, $options);
    $guilds = json_decode(curl_exec($info));
    curl_close($info);
    return view('leaderboard-user')->with([
        "user" => $user,
        "guilds" => $guilds,
    ]);
});
