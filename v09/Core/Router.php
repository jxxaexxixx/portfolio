<?php

namespace Core;
use \Core\Modules;
use \Core\View;

/**
 * Router
 *
 * PHP version 7.0
 */
class Router extends DefinAll
{

    public function RouterPackage($pageArr = [])
    {
        $getUriType = $this->getUriType($pageArr);
        $uri        = $getUriType['uri'];
        $routerType = $getUriType['routerType'];

        print($uri);

        $this->dispatch($getUriType); // 최종 라우터타기
    }

    //이게 페이지인가 controller인가.
    protected function getUriType($pageArr = [])
    {

        $uri=$_SERVER['QUERY_STRING'];
        if ($uri != '') {
            $parts = explode('&', $uri, 2);

            if (strpos($parts[0], '=') === false) {
                $uri = $parts[0];
            } else {
                $uri = '';
            }
        }
        $urlLastStr=substr($uri, -1);
        if($urlLastStr=='/'){//마지막글자가 /일때
            $uri = substr($uri, 0, -1);//'/'지움
        }

        foreach ($pageArr as $key) {//유효성 조사
            if(!isset($key[0]) ||(!isset($key[1]) || empty($key[1])) ||(!isset($key[2]) || empty($key[2]))){
                throw new \Exception("Method $action in controller $controller cannot be called directly - remove the Action suffix to call this method");
                break;
            }
        }

        $goClass='';
        $goFunc='';
        $routerType='con'; //페이지아님

        foreach ($pageArr as $key) { //페이지인지 조사
            $pageName=$key[0];
            $pageClass=$key[1];
            $pageFunc=$key[2];
            if($uri==$pageName){
                //페이지네?
                $goClass=$pageClass;
                $goFunc=$pageFunc;
                $routerType='page';//페이지임
                break;
            }
        }

        if($routerType==='con'){
            // $conShape='{controller}/{do}'; //post

            $banExt = ['php','js','jsp','css','xlsx','xml','exe','com','bat','cmd','html'];
            foreach ($banExt as $key => $value) {
                $pattern = '/\.'.$value.'/';
                if (preg_match($pattern, $uri)) {
                    $memo=$value.' 파일로 직접 접근 시도중입니다.';
                    throw new \Exception("온점이 있다 .php 나 .js등 실파일을 부르려는 시도가 아닐까?".$pattern);
                    // static::BanTableInsertFunc(4,$memo);
                }
            }

            $pattern = '/([a-zA-z]+)\/([a-zA-z]+)/';
            if (!preg_match($pattern, $uri)) {
                throw new \Exception("컨트롤러 형식이 아닙니다.".$uri);
            }

            $urlCut=explode('/',$uri);
            $goClass=$urlCut[0];
            $goFunc=$urlCut[1];

            $pattern = '/Con$/i';
            if (!preg_match($pattern, $goClass)) {
                throw new \Exception("컨트롤러 형식이 아닙니다.");
            }

        }

        $namespace = 'App\Controllers\\';
        $controller=$goClass;
        $controller = $this->convertToStudlyCaps($controller);
        $controller = $namespace . $controller;



        if (!class_exists($controller)) { //그런 클래스는 없는데?
            throw new \Exception("Controller class $controller not found");
        }

        $goFunc = lcfirst($this->convertToStudlyCaps($goFunc));
        // if (preg_match('/do$/i', $goFunc)) { //끝에 do가 있음 직접 클래스 함수를 찾으려는 시도임!
        //    throw new \Exception("Method $goFunc in controller $controller cannot be called directly - remove the Action suffix to call this method");
        // }


        $controllerObj = new $controller();
        // $method = $goFunc . 'Do';
        if (!method_exists($controllerObj, $goFunc)) {
            throw new \Exception("Method $goFunc not found in controller");
        }

        return ['routerType'=>$routerType , 'controller'=>$controller, 'function'=>$goFunc , 'class'=>$goClass,'uri'=>$uri];
    }

    public function dispatch($data)
    {

        $routerType = $data['routerType'];
        $controller = $data['controller'];
        $goFunc     = $data['function'];
        $uri        = $data['uri'];

        $controllerObj = new $controller();
        // $method = $goFunc . 'Do';
        if (!method_exists($controllerObj, $goFunc)) {
            throw new \Exception("Method $goFunc not found in controller");
        }

        $controllerObj->$goFunc();
    }

    public static function goErrorPage($routerType=null)
    {
        if($routerType=='con'){
            $result=['result'=>'f','msg'=>'Your login session has expired'];
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
        }else{
            View::renderTemplate('page/error/error.html');
            exit();
        }
        exit();
    }


    public static function GetLoginChk($routerType=null)
    {
        if(!$_GET['idx']){
            static::goErrorPage($routerType);
        }
        if(!$_COOKIE['chatCookie']){
            static::goErrorPage($routerType);
        }
        $encidx        = $_GET['idx'];
        $encKey        = self::encKey;
        $loginKey      = self::LoginKey;
        $getIDX        = Controller::Decrypt($encidx,$encKey);
        $encryptCookie = $_COOKIE['chatCookie'];
        $decrypt       = Controller::Decrypt($encryptCookie,$loginKey);
        $decryptArr    = json_decode($decrypt,true);
        $cookieIDX     = $decryptArr['idx'];
        $cookieIp      = $decryptArr['ipAddress'];
        if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            static::goErrorPage($routerType);
        }
        $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $ip = $_SERVER['REMOTE_ADDR'];
        if($getIDX!=$cookieIDX){
            static::goErrorPage($routerType);
        }
        if($cookieIp!=$ip){
            static::goErrorPage($routerType);
        }
        return ['result'=>'t'];
    }


    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

}
