<?php

namespace App\Controllers;

use \Core\View;

class LoginCon extends \Core\Controller
{
    public function Render()
    {
        $renderArr = [];
        View::renderTemplate('page/login/login.html', $renderArr);
    }

}


