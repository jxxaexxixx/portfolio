<?php

namespace Core;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GlobalsVariable extends AbstractExtension {

    //트위그 함수 콜 및 확장
    public  function getFunctions(){
        return [
            new TwigFunction('GetGlobals',[$this,'GetGlobals']),

        ];
    }

    public static function GetGlobals($data=null){
        if($data=='DomainUri'){
            return self::ChatUri;
        }
        if($data=='ChatIoUri'){
            return self::ChatIoUri;
        }
        if($data=='StaffUrl'){
            return self::StaffUrl;
        }


        $valName='';
        switch ($data) {
            case 'managerIdx':
                $StgbValCookie = $_COOKIE['code'];
                $encryptKey=self::encKey;
                $StgbValCookieDecr = Controller::Decrypt($StgbValCookie,        $encryptKey);
                $gbCookieVal = json_decode($StgbValCookieDecr,true);
                if($StgbValCookie==""||$StgbValCookie==null){
                    $gbErr = ['result'=>'f','msg'=>'로그인을 해주세요.'];
                    echo json_encode($gbErr,JSON_UNESCAPED_UNICODE);
                    exit();
                }
                $valName=$gbCookieVal['managerIdx'];
            break;
            case 'clientIdx':
                $StgbValCookie = $_COOKIE['client'];
                $encryptKey=self::encKey;
                $StgbValCookieDecr = Controller::Decrypt($StgbValCookie,        $encryptKey);
                $gbCookieVal = json_decode($StgbValCookieDecr,true);
                if($StgbValCookie==""||$StgbValCookie==null){
                    $gbErr = ['result'=>'f','msg'=>'로그인을 해주세요.'];
                    echo json_encode($gbErr,JSON_UNESCAPED_UNICODE);
                    exit();
                }
                $valName=$gbCookieVal['clientIdx'];
            break;
            default:
            break;
        }
        return $valName;
    }

}
