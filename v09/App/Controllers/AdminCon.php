<?php

namespace App\Controllers;
use \Core\View;
use App\Models\ChatMo;
use App\Models\ClientMo;


class AdminCon extends \Core\Controller
{
    public function Render()
    {
        $GlobalsValGroup = new \Core\GlobalsVariable;
        $managerIdx = $GlobalsValGroup->GetGlobals('managerIdx');
        $chatList  = ClientMo::ChatList($managerIdx);

        $renderArr = [
            'chatList'=>$chatList,
        ];
        View::renderTemplate('page/admin/admin.html', $renderArr);
    }

    public function GetChat($data=null)
    {
        if(!isset($_POST['rn'])||empty($_POST['rn'])){
            $errMsg='잘못된 접근입니다.';
            $errOn=$this::errExport($errMsg);
        }
        $rn = $_POST['rn'];
        $client = ClientMo::GetClient($rn);
        if(!isset($client['idx'])||empty($client['idx'])){
            $errMsg='잘못된 접근입니다.';
            $errOn=$this::errExport($errMsg);
        }
        $client_idx=$client['idx'];
        $chatList = ChatMo::GetChat($client_idx);

        $dataArr = [
            'userName' => $client['name'],
            'userCreateTime' => $client['create_time'],
            'userTalk' => $client['talk'],
            'chatList' => $chatList,
        ];
        $result =['result'=>'t','data'=>$dataArr];
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    public function Chat($data=null)
    {
        if(!isset($_POST['msg'])||empty($_POST['msg'])){
            $errMsg='메시지를 입력해 주세요.';
            $errOn=$this::errExport($errMsg);
        }
        if(!isset($_POST['rn'])||empty($_POST['rn'])){
            $errMsg='잘못된 접근입니다.';
            $errOn=$this::errExport($errMsg);
        }
        $msg = $_POST['msg'];
        $rn = $_POST['rn'];
        $client = ClientMo::GetClient($rn);
        if(!isset($client['idx'])||empty($client['idx'])){
            $errMsg='잘못된 접근입니다.';
            $errOn=$this::errExport($errMsg);
        }
        $create_time = date('Y-m-d H:i:s');
        $formatted   = date('y.m.d H:i', strtotime($create_time));

        $dt     = new \DateTime($create_time);
        $hour   = (int)$dt->format('H');
        $minute = $dt->format('i');
        $prefix = $hour < 12 ? '오전' : '오후';
        $hour12 = $hour % 12;
        if ($hour12 === 0) {
            $hour12 = 12;
        }
        $chat_list_time = sprintf('%s %d:%02d', $prefix, $hour12, $minute);

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
        $insert->bindValue(':client_idx', $client['idx']);
        $insert->bindValue(':type', $type);
        $insert->bindValue(':msg',  $msg);
        $insert->bindValue(':create_time', $create_time);
        $insert->execute();
        $chatIDX = $db->lastInsertId();

        $dataArr = [
            'type' => $type,
            'formattime' => $formatted,
            'time' => $create_time,
            'chat_list_time' => $chat_list_time,
        ];
        $result =['result'=>'t','data'=>$dataArr];
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    public function ClientDel($data=null)
    {

        if(!isset($_POST['rn'])||empty($_POST['rn'])){
            $errMsg='잘못된 접근입니다.';
            $errOn=$this::errExport($errMsg);
        }
        $rn = $_POST['rn'];
        $type=3;//삭제
        $db        = static::GetMainDB();
        $dbName    = self::MainDBName;
        $update=$db->prepare("UPDATE $dbName.client SET
            type=:type
            WHERE rn=:rn
        ");
        $update->bindValue(':rn', $rn);
        $update->bindValue(':type', $type);
        $update->execute();

        $result =['result'=>'t'];
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    public function ClientDataTable($data=null)
    {
        $GlobalsValGroup = new \Core\GlobalsVariable;
        $managerIdx = $GlobalsValGroup->GetGlobals('managerIdx');
        $clientDataTable =ClientMo::ClientDataTable($managerIdx);
        $result =['result'=>'t','data'=>$clientDataTable];
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }


}