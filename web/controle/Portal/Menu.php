<?php

namespace Portal;

class Menu
{
    const MENU = [
        [
            'fa'         => 'fa-link',
            'text'       => 'Carga',
            'permission' => PERFIL_1_ESCRITA,
            'url'        => DIRETORIO_RAIZ_VIEW . '/carga',
        ],
        [
            'fa'       => 'fa-file-text-o',
            'text'     => 'Documentação',
            'url'      => '#',
            'children' => [
                [
                    'fa'   => 'fa-file-text-o',
                    'text' => 'Gerar Documentação',
                    'url'  => DIRETORIO_RAIZ_VIEW . '/documentacao',
                ],
                [
                    'fa'   => 'fa-table',
                    'text' => 'Buscar Tabelas',
                    'url'  => DIRETORIO_RAIZ_VIEW . '/tabela',
                ]
            ]
        ],
        [
            'fa'         => 'fa-cogs',
            'text'       => 'Configurações',
            'url'        => '#',
            'permission' => PERFIL_0_ADMIN,
            'children'   => [
                [
                    'fa'       => 'fa-user',
                    'text'     => 'Usuário',
                    'url'      => '#',
                    'children' => [
                        [
                            'fa'   => 'fa-user-plus',
                            'text' => 'Cadastrar',
                            'url'  => DIRETORIO_RAIZ_VIEW . '/addUsuario'
                        ],
                        [
                            'fa'   => 'fa-search',
                            'text' => 'Pesquisar',
                            'url'  => DIRETORIO_RAIZ_VIEW . '/viewUsuario'
                        ]
                    ]
                ]

            ]
        ]
    ];

    public static function getMenu ()
    {
        return self::MENU;
    }
}