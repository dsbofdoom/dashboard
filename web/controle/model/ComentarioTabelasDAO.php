<?php

/**
 * Created by PhpStorm.
 * User: saulocorreia
 * Date: 18/05/2017
 * Time: 15:54
 */
class ComentarioTabelasDAO
{
    public static function buscarTabelas ()
    {
        $resultado = [];
        $unico = [];

        try
        {
            $query = empty($_REQUEST['query']) ? [] : self::getTables((new PHPSQLParser($_REQUEST['query']))->parsed);
        } catch (ErrorException $e)
        {
            $query = [];
        }

        $tabelas = array_merge($query, explode("\r\n", $_REQUEST['tabelas']));
        foreach ($tabelas as $index => $tabela)
        {
            $tabela = strtolower(trim($tabela));

            if (empty($tabela) || in_array($tabela, $unico))
            {
                continue;
            }

            $dados = explode('.', $tabela);
            if (count($dados) > 1)
            {
                $result = UtilDAO::getResult(Querys::SELECT_TABELA_BY_SCHEMA_TABELA, $dados[0], $dados[1]);
            }

            if (empty($result))
            {
                $resultado[] = (object) [
                    'nome'       => $tabela,
                    'comentario' => '',
                    'id_tabela'  => ''
                ];
            }
            else
            {
                $resultado[] = $result[0];
            }

            $unico[] = $tabela;
        }

        sort($resultado);

        Portal\Ajax::RespostaGenerica('', '', false, $resultado);
    }

    public static function salvarTabela ()
    {
        $tabela = key($_REQUEST['comentario']);
        $id = key($_REQUEST['comentario'][$tabela]);

        $schemaETabela = explode('.', $tabela);

        try
        {
            \Portal\Gestao::flushMemcache();

            if (empty($id))
            {
                UtilDAO::executeQueryParam(Querys::INSERT_TABELA_SUGESTAO, $schemaETabela[0], $schemaETabela[1], $_REQUEST['comentario'][$tabela][$id]);
            }
            else
            {
                UtilDAO::executeQueryParam(Querys::UPDATE_TABELA_SUGESTAO, $schemaETabela[0], $schemaETabela[1], $_REQUEST['comentario'][$tabela][$id]);
            }

            Portal\Ajax::RespostaSucesso("Comentário da tabela {$tabela} salva com sucesso.", false);
        } catch (Exception $e)
        {
            Portal\Ajax::RespostaErro("Falha ao salvar comentário da tabela {$tabela}.", $e);
        }
    }

    private static function getTables ($dados)
    {
        $tabelas = [];

        if (is_array($dados))
        {
            foreach ($dados as $index => $dado)
            {
                if (is_array($dado))
                {
                    $tabelas = array_merge($tabelas, self::getTables($dado));
                }
                elseif ($index == 'table')
                {
                    $tabelas[] = $dado;
                }
            }
        }

        return $tabelas;
    }
}