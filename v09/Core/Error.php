<?php

namespace Core;
use Core\Controller;

/**
 * Error and exception handler
 *
 * PHP version 7.0
 */
class Error extends DefinAll
{

    /**
     * Error handler. Convert all errors to Exceptions by throwing an ErrorException.
     *
     * @param int $level  Error level
     * @param string $message  Error message
     * @param string $file  Filename the error was raised in
     * @param int $line  Line number in the file
     *
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler.
     *
     * @param Exception $exception  The exception
     *
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        // Code is 404 (not found) or 500 (general error)
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);
        $errClass = get_class($exception);
        $errMsg = $exception->getMessage();
        $errStack = $exception->getTraceAsString();
        $errPath = $exception->getFile() .'('.$exception->getLine().')';

        $ErrorViewIpArr=self::ErrorViewIpArr;
        $coreController=new \Core\Controller;
        $myIp=$coreController->GetIPaddress();

        $allowStat  = 0;
        $allowIpTxt = '';
        foreach ($ErrorViewIpArr as $key) {
            if($myIp == $key){
                $allowStat = 1;
            }
            $hideIp = substr($key, 0, 7) . str_repeat('*', strlen($key) - 7);
            if($allowIpTxt !== ''){
                $allowIpTxt .= ','.$hideIp;
            }else{
                $allowIpTxt .= $hideIp;
            }
        }

        $errArr=['cls'=>$errClass, 'msg'=>$errMsg, 'sta'=>$errStack,'path'=>$errPath , 'allowStat'=>$allowStat, 'allowIpTxt'=>$allowIpTxt];
        print_r($errArr);
        exit;

        View::renderTemplate(self::ErrorPage,$errArr);
    }



}
