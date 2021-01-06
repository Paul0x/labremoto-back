<?php

/*
 *            __   __   ___        __  ___  __  
 * |     /\  |__) |__) |__   |\/| /  \  |  /  \ 
 * |___ /~~\ |__) |  \ |___  |  | \__/  |  \__/ 
 * =============================================
 * Laboratório Remoto de Robótica Móvel                                           
 * Autor: Paulo Felipe P. Parreira - paulof (at) ufop.edu.br
 * =============================================
 * Arquivo: loginService.php
 * Descrição: Service com as regras de negócio do login.
 */

require_once __DIR__ . '/../repository/loginRepository.php';
require_once __DIR__ . '/../lib/php-jwt/src/BeforeValidException.php';
require_once __DIR__ . '/../lib/php-jwt/src/ExpiredException.php';
require_once __DIR__ . '/../lib/php-jwt/src/SignatureInvalidException.php';
require_once __DIR__ . '/../lib/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

class LoginService {

    private $repository;
    
    function __construct() {
        $this->repository = new LoginRepository();        
    }

    public function authUser($login, $password) {

        if (!isset($password) || !isset($login) || trim($login) == "" || trim($password) == "") {
            throw new Exception("Dados necessários não foram preenchidos (1).");
        }
        $user = $this->repository->findUserByIdAndPassword($login, $password);
        if(!is_array($user) || count($user) == 0) {
            throw new Exception("Usuário não existente ou senha inválida (2).");
        }
                $token = array(
            "iss" => Config::$iss,
            "aud" => Config::$aud,
            "data" => array(
                "matricula" => $user[0]["matricula"],
                "nome" => $user[0]["nome"],
                "perfil" => "1"
            )
        );

        $jwt = JWT::encode($token, Config::$key);
        http_response_code(200);

        echo json_encode(
                array(
                    "token" => $jwt
                )
        );
    }

    public static function checkToken() {
        $token = null;
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $matches = array();
            preg_match('/Bearer (.*)/', $headers['Authorization'], $matches);
            if (isset($matches[1])) {
                $token = $matches[1];
            }
            try {
                // decode jwt
                $decoded = JWT::decode($token, Config::$key, array('HS256'));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
    
    public function getToken() {
        $token = null;
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $matches = array();
            preg_match('/Bearer (.*)/', $headers['Authorization'], $matches);
            if (isset($matches[1])) {
                $token = $matches[1];
            }
            try {
                // decode jwt
                $decoded = JWT::decode($token, Config::$key, array('HS256'));
                return $decoded->data;
            } catch (Exception $e) {
                return null;
            }
        }
        return null;
    }

}

?>
