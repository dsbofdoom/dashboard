<?php

namespace Portal;
/**
 * Classe para padronizacao de resposta para Ajax
 *
 * @author saulo.araujo.correia
 *
 */
class Ajax
{
    const TIPO_SUCCESS = "success";
    const TIPO_INFO = "info";
    const TIPO_WARN = "warn";
    const TIPO_ERROR = "error";

    /**
     * Responde com sucesso.
     *
     * formato de resposta
     * {
     * "msg" : {
     * "texto" : $msg,
     * "tipo" : $tipo
     * },
     * "retorno" : $retorno,
     * }
     *
     * @param string $msg
     *            mensagem a ser mostrada
     * @param string $tipo
     *            [opicional = SUCCESS]
     * @param bool   $retorno
     *            deverá chamar função?
     */
    public static function RespostaSucesso (string $msg, bool $retorno, string $tipo = self::TIPO_SUCCESS)
    {
        die (json_encode(self::montaResposta($msg, $tipo, $retorno), JSON_PRETTY_PRINT));
    }

    /**
     * Responde com erro.
     *
     * formato de resposta
     * {
     * "msg" : {
     * "texto" : $msg,
     * "tipo" : "error"
     * }
     * }
     *
     * @param string $msg
     *            mensagem a ser mostrada
     */
    public static function RespostaErro (string $msg, Exception $trace = null)
    {
        $retorno = self::montaMsg($msg, self::TIPO_ERROR);
        if ($trace != null)
        {
            if ($trace->getCode() == 0)
            {
                $retorno ["msg"] ["trace"] = $trace->getMessage();
                $retorno ["console"] ["trace"] = $trace->getMessage();
            }
            else
            {
                $retorno ["console"] ["trace"] = $trace->getTraceAsString();
                $retorno ["console"] ["msg"] = $trace->getMessage();
                if (!empty ($trace->getPrevious()))
                {
                    $retorno ["console"] ["msgAnterior"] = $trace->getPrevious()->getMessage();
                }
            }
        }

        die (json_encode($retorno, JSON_PRETTY_PRINT));
    }

    /**
     * Responde com envio de dados genericos
     *
     * formato de resposta
     * {
     * "msg" : {
     * "texto" : $msg,
     * "tipo" : $tipo
     * },
     * "retorno" : $retorno,
     * "dado" : $dados
     * }
     *
     * @param string $msg
     *            mensagem a ser mostrada
     * @param string $tipo
     *            [opicional]
     * @param bool   $retorno
     *            deverá chamar função?
     * @param array  $dados
     *            array com os dados
     */
    public static function RespostaGenerica (string $msg, string $tipo, bool $retorno, array $dados)
    {
        $resposta = self::montaResposta($msg, $tipo, $retorno);
        $resposta ["valores"] = $dados;

        die (json_encode($resposta, JSON_PRETTY_PRINT));
    }

    /**
     * Monta Resposta Padrão, mas nao envia
     *
     * @param string $msg
     * @param string $tipo
     * @param bool   $retorno
     * @return string[][]
     */
    private static function montaResposta ($msg, $tipo, $retorno)
    {
        $resposta = self::montaMsg($msg, $tipo);
        $resposta ["retorno"] = $retorno;

        return $resposta;
    }

    /**
     * Monta Mensagem e nao envia
     *
     * @param string $msg
     * @param string $tipo
     * @return string[][]
     */
    private static function montaMsg (string $msg, string $tipo)
    {
        return [
            "msg" => [
                "texto" => $msg,
                "tipo"  => $tipo
            ]
        ];
    }
}