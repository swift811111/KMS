<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
   
</head>
<body>
   <p>4564656465</p>

    @if(Auth::check())
        {{ Auth::user()->username}} 已登入，{{ HTML::link('logout', '登出') }}
    @endif
</body>
</html>