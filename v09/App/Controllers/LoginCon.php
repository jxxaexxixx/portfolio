<?php

namespace App\Controllers;

use \Core\View;
use App\Models\ClientMo;

class LoginCon extends \Core\Controller
{
    public function Render()
    {
        View::renderTemplate('page/login/login.html');
    }


    public function ChatStart($data=null)
    {
        if(!$_POST['name']) $this::errExport('닉네임을 입력해 주세요.');
        $name = $_POST['name'];
        setcookie('name', $name, time() + 36000, "/");

        $clientChk = ClientMo::ClientNameChk($name);
        if ($clientChk) $this::errExport('중복된 닉네임입니다.');

        // 채팅에 쓸 룸네임 발번
        $rn = $this->MakeRandomString();

        // 매니저IDX 찾기
        $GlobalsValGroup = new \Core\GlobalsVariable;
        $managerIdx      = $GlobalsValGroup->GetGlobals('managerIdx');
        $create_time = date('Y-m-d H:i:s');

        // 디비 인서트
        $db     = static::GetMainDB();
        $dbName = self::MainDBName;
        $insert = $db->prepare("
            INSERT INTO `{$dbName}`.`client`
                ( name, rn, type, manager_idx, create_time )
            VALUES
                ( :name, :rn, :type, :manager_idx, :create_time )
        ");

        $insert->bindValue(':name',         $name,        \PDO::PARAM_STR);
        $insert->bindValue(':rn',           $rn,          \PDO::PARAM_STR);
        $insert->bindValue(':type',         2,            \PDO::PARAM_INT);
        $insert->bindValue(':manager_idx',  $managerIdx,  \PDO::PARAM_INT);
        $insert->bindValue(':create_time',  $create_time, \PDO::PARAM_STR);

        $insert->execute();
        $clientIDX = $db->lastInsertId();


        $msg  = '안녕하세요, ' . $name . ' 님!<br>';
        $msg .= '저희 포트폴리오에 방문해 주셔서 감사해요.<br>';
        $msg .= '우측 내 정보 영역에 듣고 싶은 말을 입력해 저장하시면,<br>';
        $msg .= '저와 대화하실 때마다 따뜻하게 전해드릴게요.<br><br>';
        $msg .= '또한, 자동응답 명령어로 다양한 정보를 확인하실 수 있어요.<br><br>';
        $msg .= '!소개 — 제작자 소개<br>';
        $msg .= '!스택 — 기술 스택 안내<br>';
        $msg .= '!프로젝트 — 참여 프로젝트 목록<br>';
        $msg .= '!성장과정 — 취업 후 성장과정<br>';
        $msg .= '!강점 — 주요 강점 소개<br>';
        $msg .= '!연락처 — 연락 방법 안내<br>';
        $msg .= '!링크 — 외부 링크 모음<br>';
        $msg .= '!포폴 — 포트폴리오 소개';

        $insert2 = $db->prepare("
            INSERT INTO `{$dbName}`.`chat`
                ( client_idx, type, msg, create_time )
            VALUES
                ( :client_idx, :type, :msg, :create_time )
        ");

        $insert2->bindValue(':client_idx',  $clientIDX,     \PDO::PARAM_INT);
        $insert2->bindValue(':type',        1,              \PDO::PARAM_INT);
        $insert2->bindValue(':msg',         $msg,           \PDO::PARAM_STR);
        $insert2->bindValue(':create_time', $create_time,   \PDO::PARAM_STR);
        $insert2->execute();


        $dt     = new \DateTime($create_time);
        $hour   = (int)$dt->format('H');
        $minute = $dt->format('i');
        $prefix = $hour < 12 ? '오전' : '오후';
        $hour12 = $hour % 12;
        if ($hour12 === 0) {
            $hour12 = 12;
        }
        $formatedtime = sprintf('%s %d:%02d', $prefix, $hour12, $minute);


        $dataArr = [
            'name'       => $name,
            'rn'         => $rn,
            'msg'         => $msg,
            'formatted_time'         => $formatedtime,
            'time'         => $create_time,
        ];
        $result = ['result'=>'t','data'=>$dataArr];
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }
}
