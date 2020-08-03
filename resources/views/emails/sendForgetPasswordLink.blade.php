<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Dear {{ $data['name']}}</h2>
<p>you can change your password through  this link <a href="{{ url('/').'/forget-password/'.$data['uuid']."/".$data['email']."/".$data['forget_token'] }}" > From Here </a> </p>
</body>
</html>
