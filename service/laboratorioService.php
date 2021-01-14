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
require_once __DIR__ . '/../entities/experimentoApontarParametros.php';
require_once __DIR__ . '/../entities/experimentoTrajetoriaParametros.php';
require_once __DIR__ . '/../entities/experimentoTrajetoriaInstrucao.php';

class LaboratorioService {

    private $repository;
    private $loginService;

    function __construct() {
        $this->repository = new LaboratorioRepository();
        $this->loginService = new LoginService();
    }

    public function getSessaoAtiva() {
        $sessaoAtivaArr = $this->repository->getSessaoAtiva();
        if (!is_array($sessaoAtivaArr) || count($sessaoAtivaArr) == 0) {
            return (null);
        }
        $sessaoAtiva = new Sessao(null, null, null, null);
        $sessaoAtiva->setCodigo($sessaoAtivaArr[0]["codigo"]);
        $sessaoAtiva->setMatricula($sessaoAtivaArr[0]["matricula"]);
        $sessaoAtiva->setDtInicio($sessaoAtivaArr[0]["dt_inicio"]);
        $sessaoAtiva->setDtFim($sessaoAtivaArr[0]["dt_fim"]);
        $sessaoAtiva->setAtivo($sessaoAtivaArr[0]["ativo"]);
        return $sessaoAtiva;
    }

    public function getExperimentos() {
        return json_encode(InputHelper::utf8ize($this->repository->getExperimentos()));
    }

    public function getExperimentoAtivo() {
        $experimentoAtivoArr = $this->repository->getExperimentoAtivo();
        if (count($experimentoAtivoArr) != 1) {
            return null;
        }
        $experimentoAtivoRepo = $experimentoAtivoArr[0];

        $experimentoAtivo = new Experimento();
        $experimentoAtivo->setCodigo($experimentoAtivoRepo["codigo"]);
        $experimentoAtivo->setCodExperimento($experimentoAtivoRepo["cod_experimento"]);
        $experimentoAtivo->setCodSessao($experimentoAtivoRepo["cod_sessao"]);
        $experimentoAtivo->setDtInicio($experimentoAtivoRepo["dt_inicio"]);
        $experimentoAtivo->setParametros($experimentoAtivoRepo["parametros"]);
        $experimentoAtivo->setAtivo($experimentoAtivoRepo["ativo"]);
        $experimentoAtivo->setLabel(utf8_encode($experimentoAtivoRepo["label"]));
        return ($experimentoAtivo);
    }

    public function startSessao() {
        $token = $this->loginService->getToken();
        if ($token == null) {
            throw new Exception("Token de acesso não encontrado.");
        }

        if ($this->getSessaoAtiva() != null) {
            throw new Exception("Já existe uma sessão ativa no momento, tente novamente mais tarde ou agende um horário para utilizar o laboratório.");
        }

        $dtInicio = new DateTime();
        $dtFim = new DateTime("+25 minutes");

        // Desabilita experimentos ativos - se existirem
        $this->repository->desabilitaExperimentos();

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

        if (!is_numeric($body->codigo)) {
            throw new Exception("Código do experimento em formato inválido.");
        }

        if (count($this->repository->getExperimentoById($body->codigo)) == 0) {
            throw new Exception("Experimento não encontrado.");
        }

        $sessao = ($this->getSessaoAtiva());
        if ($sessao->matricula != $token->matricula) {
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
            $experimento = new Experimento($experimentoAtivo, $experimentoSessao["cod_sessao"], $experimentoSessao["cod_experimento"], $experimentoSessao["parametros"], $experimentoSessao["dt_inicio"], $experimentoSessao["ativo"]);
            $this->gerarParametrosExperimentoDefault($experimento);
            return $experimento;
        } else {
            throw new Exception("Não foi possível criar seu novo experimento, erro ao inserir registro.");
        }
    }

