<?php

use Illuminate\Http\Request;

Route::get('/dashboard', function (Request $request) {
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
    $filteredguilds = [];
    foreach($guilds as $guild) {
        if ($guild->{'permissions'} === 8 || $guild->{'permissions'} === 2146958847 || $guild->{'owner'}) {
            $filteredguilds = array_merge($filteredguilds, [["id" => $guild->{"id"}, "name" => $guild->{"name"}]]);
        }
    }
    return view("dashboard")->with([
        "user"        => $user,
        "guilds"      => $filteredguilds,
    ]);
});

Route::get("/dashboard/{server}", function(Request $request, $server) {
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
    $filteredguilds = [];
    $sguild = null;
    $guildIds = [];
    foreach ($guilds as $guild) {
        if ($guild->{'permissions'} === 8 || $guild->{'permissions'} === 2146958847 || $guild->{'owner'}) {
            $filteredguilds = array_merge($filteredguilds, [["id" => $guild->{"id"}, "name" => $guild->{"name"}]]);
            if ($guild->{"id"} == $server) { $sguild = $guild; }
            $guildIds = array_merge($guildIds, [$guild->{"id"}]);
        }
    }
    return view('dashboard-server')->with([
        "invalid" => !in_array($server, $guildIds),
        "guilds" => $filteredguilds,
        "guildId" => $sguild ? $sguild->{'id'} : "",
        "guildName" => $sguild ? $sguild->{'name'} : "",
    ]);
});
