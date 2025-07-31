<?php

namespace App\Models;
use PDO;

class BlockedWordsMo extends \Core\Model
{

    public static function BlockDataTable($data = null)
    {
        $db     = static::GetMainDB();
        $dbName = self::MainDBName;
        $Sel = $db->prepare("
            SELECT
                idx,
                words
            FROM `{$dbName}`.`blocked_words`
            WHERE type = 2
        ");
        $Sel->execute();
        $result = $Sel->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
