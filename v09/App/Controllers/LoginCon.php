<?php

namespace App\Controllers;

use \Core\View;
use \Core\GlobalsVariable;

class LoginCon extends \Core\Controller
{
    public function Render()
    {
        $codeIDX = GlobalsVariable::GetGlobals('codeIDX');

        $renderArr = [];

        View::renderTemplate('page/login/login.html', $renderArr);
    }



}


