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
require_once __DIR__ . '/../entities/experimento.php';

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
            return json_encode(null);
        }
        return json_encode($sessaoAtiva[0]);
    }
    
    public function findExperimentos() {
        return json_encode(InputHelper::utf8ize($this->repository->findExperimentos()));
    }
    
    public function getExperimentoAtivo() {
        return json_encode(InputHelper::utf8ize($this->repository->getExperimentoAtivo()[0]));
    }


    public function startSessao() {
        $token = $this->loginService->getToken();
        if ($token == null) {
            throw new Exception("Token de acesso não encontrado.");
        }

        if (json_decode($this->findSessaoAtiva()) != null) {
            throw new Exception("Já existe uma sessão ativa no momento, tente novamente mais tarde ou agende um horário para utilizar o laboratório.");
        }

        $dtInicio = new DateTime();
        $dtFim = new DateTime("+25 minutes");

        if ($this->repository->startSessao($token->matricula, $dtInicio->format("Y-m-d H:i:s"), $dtFim->format("Y-m-d H:i:s"))) {
            return json_encode(new Sessao($token->matricula, true, $dtInicio->format("Y-m-d H:i:s"), $dtFim->format("Y-m-d H:i:s")));
        }
        return json_encode($token);
    }
    
    public function startExperimento($body) {
        $token = $this->loginService->getToken();
        if ($token == null) {
            throw new Exception("Token de acesso não encontrado.");
        }
        
        if(!is_numeric($body->codigo)) {
            throw new Exception("Código do experimento em formato inválido.");
        }
        
        if(count($this->repository->findExperimentoById($body->codigo)) == 0) {
            throw new Exception("Experimento não encontrado.");
        }
        
        $sessao = json_decode($this->findSessaoAtiva());
        if($sessao->matricula != $token->matricula) {
            throw new Exception("Você não é o usuário da sessão atual.");
        }
        
        $dtInicio = new DateTime();
        $experimentoSessao["cod_sessao"] = $sessao->codigo;
        $experimentoSessao["cod_experimento"] = $body->codigo;
        $experimentoSessao["parametros"] = "";
        $experimentoSessao["dt_inicio"] = $dtInicio->format("Y-m-d H:i:s"); 
        $experimentoSessao["ativo"] = true;
        $this->repository->desabilitaExperimentos();
        $experimentoAtivo = $this->repository->startExperimento($experimentoSessao);
        if ($experimentoAtivo != false) {
            return json_encode(new Experimento($experimentoAtivo, $experimentoSessao["cod_sessao"], $experimentoSessao["cod_experimento"], $experimentoSessao["parametros"], $experimentoSessao["dt_inicio"], $experimentoSessao["ativo"]));
        } else {
            throw new Exception("Não foi possível criar seu novo experimento, erro ao inserir registro.");
        }
    }

}

?>
