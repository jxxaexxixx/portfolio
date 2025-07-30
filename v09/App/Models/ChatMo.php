<?php

namespace App\Models;
use PDO;

class ChatMo extends \Core\Model
{
    public static function GetChat($data = null)
    {
        $client_idx=$data;
        $db     = static::GetMainDB();
        $dbName = self::MainDBName;
       $Sel = $db->prepare("
            SELECT
                idx,
                type,
                msg,
                create_time
            FROM `{$dbName}`.`chat`
            WHERE client_idx = :client_idx
        ");
        $Sel->bindValue(':client_idx', (int)$client_idx, PDO::PARAM_INT);
        $Sel->execute();
        $result = $Sel->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}
