<?php

namespace App\Models;
use PDO;

class ClientMo extends \Core\Model
{

    public static function GetClient($data = null)
    {
        $rn = $data;
        $db        = static::GetMainDB();
        $dbName    = self::MainDBName;
        $Sel = $db->prepare("SELECT
            idx,
            rn
        FROM $dbName.client
        WHERE rn = :rn
        ");
        $Sel->bindValue(':rn', (int)$rn, PDO::PARAM_INT);
        $Sel->execute();
        $result = $Sel->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function ClientNameChk($data = null)
    {
        $name = $data;

        $db     = static::GetMainDB();
        $dbName = self::MainDBName;
        $Sel    = $db->prepare("SELECT
            idx,
            rn,
            type,
            manager_idx
        FROM $dbName.client
        WHERE name = :name
        ");
        $Sel->bindValue(':name', $name, PDO::PARAM_STR);
        $Sel->execute();
        $result = $Sel->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function ChatList($data = null)
    {
        $managerIdx = (int)$data;
        $db         = static::GetMainDB();
        $dbName = self::MainDBName;

        $sql = "
            SELECT
                A.idx,
                A.name,
                A.rn,
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
            FROM `{$dbName}`.`client` A
            LEFT JOIN (
                SELECT
                    c1.client_idx,
                    c1.msg,
                    c1.create_time
                FROM `{$dbName}`.`chat` c1
                JOIN (
                    SELECT
                        client_idx,
                        MAX(create_time) AS max_ct
                    FROM `{$dbName}`.`chat`
                    GROUP BY client_idx
                ) sub
                  ON c1.client_idx   = sub.client_idx
                 AND c1.create_time  = sub.max_ct
            ) B                       -- AS 생략
              ON B.client_idx = A.idx
            WHERE A.manager_idx = :manager_idx
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
