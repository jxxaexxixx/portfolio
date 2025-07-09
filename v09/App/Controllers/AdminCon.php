<?php

namespace App\Controllers;

use \Core\View;

class AdminCon extends \Core\Controller
{
    public function Render()
    {
        $renderArr = [
        ];
        View::renderTemplate('page/admin/admin.html', $renderArr);
    }


}