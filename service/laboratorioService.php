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
require_once __DIR__ . '/../entities/sessao.php';

class LaboratorioService {

    private $repository;
    private $loginService;

    function __construct() {
        $this->repository = new LaboratorioRepository();
        $this->loginService = new LoginService();
    }

    public function findSessaoAtiva() {
        $sessaoAtiva = $this->repository->findSessaoAtiva();
        if (!is_array($sessaoAtiva) || count($sessaoAtiva) == 0) {
            return null;
        }
        return json_encode($sessaoAtiva[0]);
    }

    public function startSessao() {
        $token = $this->loginService->getToken();
        if ($token == null) {
            throw new Exception("Token de acesso não encontrado.");
        }

        if ($this->findSessaoAtiva() != null) {
            throw new Exception("Já existe uma sessão ativa no momento, tente novamente mais tarde ou agende um horário para utilizar o laboratório.");
        }

        $dtInicio = new DateTime();
        $dtFim = new DateTime("+25 minutes");

        if ($this->repository->startSessao($token->matricula, $dtInicio->format("Y-m-d H:i:s"), $dtFim->format("Y-m-d H:i:s"))) {
            return json_encode(new Sessao($token->matricula, true, $dtInicio->format("Y-m-d H:i:s"), $dtFim->format("Y-m-d H:i:s")));
        }
        return json_encode($token);
    }

}

?>
