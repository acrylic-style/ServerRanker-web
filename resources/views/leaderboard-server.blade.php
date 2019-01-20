<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Server Ranker</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link href="/css/common.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

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

            body {
                background-image: url('../images/background.png');
                background-repeat: no-repeat;
                background-attachment: scroll;
                background-position: center;
                background-size: cover;
            }

            body::before {
                background-color: rgba(0,0,0,0.85);
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                content: ' ';
            }

            .material-icons {
                font-size: 36px;
            }
        </style>
        <script>
            function selectServer() {
                location.href = `/leaderboard/${document.getElementById("server").value}`;
            }
        </script>
    </head>
    <body class="body-bg">
        <div class="flex-center position-ref full-height" style="overflow: scroll;">
            <div class="top-right links">
                <div class="__button">
                    <select id="server" class="btn-home btn-home--green" onchange="selectServer();">
                        <option value="" selected disabled>Select Server</option>
                        <option value="">--------------- Back ---------------</option>
                        <option value="server" disabled>Server Leaderboard (This page)</option>
                        <option value="user">User Leaderboard</option>
                        @foreach ($guilds as $guild)
                            <option value="{{ $guild->{'id'} }}" disabled>{{ $guild->{'name'} }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="__button">
                    <a href="{{ url('/') }}" class="btn-home btn-home--pink-darker">
                        <span class="btn-home__text">Home</span>
                    </a>
                </div>
                <div class="__button">
                    <a href="{{ route('logout') }}" class="btn-home btn-home--teal">
                        <span class="btn-home__text">Logout</span>
                    </a>
                </div>
            </div>
            <div class="content">
                <?php $i = 0; ?>
                @foreach ($datas as $data)
                    <?php if (!$data['name']) continue; ?>
                    <?php $i++; ?>
                    @if ($i <= (count(array_filter($datas, function ($arr) {return $arr['name'];}))))
                        <br />
                    @endif
                @endforeach
                <div class="title">
                    Server Leaderboard
                </div>
                <div class="scoreboard-table" style="background-color: rgba(10,10,10,0.6);">
                    <table class="scoreboard-table-table">
                        <thead>
                            <tr>
                                <th class="scoreboard-table-header scoreboard-table-header_rank">Rank</th>
                                <th class="scoreboard-table-header scoreboard-table-header_score">Score</th>
                                <th class="scoreboard-table-header scoreboard-table-header_server">Server</th>
                                <th class="scoreboard-table-header scoreboard-table-header_nom">Number of messages</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($datas as $data)
                                <?php if (!$data['name']) continue; ?>
                                <?php $i++; ?>
                                <tr class="scoreboard-table-body-row">
                                    <td>#{{$i}}</td>
                                    <td>{{ $format($data['data']->{'point'}) }}</td>
                                    <td>{{ $data['name'] }}</td>
                                    <td>{{ round((int)$data['data']->{'point'} / 300) }} - {{ round((int)$data['data']->{'point'} / 100) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
