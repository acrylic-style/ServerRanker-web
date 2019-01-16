<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Server Ranker</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #efefef;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 48px;
            }

            .links > a {
                color: #ffffff;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .body-bg {
                background-image: url('../images/background.png');
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-position: center;
                background-size: cover;
            }

            .body-bg::before {
                background-color: rgba(0,0,0,0.85);
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                content: ' ';
            }
        </style>
        <script>
            function selectServer() {
                location.href = `/leaderboard/${document.getElementById("server").value}`;
            }
        </script>
    </head>
    <body class="body-bg">
        <div class="flex-center position-ref full-height">
            <div class="top-right links">
                <select id="server" onchange="selectServer();">
                    <option value="" selected disabled>Select Server</option>
                    <option value="" disabled>------------------------------------</option>
                    <option value="server">Server Leaderboard</option>
                    <option value="user">User Leaderboard</option>
                    @foreach ($guilds as $guild)
                        <option value="{{ $guild->{'id'} }}" disabled>{{ $guild->{'name'} }}</option>
                    @endforeach
                </select>
                <a href="{{ url('/') }}">Home</a>
                <a href="{{ route('logout') }}">Logout</a>
            </div>
            <div class="content">
                <div class="title m-b-md">
                    Pick a server to getting started
                </div>

                <div class="links">
                    <a href="https://discord.gg/8u2QVRF">Support Server</a>
                    <a href="https://discordapp.com/api/oauth2/authorize?client_id=534057687751589895&permissions=379968&scope=bot">Invite</a>
                    <a href="https://github.com/acrylic-style/ServerRanker">GitHub</a>
                    <a href="/dashboard">Dashboard</a>
                </div>
            </div>
        </div>
    </body>
</html>
