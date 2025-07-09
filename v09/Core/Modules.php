<?php

namespace Core;

/**
 * Base model
 *
 * PHP version 7.4
 */
class Modules extends DefinAll
{

    /**
     * Add Modules
     *
     * @return void
     */

    //스프레트시트
    public static function PhpSpreadsheet($data=null)
    {
        require_once(self::DIR_Modules.'Php/PhpSpreadsheet/vendor/autoload.php');
    }

    //Twig 불러오기
    public static function GetTwig($data=null)
    {
        require_once(self::DIR_Modules.'Php/TwigFunction/vendor/autoload.php');
    }


    //Twig 불러오기
    public static function GetTwigEt($data=null)
    {
        require_once(self::DIR_Modules.'Php/TwigExt/vendor/autoload.php');
    }

    //Twig 불러오기
    public static function GetTwigExt($data=null)
    {
    require_once(self::DIR_Modules.'Php/TwigUm/vendor/autoload.php');
    }

    //ImageResize 불러오기
    public static function GetImageResize($data=null)
    {
        require_once(self::DIR_Modules.'Php/PHPImageResize/resize.class.php');
    }



    //예외적인 헬더 생각해 보자
    public static function exceptionHandler($data=null)
    {

    }


}
