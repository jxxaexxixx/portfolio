<?php

namespace Core;
use PDO;
/**
 * Application configuration
 *
 * PHP version 7.0
 */
abstract class DefinAll
{
    /* 서비스명은 사용하시는 서비스명을 적어주시면 됩니다 */
    const ServerName = 'portfolio';

    /*------------------------------------------------------------------------------------------------*/

    /*
        key 정보들
    */
    const LoginKey  = '8E418EA7B854DAADRINK68D0713CE35598WATERA96E42FB1DC7998D926906EBA';
    const dataDbKey = '8E418EA7B854DAADRINK68D0713CE35598WATERA96E42FB1DC7A998D926906D4G';
    const encKey    = '8E418EA7B85W59JDRINK68D0713CE35598WATERA96E42FB1DC7A998D926906D4G';
    const ChatKey   = '8E418EA7B854DAADRINK68D0713CE35598WATERA96E42FB1DC7998D926906FBA';

    /*------------------------------------------------------------------------------------------------*/

    /*
        DomainUri = 실제 도메인을 입력해주세요 (실제 페이지에서 $.post의 주소도 똑같습니다)
        CookieDomainUri = 쿠키와 세션을 만들 주소를 입력해주세요
    */
    
    const AppUri     = 'https://www.jjjjjproject.com/';
    const AppIoUri   = 'https://www.jjjjjproject.com/:16000';
    const ChatUri    = 'https://www.jjjjjproject.com/';
    const ChatIoUri  = 'https://www.jjjjjproject.com:17000';

    const StaffUrl  = 'https://www.jjjjjproject.com';
    /*
        모듈경로
    */
    const DIR_Modules = '/portfolio/v09/Modules/';



    /*------------------------------------------------------------------------------------------------*/

    /*
        Twig 경로를 입력해주세요
        ex) const DIR_Twig = '/sendipay/vendor/';
    */
    const DIR_Twig = '/portfolio/v09/vendor';

    /*------------------------------------------------------------------------------------------------*/
    /*
        RouterPackage 실행여부
        Y = 실행 / N = 실행안함
    */
    const RouterDdosBasicStat     = 'Y';
    const RouterDdosWebCookieStat = 'Y';
    const RouterLoginChk          = 'Y';
    const RouterPageTokenChk      = 'Y';

    /*------------------------------------------------------------------------------------------------*/


    /*로그인관련
        Y = 삭제안함 [중복로그인 허용]
        N = 로그인할때(기준컬럼:loginIDX , subUrl) db에 있는 PageToken 데이터 삭제 [중복로그인 금지]
        StLoginCon에서 사용
    */
    const RouterPageDuplicateLogin = 'Y';

    /*------------------------------------------------------------------------------------------------*/

    /*
        error 관련
        ex) const ErrorPage = 'staff/page/error/error.html';
        ex) const ErrorViewIpArr = ['220.85.112.85'];
    */
    const ErrorPage      = 'page/error/error.html';  //에러페이지 경로
    const ErrorViewIpArr = ['59.15.59.7', '1.230.47.123'];        //에러를 볼 수 있는 아이피를 쓰세요. 에러를 차단하고싶으면 빈 배열로 놔주세요.

    /*------------------------------------------------------------------------------------------------*/

    /*------------------------------------------------------------------------------------------------*/
    // 파일관련

    const oriImgPath = '/portfolio/v09/App/Data/1PSJhHuWU17GyZgvAAFf/';  // 원본이미지 저장되는곳
    const tmpPath    = '/portfolio/v09/App/Data/KFB5SyaxBNIX9PJ7AAE5/';  // 파일 임시저장되는곳
    const noImgPath  = '/portfolio/v09/App/Views/page/error/noImg.png';       // 이미지 없을때 임시이미지


    const FileMaxSize    = 5 * 1048576;                  // 이동가능한 한 파일의 최대사이즈
    const FileAbledExt   = ['gif', 'png', 'jpg', 'jpeg'];  // 가능한 확장자
    const FileAbledCount = 1;                              // 한번에 몇개까지 가능한지

    /*------------------------------------------------------------------------------------------------*/

    // 메인 DB 
    const MainDB = [
        'DB_HOST'     => '15.164.32.36',
        'DB_NAME'     => '',
        'DB_USER'     => 'portfolio',
        'DB_PASSWORD' => 'portfolio',
        'SHOW_ERRORS' => true
    ];

    //DB명
    const MainDBName = 'portfolio';

    protected static function GetMainDB()
    {
        static $db = null;
        if ($db === null) {
            $dsn = 'mysql:host=' . self::MainDB['DB_HOST'] . ';dbname=' . self::MainDB['DB_NAME'] . ';charset=utf8';
            $db = new PDO($dsn, self::MainDB['DB_USER'], self::MainDB['DB_PASSWORD']);
            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $db;
    }
}