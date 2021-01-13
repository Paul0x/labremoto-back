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

    public function findSessaoAtiva() {
        $sessao = $this->db->select('sessao', ["ativo", "dt_fim", "matricula", "codigo"], ["ativo" => true]);
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

    public function findExperimentos() {
        return $this->db->query('SELECT codigo, label, descricao FROM experimento')->fetchAll();
    }

    public function findExperimentoById($codigo) {
        $experimento = $this->db->select('experimento', ["codigo", "label", "descricao"], ["codigo" => $codigo]);
        return $experimento;
    }
    
    
    public function desabilitaExperimentos() {
        return $this->db->update("sessao_experimento", ["ativo" => false], ["ativo" => true]);
    }
    
    public function getExperimentoAtivo() {
        return $this->db->query('SELECT a.codigo, a.cod_sessao, a.cod_experimento, a.parametros, a.dt_inicio, a.ativo, e.label as label FROM sessao_experimento a INNER JOIN experimento e ON a.cod_experimento = e.codigo WHERE a.ativo = true')->fetchAll();
    }
    
    

}
?>  
