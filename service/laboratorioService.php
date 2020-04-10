<?php

/*
 *            __   __   ___        __  ___  __  
 * |     /\  |__) |__) |__   |\/| /  \  |  /  \ 
 * |___ /~~\ |__) |  \ |___  |  | \__/  |  \__/ 
 * =============================================
 * Laboratório Remoto de Robótica Móvel                                           
 * Autor: Paulo Felipe P. Parreira - paulof (at) ufop.edu.br
 * =============================================
 * Arquivo: laboratorioService.php
 * Descrição: Service com as regras de gerais do laboratório.
 */

require_once __DIR__ . '/../repository/laboratorioRepository.php';

class LaboratorioService {

    private $repository;
    
    function __construct() {
        $this->repository = new LaboratorioRepository();        
    }
    
    public function findSessaoAtiva() {
        return json_encode($this->repository->findSessaoAtiva()[0]);
    }

}

?>
