<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, "Courier New", Helvetica;
        }
        #header {
            font-size:20px;
            color:#155577;
            border-bottom:1px solid #999;
            padding-bottom:5px;
        }
        #footer {
            border-top:1px solid #999;
            margin-top:10px;
            padding-top:5px;
        }
    </style>
</head>
<body>
    <div id="header">
    {{ config('app.name') }}
    </div>
    @yield('content')
    <div id="footer">
    {{ config('app.name') }} - La cartothèque des procureurs
    </div>
</body>
</html>
