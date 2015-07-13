<?php namespace Ale\Http\Controllers\Auth;

use Ale\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $request;
    protected $auth;

    public function __construct(Guard $auth, Registrar $registrar)
    {
        $this->auth = $auth;
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function postRedirect()
    {
        return redirect()->url('/vote/index');
    }

    public function postLogin()
    {
        $jResponse = [
            'success' => true,
            'message' => '',
            'url' => route('vote.index')
        ];

        return response()->json($jResponse);
    }

    public function getLogout()
    {
        return redirect()->route('login');
    }

}
