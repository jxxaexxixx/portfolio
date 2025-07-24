<?php

namespace App\Controllers;

use \Core\View;

class LoginCon extends \Core\Controller
{
    public function Render()
    {
        
        $GlobalsValGroup = new \Core\GlobalsVariable;
        $managerIdx = $GlobalsValGroup->GetGlobals('managerIdx');
        $renderArr = [];

        View::renderTemplate('page/login/login.html', $renderArr);
    }
}
