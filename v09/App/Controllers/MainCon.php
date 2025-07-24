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

    public function CodeInsert($data=null)
    {
        if(!isset($_POST['code'])||empty($_POST['code'])){
            $errMsg='code 정보가 없습니다.';
            $errOn=$this::errExport($errMsg);
        }

        $code=$_POST['code'];
        $data=[
            'code'=>$code,
        ];

        $result =['result'=>'t','data'=>$data];
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }


}