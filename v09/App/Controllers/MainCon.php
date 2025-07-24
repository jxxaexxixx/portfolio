<?php

namespace App\Controllers;
use \Core\View;
use App\Models\ManagerMo;


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
            $errMsg='비밀번호를 입력해 주세요.';
            $errOn=$this::errExport($errMsg);
        }

        $code = $_POST['code'];
        $codeIDX = ManagerMo::GetManagerIdx($code);
        if(!isset($codeIDX['idx'])||empty($codeIDX['idx'])){
            $errMsg='비밀번호가 올바르지 않습니다.';
            $errOn=$this::errExport($errMsg);
        }
        $encryptKey=self::encKey;
        $encryptArr    = ['managerIdx'=>$codeIDX['idx']];
		$encryptString = json_encode($encryptArr,JSON_UNESCAPED_UNICODE);
		$encrypt       = $this->Encrypt($encryptString,$encryptKey);

        setcookie('code', $encrypt, time() + 3600, "/");
        $result =['result'=>'t'];
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }


}