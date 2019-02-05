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
    $directories = glob(env('SERVERRANKER_LOCATION', '../../ServerRanker').'/data/servers/*');
    $guildData = [];
    $info = curl_init("https://discordapp.com/api/users/@me/guilds");
    curl_setopt_array($info, [
        CURLOPT_HTTPHEADER => ["Authorization: Bot ".env("BOT_TOKEN", "")],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $botguilds = json_decode(curl_exec($info));
    curl_close($info);
    $ids = "";
    foreach ($guilds as $guild) {
        $ids .= (string)$guild->{"id"} . ",";
    }
    foreach ($botguilds as $guild) {
        $guildData[$guild->{"id"}] = ["name" => $guild->{'name'}];
    }
    $data = [];
    foreach ($directories as $dir) {
        preg_match("/\/data\/servers\/(\d{10,20})/", $dir, $matches);
        $serverdata = json_decode(file_get_contents("$dir/config.json"));
        $serverdata->{"multipliers"} = [];
        $data[$matches[1]]["data"] = $serverdata;
        $data[$matches[1]]["id"] = $matches[1];
        $data[$matches[1]]["name"] = in_array($matches[1], array_keys($guildData)) ? $guildData[$matches[1]]['name'] : null;
    }
    sort($data);
    $data = array_reverse($data, true);
    return view('leaderboard-server')->with([
        "ids" => explode(",", $ids),
        "user" => $user,
        "guilds" => $guilds,
        "datas" => $data,
        "format" => function ($number) {
            return number_format((float)$number);
        },
    ]);
});

Route::get("/leaderboard/user", function(Request $request) {
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
    $directories = glob(env('SERVERRANKER_LOCATION', '../../ServerRanker').'/data/users/*');
    $data = [];
    foreach ($directories as $dir) {
        preg_match("/\/data\/users\/(\d{10,20})/", $dir, $matches);
        $userdata = json_decode(file_get_contents("$dir/config.json"));
        $data[$matches[1]]["data"] = $userdata;
        $data[$matches[1]]["id"] = $matches[1];
        $data[$matches[1]]["name"] = $userdata->{'tag'};
    }
    sort($data);
    $data = array_reverse($data, true);
    return view('leaderboard-user')->with([
        "user" => $user,
        "guilds" => $guilds,
        "datas" => $data,
        "format" => function ($number) {
            return number_format((float)$number);
        },
    ]);
});
