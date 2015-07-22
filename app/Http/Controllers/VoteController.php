<?php namespace Ale\Http\Controllers;

use Ale\Constants\Db;
use Ale\Http\Requests;
use Ale\Http\Controllers\Controller;

use Ale\Organization;
use Ale\Process;
use Ale\Scope_organization;
use Ale\Vote;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Ale\Constants\App;
use PhpSpec\Exception\Exception;

class VoteController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $voter = $this->request->session()->get('voter');
        $lstCedula = $this->listCedulas($voter->scopeCharter);
        $error = $this->request->get('error', 0);
        $message = '';

        if ($error == 1) {
            $message = 'Ha ocurrido un intento de cambio de voto, vuelva a intentarlo por favor!';
        }

        return view('vote.index')
            ->with('lstCedula', $lstCedula)
            ->with('message', $message);
    }

    public function confirm()
    {
        return view('vote.confirm');
    }

    public function save()
    {
        $data = $this->request->all();
        $voter = $this->request->session()->get('voter');
        $lstCedula = $this->listCedulas($voter->scopeCharter);
        $varByCedula = 3;
        $dataCed = [];
        $success = $this->validateKeysData($lstCedula, $data);


        if ($success) {
            foreach ($lstCedula as $cedula) {
                $dataToValid = [];

                if ($success) {
                    foreach ($data as $key => $value) {
                        $code = substr($key, -2, 2);

                        if ($cedula->code == $code) {
                            //validate ced_{code} with {code}agrupol
                            if (App::PREF_CED . $code == $key) {
                                if ($value != '') {
                                    $codeInValue = substr($value, 0, 2);

                                    if ($code != $codeInValue) {
                                        $success = false;
                                    }
                                }
                            }

                            $dataToValid[$key] = $value;

                            if (count($dataToValid) == $varByCedula) {
                                if (!$this->validateAgrupolDetail($cedula, $dataToValid)) {
                                    $success = false;
                                }
                                $dataToValid['code'] = $code;
                                $dataCed[] = $dataToValid;
                                break;
                            }
                        }
                    }
                } else {
                    break;
                }
            }
        }

        if ($success) {
            //without transaction yet
            try {
                dd($dataCed);

                foreach ($dataCed as $index => $vote) {
                    $voteToEncrypt = $vote[App::PREF_CED . $vote['code']];

                    Vote::create([
                        'vote' => $this->encrypt($voteToEncrypt),
                        'scope_charter' => $voter->scopeCharter,
                        'election_code' => $vote['code']
                    ]);
                }
            } catch (Exception $ex) {
                $success = false;
            }
        }

        if ($success) {
            return redirect()->route('vote.confirm');
        } else {
            return redirect()->route('vote.index', [
                'error' => '1'
            ]);
        }
    }

    public function encrypt($vote)
    {
        //ENCRYPT
        return $vote;
    }

    /**
     * List cedulas for user logged by ubigeo
     *
     * @return array
     */

    public function listCedulas($scopesString)
    {
        //$scope=explode(config('vote.SEPARATOR'),$scopesstring);
        //$so = Scope_organization::Cedula($scopesstring)->with('organization')->with('scope')->with('election')->get();
        //$so2=$so->groupBy('election_code');
        $so = Scope_organization::Cedula($scopesString)->with('organization')->with('scope')->with('election')->get();
        $so2 = $so->groupBy('election_code');
        $tudo = "";

        $lstCedula = new Collection();
        foreach ($so2 as $post) {
            $c1 = new \stdClass();
            $c1->title = $post[0]->election->description;
            $c1->code = $post[0]->election->code;
            $group = new Collection();
            foreach ($post as $item) {
                //$c1->title = $post[2]->election->description;
                //$c1->code = $post->election->code;
                //$c1->lstAgrupol= $post->organization;
                $group->add($item->organization);
                //dd($c1->title);
                //dd($group);
                //$lstCedula->add($c1);
            }
            $c1->lstAgrupol = $group;
            $lstCedula->add($c1);
        }
        //dd($lstCedula);
        return ($lstCedula);
        //dd($so2);
    }

    /**
     * Validate all required field to all cedulas
     *
     * @param $lstCedula
     * @param $data
     * @return bool
     */
    public function validateKeysData($lstCedula, $data)
    {
        $codes = [];
        $count = 0;

        foreach ($lstCedula as $cedula) {
            $codes[] = App::PREF_CED . $cedula->code;
            $codes[] = App::PREF_CED_POSC . $cedula->code;
            $codes[] = App::PREF_CED_DESC . $cedula->code;
        }

        foreach ($codes as $key => $value) {
            if (array_key_exists($value, $data)) {
                $count++;
            }
        }

        if (count($codes) == $count) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate code, description and order from request with collection db
     *
     * @param $cedula
     * @param $dataAgrup
     * @return bool
     */
    public function validateAgrupolDetail($cedula, $dataAgrup)
    {
        $agrupCode = substr($dataAgrup[App::PREF_CED . $cedula->code], 2);
        $agrupDesc = $dataAgrup[App::PREF_CED_DESC . $cedula->code];
        $agrupPosc = $dataAgrup[App::PREF_CED_POSC . $cedula->code];

        if ($agrupCode) {
            $agrup = $cedula->lstAgrupol->filter(function ($item) use ($agrupCode) {
                return $item->code == $agrupCode;
            })->first();

            if ($agrup != null) {
                if ($agrup->description == $agrupDesc) {
//                            if($agrupPosc)
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else if ($agrupDesc == '' && $agrupPosc == '') {
            return true;
        } else {
            return false;
        }
    }
}
