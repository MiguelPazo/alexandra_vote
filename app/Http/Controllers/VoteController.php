<?php namespace Ale\Http\Controllers;

use Ale\Http\Requests;
use Ale\Http\Controllers\Controller;

use Ale\Organization;
use Ale\Process;
use Ale\Scope_organization;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

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
        $lstCedula = $this->listCedulas();
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
        $lstCedula = $this->listCedulas();
        $varByCedula = 3;
        $success = $this->validateKeysData($lstCedula, $data);

        if ($success) {
            foreach ($lstCedula as $cedula) {
                $dataToValid = [];

                if ($success) {
                    foreach ($data as $key => $value) {
                        $code = substr($key, -2, 2);

                        if ($cedula->code == $code) {
                            //validate ced_{code} with {code}agrupol
                            if (ConstApp::PREF_CED . $code == $key) {
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

            return redirect()->route('vote.confirm');
        } else {
            return redirect()->route('vote.index', [
                'error' => '1'
            ]);
        }
    }

    /**
     * List cedulas for user logged by ubigeo
     *
     * @return array
     */

    public function index2()
    {
        $scopesstring="1,2";
        $scope=explode(config('vote.SEPARATOR'),$scopesstring);
        dd($scope[0].config('vote.SEPARATOR').$scope[1]);
        $scope_org=new Scope_organization();
        $scope_org::query("scope_id","=",$scope[0]);
        $so=$scope_org->get();
    }

    public function listCedulas()
    {
<<<<<<< HEAD
        $lstAgrupol = Organization::get();
        $lstCedula = [];
=======
        $lstAgrupol = Agrupol::get();
        $lstCedula = new Collection();
>>>>>>> f83f09f1f2a4e88f313620d54ac91c8fe0c3001d

        $c1 = new \stdClass();
        $c1->title = 'Cédula 1';
        $c1->code = '01';
        $c1->lstAgrupol = $lstAgrupol;
        $c2 = new \stdClass();
        $c2->title = 'Cédula 2';
        $c2->code = '02';
        $c2->lstAgrupol = $lstAgrupol;
        $c3 = new \stdClass();
        $c3->title = 'Cédula 3';
        $c3->code = '03';
        $c3->lstAgrupol = $lstAgrupol;
        $c4 = new \stdClass();
        $c4->title = 'Cédula 4';
        $c4->code = '04';
        $c4->lstAgrupol = $lstAgrupol;
        $c5 = new \stdClass();
        $c5->title = 'Cédula 5';
        $c5->code = '05';
        $c5->lstAgrupol = $lstAgrupol;

        $lstCedula->add($c1);
        $lstCedula->add($c2);
        $lstCedula->add($c3);
        $lstCedula->add($c4);
        $lstCedula->add($c5);

        return $lstCedula;
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
            $codes[] = ConstApp::PREF_CED . $cedula->code;
            $codes[] = ConstApp::PREF_CED_POSC . $cedula->code;
            $codes[] = ConstApp::PREF_CED_DESC . $cedula->code;
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
        $agrupCode = substr($dataAgrup[ConstApp::PREF_CED . $cedula->code], 2);
        $agrupDesc = $dataAgrup[ConstApp::PREF_CED_DESC . $cedula->code];
        $agrupPosc = $dataAgrup[ConstApp::PREF_CED_POSC . $cedula->code];

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
