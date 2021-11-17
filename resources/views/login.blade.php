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
                Login Test - InvGate
            </div>
            @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    {{ $error }}
                </div>
            @endforeach
            <div class="card-body">
                <form name="login-form" id="login-form" method="post" action="{{url('login')}}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required="">
                    </div>
                    @if(\Illuminate\Support\Facades\Cookie::has('login_attempts') && \Illuminate\Support\Facades\Cookie::get('login_attempts') >= 3)
                        <div class="form-group">
                            <p>{!! \Mews\Captcha\Facades\Captcha::img(); !!}</p>
                            <input type="text" id="captcha" name="captcha" class="form-control" required="">
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    </body>
</html>
