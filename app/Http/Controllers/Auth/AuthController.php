<?php namespace Ale\Http\Controllers\Auth;

use Ale\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Ale\Voter;
use Session;

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

    public function postLogin(Request $request)
    {
        $code = $request->input('code');
        $numele = $request->input('dni');
        $voter = Voter::Enable()->Pending()->Pin($code)->Numele($numele)->first();
        $success = false;
        $msj = 'código UCE incorrecto: ';        
        
        if(!empty($voter)){
            
            $voter_session = new \stdClass();
            $voter_session->name = $voter->name;
            $voter_session->lastName = $voter->lastname_first.' '.$voter->lastname_second;
            $voter_session->numElectoral = $voter->num_ele;
            $voter_session->scopeCharter = $voter->scope_charter;

            Session::put('voter', $voter_session);            
            $success = true;
            $get_voter = Session::get('voter');
            $msj = 'Elecciones: '.$get_voter->name.' '.$get_voter->lastName;
        }       

        $jResponse = [
            'success' => $success,
            'message' => $msj,
            'url' => route('vote.index')
        ];

        return response()->json($jResponse);
    }

    public function getLogout()
    {
        return redirect()->route('login');
    }

}
