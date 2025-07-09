<?php

namespace App\Controllers;

use \Core\View;

class MainCon extends \Core\Controller
{
    public function Render()
    {
        $renderArr = [
        ];
        View::renderTemplate('page/main/main.html', $renderArr);
    }


}