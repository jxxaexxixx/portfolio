<?php

namespace App\Models;
use PDO;

class ClientMo extends \Core\Model
{

    public static function GetClient($rn = null)
    {
        $db      = static::GetMainDB();
        $dbName  = self::MainDBName;

        $sql = "
            SELECT
                `idx`,
                `name`,
                `talk`,
                DATE_FORMAT(`create_time`, '%Y-%m-%d %H:%i') AS `create_time`
            FROM `{$dbName}`.`client`
            WHERE `rn` = :rn
                AND `type` = 2
            LIMIT 1
        ";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':rn', $rn, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function ClientNameChk($data = null)
    {
        $name    = $data;
        $db      = static::GetMainDB();
        $dbName  = self::MainDBName;

        $sql = "
            SELECT
                `idx`,
                `rn`,
                `type`,
                `manager_idx`
            FROM `{$dbName}`.`client`
            WHERE `name` = :name
            LIMIT 1
        ";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function ChatList($data = null)
    {
        $managerIdx = (int)$data;
        $db         = static::GetMainDB();
        $dbName     = self::MainDBName;

        $sql = "
            SELECT
                A.idx,
                A.name,
                A.rn,

                -- raw create_time
                B.create_time AS time,

                -- 포맷된 시간
                CASE
                    WHEN DATE(B.create_time) = CURRENT_DATE() THEN
                        CONCAT(
                            IF(HOUR(B.create_time) < 12, '오전 ', '오후 '),
                            LPAD(
                                CASE
                                    WHEN HOUR(B.create_time) % 12 = 0 THEN 12
                                    ELSE HOUR(B.create_time) % 12
                                END
                            , 2, '0'),
                            ':',
                            LPAD(MINUTE(B.create_time), 2, '0')
                        )
                    WHEN YEAR(B.create_time) = YEAR(CURRENT_DATE()) THEN
                        CONCAT(
                            MONTH(B.create_time), '월',
                            DAY(B.create_time), '일'
                        )
                    ELSE DATE_FORMAT(B.create_time, '%Y.%m.%d')
                END AS formatted_time,

                B.msg AS last_msg

            FROM `{$dbName}`.`client` AS A

            /*
            * 최신 메시지 한 건만 idx 기준으로 뽑아서 조인
            * (서브쿼리 내부에 LIMIT 1 이 보장되므로 중복이 절대 없습니다)
            */
            LEFT JOIN `{$dbName}`.`chat` AS B
            ON B.idx = (
                SELECT C.idx
                FROM `{$dbName}`.`chat` AS C
                WHERE C.client_idx = A.idx
                ORDER BY C.create_time DESC, C.idx DESC
                LIMIT 1
            )

            WHERE A.manager_idx = :manager_idx
            AND A.type        = 2

            ORDER BY B.create_time DESC
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':manager_idx', $managerIdx, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ClientDataTable($data = null)
    {
        $managerIdx=$data;
        $db     = static::GetMainDB();
        $dbName = self::MainDBName;
       $Sel = $db->prepare("
            SELECT
                idx,
                name,
                rn,
                CASE `type`
                    WHEN 2 THEN '정상'
                    WHEN 3 THEN '삭제'
                    ELSE '기타'
                END AS status,
                create_time,
                talk
            FROM `{$dbName}`.`client`
            WHERE manager_idx = :manager_idx
        ");
        $Sel->bindValue(':manager_idx', (int)$managerIdx, PDO::PARAM_INT);
        $Sel->execute();
        $result = $Sel->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
