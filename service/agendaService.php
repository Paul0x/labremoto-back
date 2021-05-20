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

require_once __DIR__ . '/../repository/agendaRepository.php';
require_once __DIR__ . '/../service/loginService.php';
require_once __DIR__ . '/../entities/sessao.php';

class AgendaService {

    private $repository;
    private $loginService;

    function __construct() {
        $this->repository = new AgendaRepository();
        $this->loginService = new LoginService();
    }
    
    function listAgendaUsuario() {
        $token = $this->loginService->getToken();
        if($token == null) {
            throw new Exception("Nenhuma usuário ativo encontrado.");
        }
        return $this->repository->listAgendaUsuario($token->matricula);
    }
    
    function listAgendaFull($dtInicio, $dtFim) {
        
        $format = 'Y-m-d H:i:s';
        $dtInicioFrmt= DateTime::createFromFormat($format, $dtInicio." 00:00:00");
        $dtFimFrmt= DateTime::createFromFormat($format, $dtFim." 23:59:59");
        return $this->repository->listAgendaAllBetweenDates($dtInicioFrmt->format('Y-m-d H:i:s'), $dtFimFrmt->format('Y-m-d H:i:s'));
    }

}

?>
