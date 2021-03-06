<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <meta name="description" content="@yield('description', 'FMA Provides a platform to track TV shows and series. Discover new TV shows and keep track of when new episodes are about to air.')" />
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <meta name="application-name" content="Feed my addiction">
    <meta name="robots" content="index,follow,noodp"><!-- All Search Engines -->
    <meta name="googlebot" content="index,follow"><!-- Google Specific -->
    @yield('meta')

    <title>@yield('title', 'Feeding my addiction')</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    @if ($THEME =="green")
        <link href="{{ elixir('css/green.css') }}" rel="stylesheet">
    @else 
        <link href="{{ elixir('css/default.css') }}" rel="stylesheet">
    @endif
    @yield('styles')

    @if ($THEME == "inverted")
        <style type="text/css" media="screen">
            html {
                -webkit-filter: invert(100%);
                filter: invert(100%);
            }
        </style>
    @endif
    <style type="text/css" media="screen">
        img[data-src] {
            opacity: 0;
            transition: opacity .3s ease-in;
        }
    </style>
    <script type="text/javascript" charset="utf-8">
        window.tvdb_load_hd = '{{ $TVDB_LOAD_HD }}'
    </script>
</head>
<body id="app-layout">
    @yield('body')

    @stack('post-footer')
    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://rawgit.com/notifyjs/notifyjs/master/dist/notify.js"></script>
    <script type="text/javascript" charset="utf-8">
    $(window).on('load', function(){
        $("[data-src]").unveil(null, function(){
            $(this).load(function(){
                this.style.opacity = 1;
            });
        });
    });
    </script>
    <script src="{{ elixir('js/all.js') }}"></script>
    @stack('scripts')
</body>
</html>
