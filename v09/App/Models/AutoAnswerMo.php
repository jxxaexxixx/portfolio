<?php

namespace App\Models;
use PDO;

class AutoAnswerMo extends \Core\Model
{
    public static function GetAutoAnswer($data = null)
    {
        $manager_idx = $data['manager_idx'];
        $keyword     = $data['keyword'];

        $db     = static::GetMainDB();
        $dbName = self::MainDBName;
        $Sel    = $db->prepare("SELECT
            idx,
            msg
        FROM $dbName.auto_answer
        WHERE manager_idx = :manager_idx
        AND keyword = :keyword
        ");
        $Sel->bindValue(':manager_idx', $manager_idx, PDO::PARAM_STR);
        $Sel->bindValue(':keyword', $keyword, PDO::PARAM_STR);
        $Sel->execute();
        $result = $Sel->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function GetAutoAnswerKeyword($data = null)
    {
        $manager_idx = $data['manager_idx'];
        $db     = static::GetMainDB();
        $dbName = self::MainDBName;
        $Sel    = $db->prepare("SELECT
            idx,
            keyword
        FROM $dbName.auto_answer
        WHERE manager_idx = :manager_idx
        ");
        $Sel->bindValue(':manager_idx', $manager_idx, PDO::PARAM_STR);
        $Sel->execute();
        $result = $Sel->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
