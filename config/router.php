<?php

/*
 *            __   __   ___        __  ___  __  
 * |     /\  |__) |__) |__   |\/| /  \  |  /  \ 
 * |___ /~~\ |__) |  \ |___  |  | \__/  |  \__/ 
 * =============================================
 * Laboratório Remoto de Robótica Móvel                                           
 * Autor: Paulo Felipe P. Parreira - paulof (at) ufop.edu.br
 * =============================================
 * Arquivo: router.php
 * Descrição: Arquivo responsável por gerenciar as rotas da API.
 */

require_once("routes.php");
$files = glob(__DIR__ . "/../controller/*.php");
foreach ($files as $file) {
    require($file);
}

class Router {

    public static function init() {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestUrlVars = explode("/", $_GET['vars']);
        Router::route($requestUrlVars, $method);
    }

    private static function route($requestUrlVars, $method) {
        try {
            $route = Routes::$routes[$requestUrlVars[0]];
            $routeClass = $route[0];
            $class = new ReflectionClass($routeClass);
            $obj = $class->newInstance();

            if (!key_exists(1, $requestUrlVars)) {
                $requestUrlVars[1] = "";
            }
            $method = $class->getMethod($route[1][$method][$requestUrlVars[1]][0]);
            $method->invoke($obj);
        } catch (Exception $ex) {
            echo json_encode(["status" => 404, "error" => "Rota não encontrada"]);
        }
        //$class = new ReflectionClass($requestUrlVars[0]);
    }

}

?>
