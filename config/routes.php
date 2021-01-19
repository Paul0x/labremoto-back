<?php

/*
 *            __   __   ___        __  ___  __  
 * |     /\  |__) |__) |__   |\/| /  \  |  /  \ 
 * |___ /~~\ |__) |  \ |___  |  | \__/  |  \__/ 
 * =============================================
 * Laboratório Remoto de Robótica Móvel                                           
 * Autor: Paulo Felipe P. Parreira - paulof (at) ufop.edu.br
 * =============================================
 * Arquivo: routes.php
 * Descrição: Arquivo responsável por armazenar as rotas da API.
 */


/*
 * Estrutura da rota: 
 * "raiz" => ["controller,
 *              [ 
 *                  "metodo" => [
 *                      "url" => ["funcao", [variaveis]]
 *                  ]
 *              ]
 *          ]
 */

class Routes {
    static $routes = [
        "login" => ["LoginController",
            [
                "GET" => [
                    "" => ["authUser", []]
                ],
                "POST" => [
                    "" => ["authUser", []]
                ],
                "PUT" => [],
                "DELETE" => []
            ]],
        "laboratorio" => ["LaboratorioController",
            [
                "GET" => [
                    "sessao-ativa" => ["getSessaoAtiva", []],
                    "experimentos" => ["getExperimentos", []],
                    "experimento-ativo" => ["getExperimentoAtivo", []],
                    "experimento-parametros" => ["getExperimentoParametros", []],
                    "experimento-instrucoes" => ["getExperimentoInstrucoes", []]
                ],
                "POST" => [
                    "sessao" => ["startSessao", []],
                    "experimento" => ["startExperimento", []],
                    "experimento-parametros" => ["setExperimentoParametros", []],
                    "experimento-instrucoes" => ["setExperimentoInstrucoes", []],
                    "experimento-objetivo" => ["setApontarObjetivo", []]
                ],
                "PUT" => [],
                "DELETE" => []
            ]]
    ];

}

?>
