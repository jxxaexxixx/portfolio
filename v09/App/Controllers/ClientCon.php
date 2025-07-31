<?php

namespace App\Controllers;

use \Core\View;
use App\Models\ClientMo;
use App\Models\AutoAnswerMo;
use App\Models\ChatMo;
use App\Models\BlockedWordsMo;

class ClientCon extends \Core\Controller
{
    public function Render()
    {
        $GlobalsValGroup = new \Core\GlobalsVariable;
        $name = $GlobalsValGroup->GetGlobals('clientName');
        $managerIdx = $GlobalsValGroup->GetGlobals('managerIdx');
        $arr = [
            'name'=>$name,
            'manager_idx'=>$managerIdx,
        ];


        $clientChk = ClientMo::ClientNameChk($arr);
        if (!$clientChk) $this::errExport('잘못된 접근입니다. 새로고침 후 재시도 해주세요11');

        $client_idx=$clientChk['idx'];
        $rn=$clientChk['rn'];
        $talk=$clientChk['talk'];
        $chatList = ChatMo::GetChat($client_idx);

        $renderArr = [
            'rn'   => $rn,
            'name' => $name,
            'talk' => $talk,
            'chatList' => $chatList
        ];

        View::renderTemplate('page/client/client.html', $renderArr);
    }

