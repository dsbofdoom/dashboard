<?php

/**
 * Created by PhpStorm.
 * User: saulocorreia
 * Date: 1/30/2017
 * Time: 4:40 PM
 */
class TuleapStatic
{
    /**
     * Insere os dados de Group
     * @return string
     */
    protected static function inserirProjeto ($dados)
    {
        $querys = [];

        foreach ($dados as $key => $value)
        {
            $existe = UtilDAO::getResult(
                Querys::SELECT_PROJETO_BY_ID
                , $value->group_id
            );
            if (count($existe) > 0)
            {
                $existe = $existe[0];

                if ($value->group_name != $existe->group_name
                    || $value->unix_group_name != $existe->unix_group_name
                    || $value->description != $existe->description
                )
                {
                    $querys[] = UtilDAO::MontarQuery(Querys::UPDATE_PROJETO,
                        $value->group_name
                        , $value->unix_group_name
                        , $value->description
                        , $value->group_id
                    );
                }
            }
            else
            {
                $querys[] = UtilDAO::MontarQuery(Querys::INSERT_PROJETO,
                    $value->group_id
                    , $value->group_name
                    , $value->unix_group_name
                    , $value->description
                );
            }
        }

        self::inserirDados('Projetos', $querys);
    }

    protected static function inserirTracker ($dados)
    {
        $querys = [];
        foreach ($dados as $key => $value)
        {
            foreach ($value->tracker as $key2 => $value2)
            {
                $existe = UtilDAO::getResult(
                    Querys::SELECT_TRACKER_BY_ID
                    , $value2->tracker_id
                );
                if (count($existe) > 0)
                {
                    $existe = $existe[0];

                    if ($value2->group_id != $existe->group_id
                        || $value2->name != $existe->name
                        || $value2->description != $existe->description
                        || $value2->item_name != $existe->item_name
                    )
                    {
                        $querys[] = UtilDAO::MontarQuery(Querys::UPDATE_TRACKER,
                            $value2->group_id
                            , $value2->name
                            , $value2->description
                            , $value2->item_name
                            , $value2->tracker_id
                        );
                    }
                }
                else
                {
                    $querys[] = UtilDAO::MontarQuery(Querys::INSERT_TRACKER,
                        $value2->tracker_id
                        , $value2->group_id
                        , $value2->name
                        , $value2->description
                        , $value2->item_name
                    );
                }
            }
        }

        self::inserirDados('Tracker', $querys);
    }

    /**
     * Insere as Cross References de um Artefato
     * @param $artefato
     */
    protected static function inserirCrossReferences ($artefato)
    {
        $querysThread = [];

        foreach ($artefato as $key3 => $value3)
        {
            $falhou = false;
            foreach ($value3->cross_references as $key4 => $value4)
            {
                $existe = UtilDAO::getResult(
                    Querys::SELECT_CROSS_REFERENCE_BY_ALL
                    , $value3->artifact_id
                    , $value4->ref
                    , $value4->url
                );
                if (count($existe) == 0)
                {
                    $falhou = true;
                    break;
                }
            }
            if ($falhou)
            {
                $querysThread[] = UtilDAO::MontarQuery(
                    Querys::DELETE_CROSS_REFERENCE
                    , $value3->artifact_id
                );

                foreach ($value3->cross_references as $key4 => $value4)
                    $querysThread[] = UtilDAO::MontarQuery(
                        Querys::INSERT_CROSS_REFERENCE
                        , $value3->artifact_id
                        , $value4->ref
                        , $value4->url
                    );
            }
        }

        self::inserirDados('CrossReferences', $querysThread);
    }

    /**
     * Insere os Valores de um Artefato
     * @param $artefato
     */
    protected static function inserirValues ($artefato)
    {
        $querysThread = [];

        foreach ($artefato as $key3 => $value3)
        {
            foreach ($value3->value as $key4 => $value4)
            {
                if (is_array($value4->field_value) || is_object($value4->field_value))
                {
                    continue;
                }

                $field_value = SQLite3::escapeString($value4->field_value);

                $existe = UtilDAO::getResult(
                    Querys::SELECT_FIELD_BY_ARTIF_ID_FIELD_NAME
                    , $value3->artifact_id
                    , $value4->field_name
                );
                if (count($existe) == 0)
                {
                    $querysThread[] = UtilDAO::MontarQuery(
                        Querys::INSERT_FIELD
                        , $value3->artifact_id
                        , $value4->field_name
                        , $value4->field_label
                        , $field_value
                    );
                }
                elseif ($field_value != SQLite3::escapeString($existe[0]->field_value))
                {
                    $querysThread[] = UtilDAO::MontarQuery(
                        Querys::UPDATE_FIELD
                        , $field_value
                        , $existe[0]->field_id
                    );
                }
            }
        }

        self::inserirDados('Values', $querysThread);
    }

