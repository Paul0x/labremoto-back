<?php

/*
 *            __   __   ___        __  ___  __  
 * |     /\  |__) |__) |__   |\/| /  \  |  /  \ 
 * |___ /~~\ |__) |  \ |___  |  | \__/  |  \__/ 
 * =============================================
 * Laboratório Remoto de Robótica Móvel                                           
 * Autor: Paulo Felipe P. Parreira - paulof (at) ufop.edu.br
 * =============================================
 * Arquivo: laboratorioController.php
 * Descrição: Controller utilizado para gerenciar as operações gerais do laboratório.
 */

require_once(__DIR__ . "/../service/laboratorioService.php");

class LaboratorioController {

    private $laboratorioService;

    public function __construct() {
        $this->laboratorioService = new LaboratorioService();
    }
    
    public function findSessaoAtiva() {
        try {
            $body = InputHelper::getBodyJson();
            echo $this->laboratorioService->findSessaoAtiva();
        } catch (Exception $ex) {
            http_response_code(400);
            echo json_encode(["status" => 400, "error" => $ex->getMessage()]);
        }
    }
    
    public function startSessao() {
        try {
            echo $this->laboratorioService->startSessao();            
        } catch (Exception $ex) {
            http_response_code(400);
            echo json_encode(["status" => 400, "error" => $ex->getMessage()]);

        }
        
    }

}

?>
