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
                    "sessao-ativa" => ["findSessaoAtiva", []]
                ],
                "POST" => [
                    "sessao" => ["startSessao", []]
                ],
                "PUT" => [],
                "DELETE" => []
            ]]
    ];

}

?>
