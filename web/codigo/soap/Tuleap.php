<?php

/**
 * Created by PhpStorm.
 * User: saulocorreia
 * Date: 1/11/2017
 * Time: 4:58 PM
 */
class Tuleap
{
    const HOST = 'http://gestaoaplicacoes.mec.gov.br';
    const HOST_LOGIN = self::HOST . '/soap/?wsdl';
    const HOST_PROJECT = self::HOST . '/soap/project/?wsdl';
    const HOST_TRACKER = self::HOST . '/plugins/tracker/soap/?wsdl';

    private $usuario;
    private $senha;

    private $client_login;
    private $client_project;
    private $client_tracker;

    private $session_hash;

    private $dados;

    function __construct ($usuario, $senha)
    {
        $this->usuario = $usuario;
        $this->senha = $senha;
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    static function startsWith ($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    static function endsWith ($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0)
        {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    /**
     * @param $time
     * @return false|string
     */
    static function trataData ($time)
    {
        if (empty($time))
        {
            return '';
        }

        date_default_timezone_set('America/Sao_Paulo');
        return date("y-m-d H:i:s", $time);
    }

    /**
     * @param       $var
     * @param int   $nivel
     * @param bool  $full
     * @param array $desabilita
     * @return string
     */
    static function printInTree ($var, $nivel = 0, $full = false, $desabilita = [])
    {
        $retorno = '';

        $espaco = '';
        $espacoMeio = '&#x251C;&#x2500;&#x2500; ';
        $espacoFim = '&#x2514;&#x2500;&#x2500; ';
        for ($i = 0; $i < $nivel - 1; $i++)
        {
            if (in_array($i, $desabilita))
            {
                $espaco .= '    ';
            }

            else
            {
                $espaco .= '&#x2502;   ';
            }
        }

        if (is_array($var))
        {
            $pos = 0;
            $count = count($var);
            foreach ($var as $key => $value)
            {
                $pos++;

                if ($count == $pos)
                {
                    $desabilita[] = $nivel - 1;
                }

                $retorno .= "<br>" . $espaco . ($nivel > 0 ? ($count == $pos ? $espacoFim : $espacoMeio) : '') . $key . ' ';
                $retorno .= self::printInTree($var[$key], $nivel + 1, $full, $desabilita);
            };

        }
        else
        {
            if (is_object($var))
            {
                $pos = 0;
                $count = count(get_object_vars($var));
                foreach (get_object_vars($var) as $key => $value)
                {
                    $pos++;
                    if (self::startsWith($key, 'field_') and $full)
                    {
                        if ($key == 'field_name')
                        {
                            continue;
                        }
                        else
                        {
                            if ($key == 'field_label')
                            {
                                $retorno .= $value;
                                continue;
                            }
                            else
                            {
                                if ($key == 'field_value' AND isset($value->value))
                                {
                                    $retorno .= ' => ' . $value->value;
                                    continue;
                                }
                            }
                        }
                    }

                    if ($count == $pos)
                    {
                        $desabilita[] = $nivel - 1;
                    }

                    $retorno .= "<br>" . $espaco . ($nivel > 0 ? ($count == $pos ? $espacoFim : $espacoMeio) : '') . $key;
                    $retorno .= self::printInTree($var->{$key}, $nivel + 1, $full, $desabilita);
                };
            }
            else
            {
                if (is_integer($var) and strlen($var) == 10)
                {
                    $var = self::trataData($var);
                }

                $retorno .= ' => (' . gettype($var) . ') ' . $var;
            }
        }

        return $retorno;
    }

    function init ()
    {
        if (isset($this->session_hash))
        {
            return;
        }

        error_log('Inicializando leitura dos WS');
        $SOAP_OPTION = [
            'cache_wsdl'     => WSDL_CACHE_NONE,
            'exceptions'     => 1,
            'trace'          => true,
            'encoding'       => 'UTF-8',
            'stream_context' => stream_context_create([
                'ssl' => [// set some SSL/TLS specific options
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                ]
            ])
        ];

        $this->client_login = new SoapClient(self::HOST_LOGIN, $SOAP_OPTION);

        $this->session_hash = $this->client_login->login($this->usuario, $this->senha)->session_hash;

        $this->client_project = new SoapClient(self::HOST_PROJECT, $SOAP_OPTION);
        $this->client_tracker = new SoapClient(self::HOST_TRACKER, $SOAP_OPTION);
        error_log('WS conectados');
    }

    function buscaDadosProjeto ()
    {
        $this->init();

        register_shutdown_function('generateCallTrace', new Exception());
        
        set_time_limit(600);
        error_log('Buscando Projetos');
        $this->dados = $this->client_login->getMyProjects($this->session_hash);
        error_log('Buscando Projetos finalizados');
    }

    function buscaDadosTracker ()
    {
        $this->buscaDadosProjeto();
        error_log('Buscando Trackers');

        foreach ($this->dados as $key => $value)
        {
            $this->dados[$key]->tracker = $this->client_tracker->getTrackerList($this->session_hash, $value->group_id);
        }
        error_log('Buscando Trackers finalizados');
    }

    function buscaDadosArtifacts ()
    {
        $this->buscaDadosTracker();

        error_log('Buscando Artifacts');
        foreach ($this->dados as $key => $value)
        {
            foreach ($this->dados[$key]->tracker as $key2 => $value2)
            {
                $this->dados[$key]->tracker[$key2]->artifacts = $this->client_tracker->getArtifacts($this->session_hash, $value->group_id, $value2->tracker_id)->artifacts;
            }
        }
        error_log('Buscando Trackers finalizado');
    }

    function inserirDadosProjeto ()
    {
        $this->buscaDadosProjeto();

        error_log('Inserindo Projetos');
        foreach ($this->dados as $key => $value)
        {
            $existe = UtilDAO::getResult(Querys::SELECT_PROJETO_BY_ID, $value->group_id);
            if (count($existe) > 1)
            {
                UtilDAO::executeQueryParam(Querys::UPDATE_PROJETO,
                    $value->group_name
                    , $value->unix_group_name
                    , $value->description
                    , $value->group_id
                );
            }

            else
            {
                UtilDAO::executeQueryParam(Querys::INSERT_PROJETO,
                    $value->group_id
                    , $value->group_name
                    , $value->unix_group_name
                    , $value->description
                );
            }
        }
        error_log('Inserindo Projetos finalizados');

        return self::printInTree($this->dados);
    }

    function inserirDadosTracker ()
    {
        $this->buscaDadosTracker();

        error_log('Inserindo Tracker');
        foreach ($this->dados as $key => $value)
        {
            foreach ($this->dados[$key]->tracker as $key2 => $value2)
            {
                $existe = UtilDAO::getResult(Querys::SELECT_TRACKER_BY_ID, $value2->tracker_id);
                if (count($existe) > 1)
                {
                    UtilDAO::executeQueryParam(Querys::UPDATE_TRACKER, 
                        $value2->group_id
                        , $value2->name
                        , $value2->description
                        , $value2->item_name
                        , $value2->tracker_id
                    );
                }
                else
                {
                    UtilDAO::executeQueryParamArray(Querys::INSERT_TRACKER, 
                        $value2->tracker_id
                        , $value2->group_id
                        , $value2->name
                        , $value2->description
                        , $value2->item_name
                    );
                }
            }
        }
        error_log('Inserindo Tracker finalizado');

        return self::printInTree($this->dados);
    }

    function inserirDadosArtifacts ()
    {
        $this->buscaDadosArtifacts();

        error_log('Inserindo Artifacts');

        $querysThread = [];

        foreach ($this->dados as $key => $value)
        {
            foreach ($this->dados[$key]->tracker as $key2 => $value2)
            {
                foreach ($this->dados[$key]->tracker[$key2]->artifacts as $key3 => $value3)
                {
                    $existe = UtilDAO::getResult(Querys::SELECT_ARTIFACT_BY_ID, $value3->tracker_id);
                    if (count($existe) > 1)
                    {
                        UtilDAO::executeQueryParam(Querys::UPDATE_ARTIFACT,
                            $value3->tracker_id
                            , $value2->group_id
                            , $value3->submitted_by
                            , $value3->submitted_on
                            , $value3->last_update_date
                            , $value3->artifact_id
                        );
                    }
                    else
                    {
                        UtilDAO::executeQueryParam(Querys::INSERT_ARTIFACT,
                            $value3->artifact_id
                            , $value3->tracker_id
                            , $value2->group_id
                            , $value3->submitted_by
                            , $value3->submitted_on
                            , $value3->last_update_date
                        );
                    }

                    UtilDAO::executeQueryParam(Querys::DELETE_CROSS_REFERENCE, $value3->artifact_id);
                    $parametros = [];
                    foreach ($value3->cross_references as $key4 => $value4)
                    {
                        $parametros[] = [$value3->artifact_id, $value4->ref, $value4->url];
                    }

                    error_log("Começando {$value3->artifact_id}");
                    UtilDAO::executeQueryParamArray(Querys::INSERT_CROSS_REFERENCE, $parametros);
                    error_log("terminou {$value3->artifact_id}");
                }
            }
        }

        UtilDAO::executeArrayQuery($querysThread);

        error_log('Inserindo Artifacts finalizado');

        return self::printInTree($this->dados);
    }

    function trataTudo ()
    {
        $this->buscaDadosArtifacts();

        try
        {
            foreach ($this->dados as $key => &$value)
            {
                foreach ($value->tracker as $key2 => &$value2)
                {
                    foreach ($value2->artifacts as $key3 => &$value3)
                    {
                        foreach ($value3->value as $key4 => &$value4)
                        {
                            if (!(self::startsWith($value4->field_name, 'status')
                                OR self::startsWith($value4->field_name, 'assigned')
                                OR self::endsWith($value4->field_name, 'date'))
                            )
                            {
                                // unset ($this->dados[$key]->tracker[$key2]->artifacts[$key3]->value[$key4]);
                            }
                            else
                            {
                                if ((self::startsWith($value4->field_name, 'status')
                                        OR self::startsWith($value4->field_name, 'assigned'))
                                    AND count($this->dados[$key]->tracker[$key2]->artifacts[$key3]->value[$key4]->field_value->bind_value) > 0
                                )
                                {
                                    $this->dados[$key]->tracker[$key2]->artifacts[$key3]->value[$key4]->field_value = $this->dados[$key]->tracker[$key2]->artifacts[$key3]->value[$key4]->field_value->bind_value[0]->bind_value_label;
                                }
                                else
                                {
                                    if (isset($this->dados[$key]->tracker[$key2]->artifacts[$key3]->value[$key4]->field_value->value))
                                    {
                                        if (self::endsWith($value4->field_name, 'date'))
                                        {
                                            $this->dados[$key]->tracker[$key2]->artifacts[$key3]->value[$key4]->field_value = self::trataData($this->dados[$key]->tracker[$key2]->artifacts[$key3]->value[$key4]->field_value->value);
                                        }
                                        else
                                        {
                                            $this->dados[$key]->tracker[$key2]->artifacts[$key3]->value[$key4]->field_value = $this->dados[$key]->tracker[$key2]->artifacts[$key3]->value[$key4]->field_value->value;
                                        }
                                    }
                                    else
                                    {
                                        // $this->dados[$key]->tracker[$key2]->artifacts[$key3]->value[$key4]->field_value = '';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e)
        {

        }
        return $this->dados;
    }
}
