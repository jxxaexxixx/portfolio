<?php

namespace App\Controllers;

use \Core\View;
use App\Models\ClientMo;

class LoginCon extends \Core\Controller
{
    public function Render()
    {
        
        $GlobalsValGroup = new \Core\GlobalsVariable;
        $managerIdx = $GlobalsValGroup->GetGlobals('managerIdx');
        $renderArr = [];

        View::renderTemplate('page/login/login.html', $renderArr);
    }


    public function ChatStart($data=null)
    {
        if(!$_POST['name']) $this::errExport('메시지를 입력해 주세요.');
        $name = $_POST['name'];

        $clientChk = ClientMo::ClientNameChk($name);
        if ($clientChk) $this::errExport('중복된 닉네임입니다.');

        // 채팅에 쓸 룸네임 발번
        
        $rn = $this->MakeRandomString();

        // 매니저IDX 찾기
        $GlobalsValGroup = new \Core\GlobalsVariable;
        $managerIdx      = $GlobalsValGroup->GetGlobals('managerIdx');
        
        // 디비 인서트
        $db     = static::GetMainDB();
        $dbName = self::MainDBName;
        $insert = $db->prepare("INSERT INTO $dbName.client
            (
                name,
                rn,
                manager_idx
            )
            VALUES
            (
                :name,
                :rn,
                :manager_idx
            )
        ");
        $insert->bindValue(':name', $name);
        $insert->bindValue(':rn', $rn);
        $insert->bindValue(':manager_idx',  $managerIdx);
        $insert->execute();
        $chatIDX = $db->lastInsertId();

        setcookie('name', $name, time() + 36000, "/");

        $dataArr = [
            'name'       => $name,
            'rn'         => $rn
        ];
        $result = ['result'=>'t','data'=>$dataArr];
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }
}
