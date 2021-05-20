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

class AgendaRepository {

    private $db;

    public function __construct() {
        $this->db = new Medoo(Config::$dbConfiguration);
    }

    public function listAgendaUsuario($matricula) {
        return $this->db->query("SELECT a.codigo, a.matricula, a.dt_agendamento FROM agenda a "
                        . "WHERE a.matricula = :matricula ORDER BY a.dt_agendamento DESC", [
                    ":matricula" => $matricula
                ])->fetchAll();
    }

    public function listAgendaAllBetweenDates($dtInicio, $dtFim) {
        return $this->db->query("SELECT u.nome as nome, a.codigo, a.matricula, a.dt_agendamento FROM agenda a JOIN usuario u ON a.matricula = u.matricula "
                        . "WHERE a.dt_agendamento BETWEEN :dtInicio AND :dtFim ORDER BY a.dt_agendamento DESC", [
                    ":dtInicio" => $dtInicio,
                    ":dtFim" => $dtFim
                ])->fetchAll();
    }

}
?>  

