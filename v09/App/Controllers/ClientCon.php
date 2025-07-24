<?php

namespace App\Controllers;

use \Core\View;
use App\Models\ClientMo;
use App\Models\AutoAnswerMo;

class ClientCon extends \Core\Controller
{
    public function Render()
    {
        $GlobalsValGroup = new \Core\GlobalsVariable;
        $name = $GlobalsValGroup->GetGlobals('clientName');

        $clientChk = ClientMo::ClientNameChk($name);
        if (!$clientChk) $this::errExport('잘못된 접근입니다. 새로고침 후 재시도 해주세요11');
        
        $rn          = $clientChk['rn'];
        $manager_idx = $clientChk['manager_idx'];
        
        $get_data = [
            'manager_idx' => $manager_idx,
            'keyword' => '%{시작}'
        ];
        $msg_data = AutoAnswerMo::GetAutoAnswer($get_data);
        if (!$msg_data) $this::errExport('잘못된 접근입니다. 새로고침 후 재시도 해주세요');

        $msg = str_replace('%{name}', $name, $msg_data['msg']);
        $create_time = date('Y-m-d H:i:s');

        $renderArr = [
            'rn'   => $rn,
            'name' => $name,
            'msg'  => $msg,
            'start_time' => $create_time
        ];
        
        View::renderTemplate('page/client/client.html', $renderArr);
    }


}