<?php
/**
 * Created by PhpStorm.
 * User: saulocorreia
 * Date: 19/06/2017
 * Time: 10:12
 */

namespace Portal;


class Template
{
    /**
     * Carrega o template padrão
     *
     * @param $tpl
     * @return \raelgc\Template
     */
    public static function carregarTemplate (&$tpl): \raelgc\Template
    {
        $tpl = new \raelgc\Template (DIRETORIO_VIEW . '/padrao.html', false);

        // DEBUG
        $tpl->DEBUG = self::SendScript("var debug = " . (DEBUG ? 'true' : 'false') . ";");

        // CABECALHO
        $tpl->TITULO_PAGINA = TITULO_PAGINA;

        // RODAPE
        $tpl->NOME_SISTEMA = NOME_SISTEMA;
        $tpl->ANO_SISTEMA = ANO_SISTEMA;
        $tpl->EMPRESA_SISTEMA = EMPRESA_SISTEMA;

        // DIRETORIOS E PAGINAS
        $tpl->PAGINA_PRINCIPAL = PAGINA_PRINCIPAL;
        $tpl->DIRETORIO_RAIZ = ENDERECO_RAIZ;
        $tpl->DIRETORIO_CONTEUDO = DIRETORIO_RAIZ_VIEW;

        // USUARIO
        $tpl->NOME_COMPLETO_USUARIO = $_SESSION ["NOME_USUARIO"];
        $tpl->NOME_CURTO_USUARIO = explode(' ', trim($_SESSION ["NOME_USUARIO"])) [0];

        // MENU
        self::montarMenu($tpl);

        return $tpl;
    }

    public static function preencherFiltro (\raelgc\Template &$tpl, $campo, $coluna, $block, $query)
    {
        foreach (\UtilDAO::getResult($query) as $row)
        {
            $tpl->{$campo} = $row [$coluna];
            $tpl->block($block);
        }
    }

    public static function SendReadyScript ($script)
    {
        return "<script>$().ready(function(){" . $script . "});</script>";
    }

    public static function SendScript ($script)
    {
        return "<script>{$script}</script>";
    }


    public static function preparaPopOver (string $glue, string $label, string $value, array $dados)
    {
        $aux = [];

        foreach ($dados as $item)
        {
            //$valor = str_replace("\n",'<br>',
            $valor = trim(
            //html_entity_decode(
                (trim($item->{$value}))
            //    )
            );
            //);

            $aux[] = htmlspecialchars("<div class='row par_impar'><div class='col-sm-4 text-right'>{$item->{$label}}</div><div class='col-sm-8 text-justify'>{$valor}</div></div>");
        }

        return (string) implode($glue, $aux);
    }

    /**
     * @param $tpl
     */
    private static function montarMenu (\raelgc\Template &$tpl): void
    {
        $menu = Menu::getMenu();
        $permissaoUsuario = $_SESSION ["PERFIL"];
        foreach ($menu as $m)
        {
            // Se o usuario nao tiver permissao pular este menu
            if (isset ($m ["permission"]) and $m ["permission"] < $permissaoUsuario)
            {
                continue;
            }

            $tpl->MENU_URL = $m ["url"];
            $tpl->MENU_FA = $m ["fa"];
            $tpl->MENU_TEXT = $m ["text"];

            // verifica se possui menu filho
            if (isset ($m ["children"]) && $m ["children"] != null)
            {
                $tpl->MENU_CHILDREN_1 = "<i class='fa fa-angle-left pull-right'></i>";
                $tpl->block('BLOCK_PRE_PRE_CHILDREN_1');
                foreach ($m ["children"] as $c1)
                {
                    // Se o usuario nao tiver permissao pular este menu
                    if (isset ($c1 ["permission"]) and $c1 ["permission"] < $permissaoUsuario)
                    {
                        continue;
                    }

                    $tpl->CHILDREN_1_URL = $c1 ["url"];
                    $tpl->CHILDREN_1_FA = $c1 ["fa"];
                    $tpl->CHILDREN_1_TEXT = $c1 ["text"];

                    // verifica se possui menu neto
                    if (isset ($c1 ["children"]))
                    {
                        $tpl->MENU_CHILDREN_2 = "<i class='fa fa-angle-left pull-right'></i>";
                        foreach ($c1 ["children"] as $c2)
                        {
                            // Se o usuario nao tiver permissao pular este menu
                            if (isset ($c2 ["permission"]) and $c2 ["permission"] < $permissaoUsuario)
                            {
                                continue;
                            }

                            $tpl->CHILDREN_2_URL = $c2 ["url"];
                            $tpl->CHILDREN_2_FA = $c2 ["fa"];
                            $tpl->CHILDREN_2_TEXT = $c2 ["text"];
                            $tpl->block("BLOCK_CHILDREN_2");
                        }
                        // tendo neto devese abrir o bloco pre_children_2
                        $tpl->block("BLOCK_PRE_CHILDREN_2");

                    }
                    else
                        // uma vez iniciado o bloco, deve-se limpar para indicar a remocao,
                        // senao ira aparecer para todas as linhas seguintes mesmo nao sendo chamado
                    {
                        $tpl->clear("MENU_CHILDREN_2");
                    }

                    $tpl->block("BLOCK_CHILDREN_1");
                }

                // tendo filho devese abrir o bloco pre_children_1
                $tpl->block("BLOCK_PRE_CHILDREN_1");
            }

            $tpl->block("BLOCK_MENU");
        }
    }
}