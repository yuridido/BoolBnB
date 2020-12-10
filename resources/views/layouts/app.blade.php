<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,700;1,100&display=swap"
        rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BoolBnB</title>

    <!-- Scripts -->


    <!-- Fonts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="">
    <link href='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.40.1/maps/maps.css' rel='stylesheet' type='text/css'>
    <script src='https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.40.1/maps/maps-web.min.js'></script>
    <link href='https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' rel='stylesheet'>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.36.1/services/services-web.min.js"></script>
    <link rel="ahrotcut icon" type="image/png" href="https://cdn.iconscout.com/icon/free/png-256/airbnb-4-432491.png">
    <!-- Styles -->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
</head>

<body>


    <div class="container-page">
          @include('partials/mobilemenu')  
       
        @include('partials/navbar')

        <main class="">
            @yield('content')
        </main>
         
        @include('partials/footer')
       
        @include('partials/mobilemenubottom')
        
    </div>
    <script src="{{ asset('/js/app.js') }}"></script>
</body>

</html>
