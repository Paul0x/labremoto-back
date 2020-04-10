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

    public function authUser($login, $password) {

        if (!isset($password) || !isset($login)) {
            throw new Exception("001 - Login ou senha não informados.");
        }
        $token = array(
            "iss" => Config::$iss,
            "aud" => Config::$aud,
            "data" => array(
                "user" => $login
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

}

?>
