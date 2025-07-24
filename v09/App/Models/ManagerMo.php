<?php

namespace App\Models;
use PDO;

class ManagerMo extends \Core\Model
{

    public static function GetManagerIdx($data = null)
    {
        $code = $data;
        $db        = static::GetMainDB();
        $dbName    = self::MainDBName;
        $Sel = $db->prepare("SELECT
            idx
        FROM $dbName.manager
        WHERE code = :code
        ");
        $Sel->bindValue(':code', $code, PDO::PARAM_STR);
        $Sel->execute();
        $result = $Sel->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
