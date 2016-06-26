<!DOCTYPE html>
<html>
    <head>
        <title>404 Page not found</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:700" rel="stylesheet" type="text/css">
        <style>
            html, body { height: 100%; }
            body { margin: 0; padding: 0; width: 100%; color: #B0BEC5; display: table; font-weight: 100; font-family: 'Lato'; background: black; }
            .container { text-align: center; display: table-cell; vertical-align: middle; }
            .content { text-align: center; display: inline-block; }
            .title { font-size: 72px; margin-bottom: 0; font-weight: 700; }
            .back { margin-top: 20px; display:block; padding: 20px;            color: inherit; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">404</div>
                <div class="subtitle">Page not found</div>
                <a href='{{ url('/') }}' class="back">Home</a>
            </div>
        </div>
    </body>
</html>
