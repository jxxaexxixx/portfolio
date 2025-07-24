<?php

namespace App\Models;
use PDO;

class ClientMo extends \Core\Model
{

    public static function GetClient($data = null)
    {
        $client_idx = $data;
        $db        = static::GetMainDB();
        $dbName    = self::MainDBName;
        $Sel = $db->prepare("SELECT
            idx,
            rn
        FROM $dbName.client
        WHERE idx = :client_idx
        ");
        $Sel->bindValue(':client_idx', (int)$client_idx, PDO::PARAM_INT);
        $Sel->execute();
        $result = $Sel->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
