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
        $managerIdx=$data;
        $db     = static::GetMainDB();
        $dbName = self::MainDBName;
       $Sel = $db->prepare("
            SELECT
                idx,
                name,
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
