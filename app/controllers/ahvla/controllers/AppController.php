<?php

namespace ahvla\controllers;

use View;
use Controller;
use Redirect;
use Response;
use Illuminate\Support\Facades\Input;

use ahvla\entity\coord\OSRef;

/*
 * Top level action methods
 */
class AppController extends Controller
{
    protected $submission;

    public function __construct()
    {

    }

    public function googleLatLongAction()
    {
        $input = Input::all();

        // default to middle england Coton-in-the-Elms
        $eastings = isset($input['eastings'])?$input['eastings']:'424374';
        $northings = isset($input['northings'])?$input['northings']:'315345';

        $os1 = new OSRef($eastings,$northings);
        $ll1 = $os1->toLatLng();
        $ll1->OSGB36ToWGS84();

        return Response::json(['lat'=>$ll1->lat,'lng'=>$ll1->lng]);
    }

    public function uiSampleAction()
    {
        return View::make('ui-sample', array());
    }
}
