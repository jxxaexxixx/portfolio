<?php

namespace App\Controllers;

use \Core\View;
use App\Models\ClientMo;
use App\Models\AutoAnswerMo;
use App\Models\ChatMo;

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
        if (!isset($_POST['msg']) || empty($_POST['msg'])) {
            $errMsg = '메시지를 입력해 주세요.';
            return $this::errExport($errMsg);
        }
        if (!isset($_POST['rn']) || empty($_POST['rn'])) {
            $errMsg = '잘못된 접근입니다.';
            return $this::errExport($errMsg);
        }

        $msg = $_POST['msg'];
        $rn  = $_POST['rn'];

        // 2) 클라이언트 조회
        $client = ClientMo::GetClient($rn);
        if (!isset($client['idx']) || empty($client['idx'])) {
            $errMsg = '존재하지 않는 회원입니다. 새로고침 후 다시 시도해 주세요.';
            return $this::errExport($errMsg);
        }

        // 3) 시간 포맷 준비
        $create_time = date('Y-m-d H:i:s');
        $formatted   = date('y.m.d H:i', strtotime($create_time));

        $dt     = new \DateTime($create_time);
        $hour   = (int)$dt->format('H');
        $minute = $dt->format('i');
        $prefix = $hour < 12 ? '오전' : '오후';
        $hour12 = $hour % 12 ?: 12;
        $chat_list_time = sprintf('%s %d:%02d', $prefix, $hour12, $minute);

        // 4) 자동응답 메시지 매핑
        $autoResponses = [
    '!소개' => <<<EOD
안녕하세요, 저는 박제이입니다.

항상 밝고 긍정적인 에너지로 팀에 기여하는 것을 중요하게 생각하며,
MBTI는 ESFJ이고, 1997년생으로 만 27세입니다.

부산에서 태어나 4년 전 취업을 위해 서울로 상경했고,
약 6개월간 국비지원 학원에서 개발을 공부한 뒤 한 회사에 입사해 3년간 근무했습니다.
최근 회사의 경영상 어려움으로 갑작스럽게 퇴사하게 되었고,
현재는 새로운 기회를 찾아 이직을 준비하고 있습니다.
EOD,
    '!스택' => <<<EOD
저의 기술 스택은

HTML/CSS, JavaScript, PHP, MySQL, Node.js, React, Git
를 다룰 수 있으며,

특히 SQL 문 작성과 스크립트 구현에 자신이 있습니다.
항상 효율적이고 안정적인 코드를 고민하며, 개발의 품질을 높이는 데 집중합니다.
EOD,
    '!성장과정' => <<<EOD
저는 국비학원에서 웹 퍼블리싱과 UI/UX 기초를 배우며 개발자의 길을 시작했습니다.
2022년 3월 첫 회사에 입사하여 퍼블리셔로 출발 했지만, 다양한 프로젝트를 수행하면서 프론트엔드 및 백엔드 영역까지 폭넓게 경험했습니다.

JavaScript와 jQuery를 활용한 동적 UI 개발, PHP 기반 MVC 아키 텍처를 이용한 서버사이드 로직 구현, SQL 쿼리를 통한 데이터베이스 설계 및 관리를 수행했으며, AJAX와 Fetch API 등 비동기 통신을 통 해 서버와의 실시간 데이터 교환을 구현했습니다. 또한 Node.js 환경에 서 API 서버를 제작하고, 내부 시스템과 연계되는 다양한 API를 직접 설계·개발한 경험이 있습니다.

국비학원에서 익힌 Adobe XD를 바탕으로 Figma를 독학하여 디자인-개발 간 협업을 효율화했고, 프로젝트 기획 단계부터 능동적으로 참여 하여 워크플로우를 개선했습니다. 근무 2년 반이 지나던 2024년 10월, 기존 jQuery + PHP 기반 MVC 협업 체계의 한계를 극복하기 위해 React와 Git을 학습 및 도입하여 신규 프로젝트(한림원 프로젝트)를 성공적으로 마무리하는 데 기여했습니다.
EOD,
    '!강점' => <<<EOD
"저는 문제 해결을 위해 주도적으로 움직이고 실행으로 답을 찾는 개발 자입니다."

3년간 다양한 프로젝트를 경험하면서 단순히 주어진 일만 처리 하기보다, 팀이 더 나은 방향으로 나아가기 위해 무엇을 개선해야 할까 를 고민하고 실천해왔습니다.
그 중 한 예로, 신규 프로젝트의 프레임워크 선정 과정에서 React와 Vue 중 어떤 것이 적합한지 직접 비교·분석했습니다. 각 프레임워크의 장단점을 공부하고 PPT로 정리해 팀에 공유했으며, 최종적으로 React와 Git을 도입해 프로젝트 효율성을 크게 높일 수 있었습니다. 이 경험은 저의 실행력과 팀을 위한 주도적 사고를 잘 보여줍니다.

또한 긍정적인 팀 분위기 형성을 중시합니다. '이바이 프로젝트'에서는
3주 안에 채팅 상담 기능을 완성해야 하는 촉박한 일정 속에서, 백엔드 개발자 1명과 함께 기획 및 스키마를 꼼꼼히 설계해 앱 화면과 관리자 화면을 단기간에 완성했습니다. 덕분에 후속 작업을 맡은 팀원들이 보다 수월하게 프로젝트를 진행할 수 있었습니다.
마지막으로, 피드백을 겸허히 수용하는 태도도 저의 강점입니다. 첫 프로젝트였던 '캠핑장 사이트' 제작 시 외부 일러스트 제작을 진행하며 의사소통 과정에서 절차를 지키지 않아 피드백을 받은 경험이 있습니다.
이를 계기로 작은 결정도 투명하게 공유하는 것이 신뢰와 협업의 시작임을 깨닫고, 이후 팀 내 소통 방식을 개선했습니다.
EOD,
    '!연락처' => <<<EOD
이메일: wpdldlze@gmail.com

전화: 010-3695-5956
EOD,
    '!포폴' => <<<EOD
해당 포트폴리오는 두 명이 함께 약 일주일간 협업하여 제작했으며,
저는 전체 기획과 페이지 구현을 맡았습니다.

jQuery, PHP, Node.js, Socket.IO를 활용해 관리자 페이지와 사용자 페이지 간의 실시간 소통 기능 구현에 중점을 두었으며,
사용자가 메시지를 전송하면 관리자 화면에 즉시 반영되고, 자동응답 처리도 가능하도록 설계했습니다.

프론트엔드와 백엔드가 자연스럽게 연결되어 원활하게 소통되도록 신경 썼습니다.
EOD,
        ];

        // 5) DB 커넥션
        $db     = static::GetMainDB();
        $dbName = self::MainDBName;

        // 6) 결과 데이터 배열
        $dataArr = [];

        // 7) 유저 메시지 저장
        $userType = 2;
        $insUser = $db->prepare("INSERT INTO {$dbName}.chat
            (client_idx, type, msg, create_time)
            VALUES (:client_idx, :type, :msg, :create_time)
        ");
        $insUser->bindValue(':client_idx', $client['idx']);
        $insUser->bindValue(':type',       $userType);
        $insUser->bindValue(':msg',        $msg);
        $insUser->bindValue(':create_time',$create_time);
        $insUser->execute();

        // 8) 유저 메시지 응답 데이터에 추가
        $dataArr[] = [
            'type'           => $userType,
            'msg'            => $msg,
            'formattime'     => $formatted,
            'time'           => $create_time,
            'chat_list_time' => $chat_list_time,
            'rn'             => $rn,
        ];

        // 9) 자동응답 키워드가 있으면 관리자 메시지 저장
        if (isset($autoResponses[$msg])) {
            $adminMsg  = $autoResponses[$msg];
            $adminType = 1;

            $insAdmin = $db->prepare("INSERT INTO {$dbName}.chat
                (client_idx, type, msg, create_time)
                VALUES (:client_idx, :type, :msg, :create_time)
            ");
            $insAdmin->bindValue(':client_idx', $client['idx']);
            $insAdmin->bindValue(':type',       $adminType);
            $insAdmin->bindValue(':msg',        $adminMsg);
            $insAdmin->bindValue(':create_time',$create_time);
            $insAdmin->execute();

            // 관리자 메시지 응답 데이터에 추가
            $dataArr[] = [
                'type'           => $adminType,
                'msg'            => $adminMsg,
                'formattime'     => $formatted,
                'time'           => $create_time,
                'chat_list_time' => $chat_list_time,
                'rn'             => $rn,
            ];

        // 10) 자동응답 키워드가 아니고, 클라이언트 talk 컬럼에 값이 있으면
        } elseif (!empty($client['talk'])) {
            $talkMsg   = $client['talk'];
            $adminType = 1;

            $insTalk = $db->prepare("INSERT INTO {$dbName}.chat
                (client_idx, type, msg, create_time)
                VALUES (:client_idx, :type, :msg, :create_time)
            ");
            $insTalk->bindValue(':client_idx', $client['idx']);
            $insTalk->bindValue(':type',       $adminType);
            $insTalk->bindValue(':msg',        $talkMsg);
            $insTalk->bindValue(':create_time',$create_time);
            $insTalk->execute();

            // talk 메시지 응답 데이터에 추가
            $dataArr[] = [
                'type'           => $adminType,
                'msg'            => $talkMsg,
                'formattime'     => $formatted,
                'time'           => $create_time,
                'chat_list_time' => $chat_list_time,
                'rn'             => $rn,
            ];
        }

        // 11) JSON 출력
        $result = ['result' => 't', 'data' => $dataArr];
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }



}