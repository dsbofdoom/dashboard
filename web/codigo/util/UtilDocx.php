<?php

/**
 * Created by PhpStorm.
 * User: saulocorreia
 * Date: 2/20/2017
 * Time: 3:45 PM
 */
class UtilDocx
{
    /**
     * @param $dirTmp
     * @param $projeto
     * @param $release
     * @return string
     */
    public static function montarDocumentacao ($dirTmp, $projeto, $release)
    {
        // Criar Roteiro
        $dirProjeto = self::criarRoteiro($dirTmp, $projeto, $release);

        // Criar Termo de Entrega
        $dirRelease = self::criarTermoEntrega($projeto, $release, $dirProjeto);

        // Criar Storys
        self::criarStorys($projeto, $release, $dirRelease);

        // Compacta todos os arquivos
        $zip = "{$_SERVER ['DOCUMENT_ROOT']}/{$projeto->unix_group_name}-release-{$release}.zip";
        Util::zipFile($dirTmp, $zip);

        return $zip;
    }

    /**
     * @param $template
     * @param $search
     * @param $replace
     * @param $saida
     * @return bool
     */
    public static function converterTemplate ($template, $search, $replace, $saida)
    {
        //open file and get data
        $data = file_get_contents("{$template}/word/document2.xml");

        // do tag replacements or whatever you want
        $data = str_replace($search, $replace, $data);

        //save it back
        file_put_contents("{$template}/word/document.xml", $data);

        return (bool)Util::zipFile($template, $saida, '/word/document2.xml');
    }

    /**
     * @param        $text
     * @param string $delimiter
     * @return string
     */
    public static function converteEmParagrafo ($text, $delimiter = '<w:br/>')
    {
        $split = explode($delimiter, $text);

        $paragrafos = '';
        foreach ($split as $index => $texto)
        {
            $paragrafos .= "<w:p><w:pPr><w:rPr><w:lang w:eastAsia=\"en-US\"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:eastAsia=\"en-US\"/></w:rPr><w:t>{$texto}</w:t></w:r></w:p>";
        }

        return $paragrafos;
    }

    /**
     * @param $dirTmp
     * @param $projeto
     * @param $release
     * @return string
     */
    private static function criarRoteiro ($dirTmp, $projeto, $release)
    {
        // cria diretorio release e copia arquivo roteiro
        $dirProjeto = "{$dirTmp}/{$projeto->unix_group_name}-release-{$release}";
        Util::criaPasta($dirProjeto);
        self::converterTemplate(
            TEMPLATE_ROTEIRO,
            [
                '##_NOME_SISTEMA_##',
                '##_UNIX_NAME_##',
                '##_NUMERO_RELEASE_##',
                '##_NUMERO_SPRINT_##'
            ],
            [
                $projeto->group_name,
                $projeto->unix_group_name,
                $release,
                $_REQUEST['sprint']
            ],
            "{$dirProjeto}/roteiroPublicacaoRelease{$release}.docx"
        );

        return $dirProjeto;
    }

    /**
     * @param $projeto
     * @param $release
     * @param $dirProjeto
     * @return string
     */
    private static function criarTermoEntrega ($projeto, $release, $dirProjeto)
    {
// cria diretorio sprint e copia arquivo termo
        $dirRelease = "{$dirProjeto}/sprint-{$_REQUEST['sprint']}";
        Util::criaPasta($dirRelease);
        self::converterTemplate(
            TEMPLATE_TERMO_ENTREGA,
            [
                '##_NOME_SISTEMA_##',
                '##_UNIX_NAME_##',
                '##_NUMERO_RELEASE_##',
                '##_NUMERO_SPRINT_##'
            ],
            [
                $projeto->group_name,
                $projeto->unix_group_name,
                $release,
                $_REQUEST['sprint']
            ],
            "{$dirRelease}/termoDeEntrega-sprint-{$_REQUEST['sprint']}.docx"
        );
        return $dirRelease;
    }

    /**
     * @param $projeto
     * @param $release
     * @param $dirRelease
     */
    private static function criarStorys ($projeto, $release, $dirRelease)
    {
        $story = [];
        foreach (UtilDAO::getResult(Querys::SELECT_VALUES_BY_SPRINT, intval($_REQUEST['sprint'])) as $row)
        {
            $story[$row->artifact_id][$row->field_name] = Encoding::fixUTF8(
                str_replace("\xc2\xa0", ' ',
                    preg_replace('/\s+/S', ' ',
                        trim(
                            preg_replace("/[\n\r]/", '<w:br/>',
                                stripslashes(
                                    strip_tags(
                                        html_entity_decode($row->field_value, ENT_QUOTES, 'UTF-8'), '</p></li>')
                                )
                            )
                        )
                    )
                )
            );

            while (strpos($story[$row->artifact_id][$row->field_name], '<w:br/><w:br/>') !== false)
            {
                $story[$row->artifact_id][$row->field_name] = str_replace('<w:br/><w:br/>', '<w:br/>', $story[$row->artifact_id][$row->field_name]);
            }

            if ($row->field_name == 'como_demonstrar' || $row->field_name == 'acceptance_criteria_1')
            {
                $story[$row->artifact_id][$row->field_name] = self::converteEmParagrafo($story[$row->artifact_id][$row->field_name]);
            }
        }

        foreach ($story as $key => $row)
        {
            // cria diretorio historia e copia arquivo analise funcionalidades
            Util::criaPasta("{$dirRelease}/historia-{$key}");
            self::converterTemplate(
                TEMPLATE_HISTORIA,
                [
                    '##_NOME_SISTEMA_##',
                    '##_UNIX_NAME_##',
                    '##_NUMERO_RELEASE_##',
                    '##_NUMERO_SPRINT_##',
                    '##_NUMERO_STORY_##',
                    '##_DESCRICAO_##',
                    '##_BREVE_DESCRICAO_##',
                    '##_FUNCIONALIDADE_##'
                ],
                [
                    $projeto->group_name,
                    $projeto->unix_group_name,
                    $release,
                    $_REQUEST['sprint'],
                    $key,
                    $row['como_demonstrar'] . " " . $row['acceptance_criteria_1'],
                    $row['observao'],
                    (!empty($row['in_order_to_1']) ? $row['in_order_to_1'] : $row['i_want_to'])
                ],
                "{$dirRelease}/historia-{$key}/AnaliseFuncionalidadesHistoria{$key}.docx");
        }

        Util::criaPasta("{$dirRelease}/scripts");
    }
}