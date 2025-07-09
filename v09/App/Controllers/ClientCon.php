<?php

namespace App\Controllers;

use \Core\View;

class ClientCon extends \Core\Controller
{
    public function Render()
    {
        $renderArr = [
        ];
        View::renderTemplate('page/client/client.html', $renderArr);
    }


}