<?php

namespace App\Controllers;
use \Core\View;
use App\Models\ChatMo;
use App\Models\ClientMo;


class AdminCon extends \Core\Controller
{
    public function Render()
    {
        $renderArr = [
        ];
        View::renderTemplate('page/admin/admin.html', $renderArr);
    }

    public function Chat($data=null)
    {
        if(!isset($_POST['msg'])||empty($_POST['msg'])){
            $errMsg='메시지를 입력해 주세요.';
            $errOn=$this::errExport($errMsg);
        }

        $msg = $_POST['msg'];
        $GlobalsValGroup= new \Core\GlobalsVariable;
        //임시임 닉네임 만들떄 쿠키로 구워서 글로벌변수로 가져와야함
        // $client_idx=$GlobalsValGroup->GetGlobals('clientIdx');
        $client_idx=1;
        if(!isset($client_idx)||empty($client_idx)){
            $errMsg='잘못된 접근입니다.';
            $errOn=$this::errExport($errMsg);
        }
        $chat = ClientMo::GetClient($client_idx);
        if(!isset($chat['rn'])||empty($chat['rn'])){
            $errMsg='잘못된 접근입니다.';
            $errOn=$this::errExport($errMsg);
        }
        $create_time = date('Y-m-d H:i:s');
        $type = 1;
        $db        = static::GetMainDB();
        $dbName    = self::MainDBName;
        $insert =$db->prepare("INSERT INTO $dbName.chat
            (
                client_idx,
                type,
                msg,
                create_time
            )
            VALUES
            (
                :client_idx,
                :type,
                :msg,
                :create_time
            )
        ");
        $insert->bindValue(':client_idx', $client_idx);
        $insert->bindValue(':type', $type);
        $insert->bindValue(':msg',  $msg);
        $insert->bindValue(':create_time', $create_time);
        $insert->execute();
        $chatIDX = $db->lastInsertId();

        $dataArr = [
            'type' => $type,
            'time' => $create_time,
            'rn' => $chat['rn']
        ];
        $result =['result'=>'t','data'=>$dataArr];
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }


}