    public function setExperimentoParametro($body) {
        $token = $this->loginService->getToken();
        if ($token == null) {
            throw new Exception("Token de acesso não encontrado.");
        }

        $sessao = $this->getSessaoAtiva();
        $experimento = $this->getExperimentoAtivo();
        if ($sessao->matricula != $token->matricula) {
            throw new Exception("Você não é o usuário da sessão atual.");
        }

        if ($experimento->codSessao != $sessao->codigo) {
            throw new Exception("O experimento não faz parte da sessão atual.");
        }

        if ($experimento->ativo == false) {
            throw new Exception("O experimento não está ativo.");
        }

        switch ($experimento->codExperimento) {
            case 1:
                $params = new ExperimentoApontarParametros();
                $params->setCodSessaoExperimento($experimento->codigo);
                $params->setAlgoritmoBusca($body->algoritmoBusca);
                $params->setObstaculos($body->obstaculos);
                $params->setKp($body->kp);
                $params->setKd($body->kd);
                $params->setKi($body->ki);
                $params->setTamanhoMapaBusca(1);
                $params->setTamanhoAreaSeguranca(1);
                $this->validateExperimentoParams($params);
                return $this->repository->updateExperimentoApontarParametro($params);
                break;
            case 2:
                $params = new ExperimentoTrajetoriaParametros();
                $params->setCodSessaoExperimento($experimento->codigo);
                $params->setObstaculos($body->obstaculos);
                $params->setKp($body->kp);
                $params->setKd($body->kd);
                $params->setKi($body->ki);
                $this->validateExperimentoParams($params);
                return $this->repository->updateExperimentoTrajetoriaParametro($params);
                break;
        }
    }

    public function gerarParametrosExperimentoDefault($experimento) {
        switch ($experimento->codExperimento) {
            case 1:
                $params = new ExperimentoApontarParametros();
                $params->setCodSessaoExperimento($experimento->codigo);
                $params->setAlgoritmoBusca(1);
                $params->setObstaculos(true);
                $params->setKp(1);
                $params->setKd(1);
                $params->setKi(1);
                $params->setTamanhoMapaBusca(1);
                $params->setTamanhoAreaSeguranca(1);
                return $this->repository->createExperimentoApontarParametro($params);
                break;
            case 2:
                $params = new ExperimentoTrajetoriaParametros();
                $params->setCodSessaoExperimento($experimento->codigo);
                $params->setObstaculos(true);
                $params->setKp(1);
                $params->setKd(1);
                $params->setKi(1);
                return $this->repository->createExperimentoTrajetoriaParametro($params);
                break;
        }
    }

    public function validateExperimentoParams($params) {
        if (!is_numeric($params->kp) || !is_numeric($params->kd) || !is_numeric($params->ki)) {
            throw new Exception("Os parâmetros do controlador precisam ser numéricos.");
        }
    }

    public function getExperimentoParametros($codExperimento) {
        if (!is_numeric($codExperimento)) {
            throw new Exception("Formato do código do experimento inválido.".$codExperimento);
        }

        $experimento = $this->repository->getSessaoExperimentoById($codExperimento);
        switch ($experimento["cod_experimento"]) {
            case 1:
                $paramArr = $this->repository->getExperimentoApontarParamsByCodSessaoExperimento($codExperimento);
                return new ExperimentoApontarParametros($paramArr["cod_sessao_experimento"],
                        $paramArr["algoritmo_busca"], $paramArr["obstaculos"],
                        $paramArr["kp"], $paramArr["kd"], $paramArr["ki"], 0,
                        0, $paramArr["dt_criacao"]);
            case 2:
                $paramArr = $this->repository->getExperimentoTrajetoriaParamsByCodSessaoExperimento($codExperimento);
                return new ExperimentoTrajetoriaParametros($paramArr["cod_sessao_experimento"],
                        $paramArr["obstaculos"],
                        $paramArr["kp"], $paramArr["kd"], $paramArr["ki"]
                        , $paramArr["dt_criacao"]);
        }
    }

}

?>
