<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public const LOGIN_ATTEMPTS_LIMIT = 3;
    public const LIMIT_CAPTCHA_LIFETIME = 10;
    public const LIMIT_ATTEMPTS_COOKIE_PARAM = 'login_attempts';

    public function showLogin(Request $request)
    {
        return view('login');
    }

    public function doLogin(Request $request): RedirectResponse
    {
        $rules = [
            'email'    => 'required|email',
            'password' => 'required|alphaNum|min:3'
        ];

        if($this->loginAttemptLimitReached($request)) {
            $rules['captcha'] = 'required|captcha';
        }

        $validator = Validator::make($request->all(), $rules, ['captcha' => 'Invalid captcha.']);

        if ($validator->fails()) {
            return Redirect::to('login')
                ->withErrors($validator)
                ->withInput($request->except('password'))
                ->withCookie($this->getFailedLoginAttemptCounterCookie($request));;
        }

        $userLoginAttributes = array(
            'email'     => $request->get('email'),
            'password'  => $request->get('password')
        );

        if (Auth::attempt($userLoginAttributes)) {
            return Redirect::to('home')
                ->withCookie(\cookie(self::LIMIT_ATTEMPTS_COOKIE_PARAM, 0, self::LIMIT_CAPTCHA_LIFETIME));
        } else {
            $this->getFailedLoginAttemptCounterCookie($request);
            return Redirect::to('login')
                ->withErrors(['error' => 'Invalid username or password'])
                ->withInput($request->except('password'))
                ->withCookie($this->getFailedLoginAttemptCounterCookie($request));
        }
    }

    protected function getFailedLoginAttemptCounterCookie(Request $request)
    {
        $attempts = 0;
        if ($request->hasCookie(self::LIMIT_ATTEMPTS_COOKIE_PARAM)) {
            $attempts = (int)$request->cookie(self::LIMIT_ATTEMPTS_COOKIE_PARAM);
        }
        return \cookie(self::LIMIT_ATTEMPTS_COOKIE_PARAM, $attempts + 1, self::LIMIT_CAPTCHA_LIFETIME);
    }

    protected function loginAttemptLimitReached(Request $request): bool
    {
        return $request->hasCookie(self::LIMIT_ATTEMPTS_COOKIE_PARAM) && $request->cookie(self::LIMIT_ATTEMPTS_COOKIE_PARAM) >= self::LOGIN_ATTEMPTS_LIMIT;
    }

    public function doLogout(): RedirectResponse
    {
        Auth::logout();
        return Redirect::to('login');
    }
}
