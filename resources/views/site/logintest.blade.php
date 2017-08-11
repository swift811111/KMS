<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    {{Form::open(['url'=>'login', 'method'=>'post'])}}
    {{Form::label('username', '帳號')}}<br>
    {{Form::text('username')}}<br>
    {{Form::label('password', '密碼')}}<br>
    {{Form::password('password')}}<br> 
    {{Form::submit('sign up')}}
    {{Form::close()}}

</body>
</html>