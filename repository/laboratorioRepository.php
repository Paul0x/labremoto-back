<?php

/*
 *            __   __   ___        __  ___  __  
 * |     /\  |__) |__) |__   |\/| /  \  |  /  \ 
 * |___ /~~\ |__) |  \ |___  |  | \__/  |  \__/ 
 * =============================================
 * Laboratório Remoto de Robótica Móvel                                           
 * Autor: Paulo Felipe P. Parreira - paulof (at) ufop.edu.br
 * =============================================
 * Arquivo: loginRepository.php
 * Descrição: Repository para as consultas de login.
 */
require_once(__DIR__ . "/../lib/medoo/medoo.php");

use Medoo\Medoo;

class LaboratorioRepository {

    private $db;

    public function __construct() {
        $this->db = new Medoo(Config::$dbConfiguration);
    }

    public function getSessaoAtiva() {
        $sessao = $this->db->select('sessao', ["codigo", "ativo","dt_inicio", "dt_fim", "matricula"], ["ativo" => true]);
        return $sessao;
    }

    public function startSessao($matricula, $dt_inicio, $dt_fim) {
        if ($this->db->insert("sessao", [
                    "matricula" => $matricula,
                    "ativo" => true,
                    "dt_inicio" => $dt_inicio,
                    "dt_fim" => $dt_fim
                ]) == true) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function startExperimento($experimentoSessao) {
        if ($this->db->insert("sessao_experimento", [
                    "cod_sessao" => $experimentoSessao["cod_sessao"],
                    "cod_experimento" => $experimentoSessao["cod_experimento"],
                    "parametros" => $experimentoSessao["parametros"],
                    "dt_inicio" => $experimentoSessao["dt_inicio"],
                    "ativo" => $experimentoSessao["ativo"]
                ]) == true) {
            return $this->db->id();
        } else {
            return false;
        }
    }

    public function getExperimentos() {
        return $this->db->query('SELECT codigo, label, descricao FROM experimento')->fetchAll();
    }

    public function getExperimentoById($codigo) {
        $experimento = $this->db->select('experimento', ["codigo", "label", "descricao"], ["codigo" => $codigo]);
        return $experimento;
    }

    public function getSessaoExperimentoById($codigo) {
        $experimento = $this->db->select('sessao_experimento', ["codigo", "cod_experimento", "cod_sessao"], ["codigo" => $codigo]);
        return $experimento[0];
    }
    
    public function getExperimentoApontarParamsByCodSessaoExperimento($codigo) {
        $params = $this->db->select('experimento_apontar_parametros',
                ["cod_sessao_experimento", "algoritmo_busca", "kp", "kd", "ki", "obstaculos", "dt_criacao"],
                ["cod_sessao_experimento" => $codigo]);
        return $params[0];
    }  
    
    public function getExperimentoTrajetoriaParamsByCodSessaoExperimento($codigo) {
        $params = $this->db->select('experimento_trajetoria_parametros',
                ["cod_sessao_experimento", "kp", "kd", "ki", "obstaculos", "dt_criacao"],
                ["cod_sessao_experimento" => $codigo]);
        return $params[0];
    }    
    
    public function desabilitaExperimentos() {
        return $this->db->update("sessao_experimento", ["ativo" => false], ["ativo" => true]);
    }
    
    public function getExperimentoAtivo() {
        return $this->db->query('SELECT a.codigo, a.cod_sessao, a.cod_experimento, a.parametros, a.dt_inicio, a.ativo, e.label as label FROM sessao_experimento a INNER JOIN experimento e ON a.cod_experimento = e.codigo WHERE a.ativo = true')->fetchAll();
    }
    
    public function createExperimentoApontarParametro($experimentoApontar) {
        if ($this->db->insert("experimento_apontar_parametros", [
                    "cod_sessao_experimento" => $experimentoApontar->getCodSessaoExperimento(),
                    "algoritmo_busca" => $experimentoApontar->getAlgoritmoBusca(),
                    "obstaculos" => $experimentoApontar->getObstaculos(),
                    "kp" => $experimentoApontar->getKp(),
                    "kd" => $experimentoApontar->getKd(),
                    "ki" => $experimentoApontar->getKi(),
                    "tamanho_mapa_busca" => $experimentoApontar->getTamanhoMapaBusca(),
                    "tamanho_area_seguranca" => $experimentoApontar->getTamanhoAreaSeguranca(),
                ]) == true) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function createExperimentoTrajetoriaParametro($experimentoTrajetoria) {
        if ($this->db->insert("experimento_trajetoria_parametros", [
                    "cod_sessao_experimento" => $experimentoTrajetoria->getCodSessaoExperimento(),
                    "obstaculos" => $experimentoTrajetoria->getObstaculos(),
                    "kp" => $experimentoTrajetoria->getKp(),
                    "kd" => $experimentoTrajetoria->getKd(),
                    "ki" => $experimentoTrajetoria->getKi(),
                ]) == true) {
            return true;
        } else {
            return false;
        }
    }
    
    public function updateExperimentoApontarParametro($experimentoApontar) {
        if ($this->db->update("experimento_apontar_parametros", [
                    "algoritmo_busca" => $experimentoApontar->getAlgoritmoBusca(),
                    "obstaculos" => $experimentoApontar->getObstaculos(),
                    "kp" => $experimentoApontar->getKp(),
                    "kd" => $experimentoApontar->getKd(),
                    "ki" => $experimentoApontar->getKi(),
                    "tamanho_mapa_busca" => $experimentoApontar->getTamanhoMapaBusca(),
                    "tamanho_area_seguranca" => $experimentoApontar->getTamanhoAreaSeguranca(),
                ], ["cod_sessao_experimento" => $experimentoApontar->getCodSessaoExperimento()]) == true) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function updateExperimentoTrajetoriaParametro($experimentoTrajetoria) {
        if ($this->db->update("experimento_trajetoria_parametros", [
                    "obstaculos" => $experimentoTrajetoria->getObstaculos(),
                    "kp" => $experimentoTrajetoria->getKp(),
                    "kd" => $experimentoTrajetoria->getKd(),
                    "ki" => $experimentoTrajetoria->getKi(),
                ], ["cod_sessao_experimento" => $experimentoTrajetoria->getCodSessaoExperimento()]) == true) {
            return true;
        } else {
            return false;
        }
    }
    
    public function deleteInstrucoesByCodSessaoExperimento($codSessaoExperimento) {
        if ($this->db->delete("experimento_trajetoria_instrucoes", ["cod_sessao_experimento" => $codSessaoExperimento]) == true) {
            return true;
        } else {
            return false;
        }
        
    }
    
    public function setExperimentoInstrucao($instrucao) {
        if ($this->db->insert("experimento_trajetoria_instrucoes", [
                    "cod_sessao_experimento" => $instrucao->getCodSessaoExperimento(),
                    "velocidade_linear" => $instrucao->getVelLinear(),
                    "velocidade_angular" => $instrucao->getVelAngular(),
                    "timer" => $instrucao->getTimer()
                ]) == true) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getExperimentoInstrucaoByCodSessaoExperimento($codSessaoExperimento) {
        return $this->db->select("experimento_trajetoria_instrucoes",
                ["codigo", "cod_sessao_experimento", "velocidade_linear", "velocidade_angular",
                    "timer", "dt_criacao", "dt_inicializacao", "dt_finalizacao"],[
                        "cod_sessao_experimento" => $codSessaoExperimento
                ]);
    }

}
?>  
