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
        $sessao = $this->db->select('sessao', ["ativo","dt_fim"], ["ativo" => true]);
        return $sessao;
    }
    
}

?>
