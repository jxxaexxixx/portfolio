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

        $StgbValCookie = $_COOKIE['chatCookie'];
        $staffLoginKey=self::LoginKey;
        $StgbValCookieDecr = Controller::Decrypt($StgbValCookie,$staffLoginKey);
        $gbCookieVal = json_decode($StgbValCookieDecr,true);
        $valName='';
        switch ($data) {
            case 'loginName':
                $valName=$globalName;
            break;
            case 'loginEmail':
            default:
            break;
        }
        return $valName;
    }

}
