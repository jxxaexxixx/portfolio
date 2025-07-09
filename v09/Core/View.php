<?php

namespace Core;
/**
 * View
 *
 * PHP version 7.0
 */
class View extends DefinAll
{


    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;
        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
        }
         //글로벌 변수 추가
        $GlobalsValLode =new GlobalsVariable();

        // print_r($tokenFunc);
        // exit();
        $twig->addExtension($GlobalsValLode);
        echo $twig->render($template, $args);
    }
}