    public function Chat($data = null)
    {
        // 1) 파라미터 유효성 검사
        if (empty($_POST['msg'])) {
            return $this::errExport('메시지를 입력해 주세요.');
        }
        if (empty($_POST['rn'])) {
            return $this::errExport('잘못된 접근입니다.');
        }

        // 2) 매니저 인덱스 조회
        $GlobalsValGroup = new \Core\GlobalsVariable;
        $managerIdx      = $GlobalsValGroup->GetGlobals('managerIdx');

        // 3) 원본 메시지, 채팅방 RN
        $rawMsg = $_POST['msg'];
        $rn     = $_POST['rn'];

        // 4) 금지어 필터링
        $blockedWords = BlockedWordsMo::BlockDataTable();  // type = 2 금지어
        $msg = $rawMsg;
        foreach ($blockedWords as $bw) {
            if (!empty($bw['words'])) {
                $msg = str_replace($bw['words'], '***', $msg);
            }
        }

        // 5) 클라이언트 조회
        $client = ClientMo::GetClient($rn);
        if (empty($client['idx'])) {
            return $this::errExport('존재하지 않는 회원입니다.');
        }

        // 6) 시간 포맷 준비
        $now            = new \DateTime();
        $create_time    = $now->format('Y-m-d H:i:s');
        $formatted      = $now->format('y.m.d H:i');
        $hour           = (int)$now->format('H');
        $minute         = $now->format('i');
        $prefix         = $hour < 12 ? '오전' : '오후';
        $hour12         = $hour % 12 ?: 12;
        $chat_list_time = sprintf('%s %d:%02d', $prefix, $hour12, $minute);

        // 7) DB 연결, 응답 데이터 배열 준비
        $db       = static::GetMainDB();
        $dbName   = self::MainDBName;
        $dataArr  = [];

        // 8) 유저 메시지 저장
        $insUser = $db->prepare("
            INSERT INTO {$dbName}.chat
                (client_idx, type, msg, create_time)
            VALUES
                (:client_idx, 2, :msg, :ct)
        ");
        $insUser->execute([
            ':client_idx' => $client['idx'],
            ':msg'        => $msg,
            ':ct'         => $create_time,
        ]);
        $dataArr[] = [
            'type'           => 2,
            'msg'            => $msg,
            'formattime'     => $formatted,
            'time'           => $create_time,
            'chat_list_time' => $chat_list_time,
            'rn'             => $rn,
        ];

        // 9) 자동응답 키워드 조회 (DB)
        $keywords = AutoAnswerMo::GetAutoAnswerKeyword([
            'manager_idx' => $managerIdx
        ]);

        // 10) 메시지 내 키워드 매칭 후 첫 응답 저장
        $answered = false;
        foreach ($keywords as $kw) {
            $keyword = $kw['keyword'];
            if ($keyword !== '' && mb_strpos($rawMsg, $keyword) !== false) {
                $answer = AutoAnswerMo::GetAutoAnswer([
                    'manager_idx' => $managerIdx,
                    'keyword'     => $keyword
                ]);
                if (!empty($answer['msg'])) {
                    $insAdmin = $db->prepare("
                        INSERT INTO {$dbName}.chat
                            (client_idx, type, msg, create_time)
                        VALUES
                            (:client_idx, 1, :msg, :ct)
                    ");
                    $insAdmin->execute([
                        ':client_idx' => $client['idx'],
                        ':msg'        => $answer['msg'],
                        ':ct'         => $create_time,
                    ]);
                    $dataArr[] = [
                        'type'           => 1,
                        'msg'            => $answer['msg'],
                        'formattime'     => $formatted,
                        'time'           => $create_time,
                        'chat_list_time' => $chat_list_time,
                        'rn'             => $rn,
                    ];
                    $answered = true;
                }
                break;
            }
        }

        // 11) DB 자동응답 없고, 클라이언트 talk 값이 있으면 fallback
        if (! $answered && !empty($client['talk'])) {
            $talkMsg = $client['talk'];
            $insTalk = $db->prepare("
                INSERT INTO {$dbName}.chat
                    (client_idx, type, msg, create_time)
                VALUES
                    (:client_idx, 1, :msg, :ct)
            ");
            $insTalk->execute([
                ':client_idx' => $client['idx'],
                ':msg'        => $talkMsg,
                ':ct'         => $create_time,
            ]);
            $dataArr[] = [
                'type'           => 1,
                'msg'            => $talkMsg,
                'formattime'     => $formatted,
                'time'           => $create_time,
                'chat_list_time' => $chat_list_time,
                'rn'             => $rn,
            ];
        }

        // 12) 결과 JSON 출력
        echo json_encode(['result' => 't', 'data' => $dataArr], JSON_UNESCAPED_UNICODE);
    }

    public function ClientInfoUpdate($data = null)
    {
        if (!isset($_POST['type']) || empty($_POST['type'])) {
            $errMsg = '잘못된 접근입니다.';
            return $this::errExport($errMsg);
        }
        if (!isset($_POST['rn']) || empty($_POST['rn'])) {
            $errMsg = '잘못된 접근입니다.';
            return $this::errExport($errMsg);
        }
        if (!isset($_POST['name']) || empty($_POST['name'])) {
            $errMsg = '이름을 입력해 주세요.';
            return $this::errExport($errMsg);
        }
        $name = $_POST['name'];
        $type = $_POST['type'];
        setcookie('name', $name, time() + 36000, "/");

        if ($type === '3' || $type === 3) {
            $GlobalsValGroup = new \Core\GlobalsVariable;
            $managerIdx      = $GlobalsValGroup->GetGlobals('managerIdx');

            $arr = [
                'name'        => $name,
                'manager_idx' => $managerIdx,
            ];
            $clientChk = ClientMo::ClientNameChk($arr);
            if ($clientChk) {
                $errMsg = '중복된 닉네임입니다.';
                return $this::errExport($errMsg);
            }
        }



        $rn   = $_POST['rn'];
        $talk = isset($_POST['talk']) ? $_POST['talk'] : '';
        $db     = static::GetMainDB();
        $dbName = self::MainDBName;
        $update = $db->prepare("
            UPDATE {$dbName}.client
            SET
                name = :name,
                talk = :talk
            WHERE rn = :rn
        ");
        $update->bindValue(':name', $name, \PDO::PARAM_STR);
        $update->bindValue(':talk', $talk, \PDO::PARAM_STR);
        $update->bindValue(':rn',   $rn,   \PDO::PARAM_STR);
        $update->execute();

        echo json_encode(['result' => 't'], JSON_UNESCAPED_UNICODE);
    }



}