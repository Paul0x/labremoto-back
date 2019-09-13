<?php

/*
 *            __   __   ___        __  ___  __  
 * |     /\  |__) |__) |__   |\/| /  \  |  /  \ 
 * |___ /~~\ |__) |  \ |___  |  | \__/  |  \__/ 
 * =============================================
 * Laboratório Remoto de Robótica Móvel                                           
 * Autor: Paulo Felipe P. Parreira - paulof (at) ufop.edu.br
 * =============================================
 * Arquivo: inputHelper.php
 * Descrição: Helper para ajudar a tratar as entradas do sistema
 */
class InputHelper {
    
    public static function getBodyJson() {
        $input = file_get_contents("php://input");
        return json_decode($input);
    }
    
}