    /**
     * Insere os Artefatos
     * @param $artifacts
     * @param $value2
     */
    protected static function inserirArtefato ($artifacts, $value2)
    {
        $querysThread = [];

        foreach ($artifacts as $key3 => $value3)
        {
            $existe = UtilDAO::getResult(
                Querys::SELECT_ARTIFACT_BY_ID
                , $value3->artifact_id
            );

            if (count($existe) > 0)
            {
                $existe = $existe[0];
                if ($existe->tracker_id != $value3->tracker_id
                    || $existe->submitted_by != $value3->submitted_by
                    || $existe->submitted_on != Util::trataData($value3->submitted_on)
                    || $existe->last_update_date != Util::trataData($value3->last_update_date)
                    || $existe->artifact_id != $value3->artifact_id
                    || $existe->group_id != $value2->group_id
                    || $existe->type != $value3->type
                )
                {
                    $querysThread[] = UtilDAO::MontarQuery(Querys::UPDATE_ARTIFACT,
                        $value3->tracker_id
                        , $value2->group_id
                        , $value3->submitted_by
                        , Util::trataData($value3->submitted_on)
                        , Util::trataData($value3->last_update_date)
                        , $value3->type
                        , $value3->artifact_id
                    );
                }
            }
            else
            {
                $querysThread[] = UtilDAO::MontarQuery(Querys::INSERT_ARTIFACT,
                    $value3->artifact_id
                    , $value3->tracker_id
                    , $value2->group_id
                    , Util::trataData($value3->submitted_by)
                    , Util::trataData($value3->submitted_on)
                    , Util::trataData($value3->last_update_date)
                    , $value3->type
                );
            }
        }

        self::inserirDados('Artifacts', $querysThread);
    }

    protected static function inserirUsuario (array $users)
    {
        $querysThread = [];
        foreach ($users as $index => $user)
        {
            $existe = UtilDAO::getResult(
                Querys::SELECT_USUARIO_BY_ID
                , $index
            );

            if (count($existe) == 0)
            {
                $querysThread[] = UtilDAO::MontarQuery(Querys::INSERT_USUARIO_TULEAP,
                    $user->id,
                    $user->real_name,
                    $user->username,
                    md5(UsuarioDAO::SENHA_PADRAO),
                    PERFIL_2_CONSULTA,
                    $user->username
                );
            }
        }

        self::inserirDados('Usuarios', $querysThread);
    }

    protected static function inserirDados (string $nome, array $querys)
    {
        if (count($querys) > 0)
        {
            error_log("Inserindo {$nome}");

            UtilDAO::executeArrayQuery($querys);

            error_log("Inserindo {$nome} finalizados");
        }
    }


    protected static function trataValoresArtifacts ($dados)
    {

        foreach ($dados as $key3 => &$value3)
        {
            $tipo = '';
            foreach ($value3->value as $key4 => &$value4)
            {
                if (property_exists($value4->field_value, 'value'))
                {
                    if (Util::endsWith($value4->field_name, 'date'))
                    {
                        $value4->field_value = Util::trataData($value4->field_value->value);
                    }
                    else
                    {
                        $value4->field_value = $value4->field_value->value;
                    }
                }
                elseif (property_exists($value4->field_value, 'bind_value'))
                {
                    if (is_array($value4->field_value->bind_value)
                        && count($value4->field_value->bind_value) == 1
                        && property_exists($value4->field_value->bind_value[0], 'bind_value_label')
                    )
                    {
                        $value4->field_value = $value4->field_value->bind_value[0]->bind_value_label;
                    }
                    else
                    {
                        $value4->field_value = $value4->field_value->bind_value;
                    }
                }

                if ($value4->field_name == 'estimated_effort_points')
                {
                    $tipo = 'story';
                }
                elseif ($value4->field_name == 'sprint_name')
                {
                    $tipo = 'sprint';
                }
                elseif ($value4->field_name == 'progress')
                {
                    $tipo = 'release';
                }
                elseif ($value4->field_label == 'Descrição da Tarefa')
                {
                    $tipo = 'task';
                }
            }

            $value3->type = $tipo;
        }

        return $dados;
    }

    protected static function buscaValor (array $dados, string $busca, string $variavelBusca, string $variavelResposta)
    {
        foreach ($dados as $index => $dado)
        {
            if ($dado->{$variavelBusca} == $busca)
            {
                return $dado->{$variavelResposta};
            }
        }

        return (boolean)false;
    }

    protected static function existeValor (array $dados, string $busca, string $variavelBusca, string $variavelResposta)
    {
        foreach ($dados as $index => $dado)
        {
            if ($dado->{$variavelBusca} == $busca)
            {
                return true;
            }
        }

        return (boolean)false;
    }
}