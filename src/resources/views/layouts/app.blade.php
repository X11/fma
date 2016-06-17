<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{!! csrf_token() !!}">

    <title>Feeding my addiction</title>

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
    @if (!Request::is('/'))
        @include('partial.header')
        @if (isset($breadcrumbs))
            @include('partial.breadcrumbs')
        @endif
    @endif

    @if (session('status'))
        <section class="section">
            <div class="container">
                <div class="notification is-primary">
                    <button class="delete"></button>
                    {{ session('status') }}
                </div>
            </div>
        </section>
    @endif

    @yield('content')

    @if (isset($breadcrumbs))
        @include('partial.breadcrumbs')
    @endif

    @include('partial.footer')
    @yield('post-footer')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://rawgit.com/notifyjs/notifyjs/master/dist/notify.js"></script>
    <script src="{{ elixir('js/all.js') }}"></script>

    <script type="text/javascript" charset="utf-8">
    $(window).on('load', function(){
        $("img[data-src]").unveil(null, function(){
            $(this).load(function(){
                this.style.opacity = 1;
            });
        });
    });
    </script>
    @yield('scripts')
</body>
</html>
