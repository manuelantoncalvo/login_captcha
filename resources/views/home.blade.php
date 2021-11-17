<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Login Test</title>
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center font-weight-bold">
            Welcome Page - InvGate
        </div>
        <div class="card-body">
            User: {{\Illuminate\Support\Facades\Auth::user()->email}}
            <br />
            <a href="{{ \Illuminate\Support\Facades\URL::to('logout') }}">Logout</a>
        </div>
    </div>
</div>
</body>
</html>
