<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>YaWeather</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
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
            position: relative;
            display: table;
            margin: 30px auto 10px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
            word-break: break-word;
        }

        .links > a {
            color: #636b6f;
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
    </style>
</head>
<body>
@if (Route::has('login'))
    <div class="top-right links">
        @auth
            <a href="{{ url('/admin') }}">В Панель</a>
        @else
            <a href="{{ route('login') }}">Вход</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}">Регистрация</a>
            @endif
        @endauth
    </div>
@endif

<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            <img src="https://yastatic.net/weather/i/icons/blueye/color/svg/bkn_n.svg" alt="YaWeather"
                 style="opacity:.8;max-width: 100%;">
            <b>Ya</b>Weather
        </div>


    </div>
</div>
</body>
</html>
