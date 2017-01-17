<?php

class Menu
{
    const MENU = array(
//        array(
//            "fa"       => "fa-link",
//            "text"     => "Contratos",
//            "url"      => "#",
//            "children" => array(
//                array(
//                    "fa"         => "fa-plus",
//                    "text"       => "Cadastrar",
//                    "permission" => PERFIL_1_ESCRITA,
//                    "url"        => DIRETORIO_CONTEUDO . "/addContrato.php",
//                    "children"   => null
//                ),
//                array(
//                    "fa"       => "fa-search",
//                    "text"     => "Pesquisar",
//                    "url"      => DIRETORIO_CONTEUDO . "/viewContrato.php",
//                    "children" => null
//                )
//            )
//        ),
        array(
            "fa"         => "fa-cogs",
            "text"       => "Configurações",
            "url"        => "#",
            "permission" => PERFIL_1_ESCRITA,
            "children"   => array(
//                array(
//                    "fa"       => "fa-building",
//                    "text"     => "Cadastrar Empresas",
//                    "url"      => DIRETORIO_CONTEUDO . "/addEmpresa.php",
//                    "children" => null
//                ),
                array(
                    "fa"       => "fa-user",
                    "text"     => "Usuário",
                    "url"      => "#",
                    "children" => array(
                        array(
                            "fa"   => "fa-user-plus",
                            "text" => "Cadastrar",
                            "url"  => DIRETORIO_CONTEUDO . "/addUsuario.php"
                        ),
                        array(
                            "fa"   => "fa-search",
                            "text" => "Pesquisar",
                            "url"  => DIRETORIO_CONTEUDO . "/viewUsuario.php"
                        )
                    )
                )

            )
        ),
        /*
         *         array(
                    "fa"       => "fa-line-chart",
                    "text"     => "Relatório",
                    "url"      => "#",
                    "children" => array(
                        array(
                            "fa"       => "fa-bar-chart",
                            "text"     => "Acompanhamento de Datas",
                            "url"      => DIRETORIO_CONTEUDO . "/slaDatas.php",
                            "children" => null
                            ),
                            array (
                                    "fa" => "fa-bar-chart",
                                    "text" => "Alterações por Período",
                                    "url" => DIRETORIO_CONTEUDO . "/#",
                                    "children" => NULL
                            
                ),
                array(
                    "fa"       => "fa-history",
                    "text"     => "Histórico do Contrato",
                    "url"      => DIRETORIO_CONTEUDO . "/historico.php",
                    "children" => null
                )
            )
        )
    */
    );

    public static function getMenu ()
    {
        return self::MENU;
    }
}