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
    public static function montarDocumentacao ($dirTmp, $projeto, $release, $storys)
    {
        // cria diretorio release
        $dirRelease = "{$dirTmp}/release-{$release}";
        Util::criaPasta($dirRelease);

        // Criar Roteiro
        self::criarRoteiro($dirRelease, $projeto, $release);

        // cria diretorio sprint
        $dirProjeto = "{$dirRelease}/sprint-{$_REQUEST['sprint']}";
        Util::criaPasta($dirProjeto);

        // Criar Termo de Entrega
        self::criarTermoEntrega($projeto, $release, $storys, $dirProjeto);

        // Criar Storys
        self::criarStorys($projeto, $release, $storys, $dirProjeto);
        
        // Criar diretorio script
        Util::criaPasta("{$dirProjeto}/scripts/");
        
        // Compacta todos os arquivos
        $zip = "{$_SERVER ['DOCUMENT_ROOT']}/{$projeto->unix_group_name}-release-{$release}_{$_SESSION ['ID_USUARIO']}.zip";
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

        return (bool) Util::zipFile($template, $saida, '/word/document2.xml');
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
     * Cria linhas da tabela de termo de entrega
     *
     * @param        $diretorio
     * @param string $projeto
     * @param string $release
     * @param string $sprint
     * @param array  $storys
     * @return string
     */
    public static function converteEmLinhaTabela ($diretorio, string $projeto, string $release, string $sprint, array $storys)
    {
        $linhas = '';
        $contador = 3;
        $StoryUnica = [];

        if ($diretorio)
        {
            $diretorio .= '/';
        }

        foreach ($storys as $index => $row)
        {
            if (in_array($row->artifact_id, $StoryUnica))
            {
                continue;
            }

            $contador++;
            $contadorString = sprintf('%02d', $contador);
            $StoryUnica[] = $row->artifact_id;

            $linhas .= <<<XML
<w:tr w:rsidR="00D00794" w:rsidTr="00FE71AC">
    <w:trPr>
        <w:trHeight w:val="355"/>
    </w:trPr>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="426" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            </w:tcBorders>
            <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p w:rsidR="00D00794" w:rsidRPr="009B5737" w:rsidRDefault="00D00794" w:rsidP="00FE71AC">
            <w:pPr>
                <w:pStyle w:val="Ttulodatabela"/>
                <w:spacing w:after="0"/>
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
            </w:pPr>
            <w:r>
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
                <w:t>{$contadorString}</w:t>
            </w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="2693" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            </w:tcBorders>
            <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p w:rsidR="00B064D7" w:rsidRPr="009B5737" w:rsidRDefault="00D00794" w:rsidP="00B064D7">
            <w:pPr>
                <w:pStyle w:val="Ttulodatabela"/>
                <w:spacing w:after="0"/>
                <w:ind w:left="72"/>
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
            </w:pPr>
            <w:r w:rsidRPr="009B5737">
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
                <w:t xml:space="preserve">Análise de Funcionalidades da História {$row->artifact_id}</w:t>
            </w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="5528" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            </w:tcBorders>
            <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p w:rsidR="00D00794" w:rsidRPr="00D00794" w:rsidRDefault="00D00794" w:rsidP="00FE71AC">
            <w:pPr>
                <w:pStyle w:val="Ttulodatabela"/>
                <w:spacing w:after="0"/>
                <w:ind w:left="72"/>
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
            </w:pPr>
            <w:r>
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
                <w:t>https://svn.mec.gov.br/simec/simec/trunk/docs/05-Agil/{$diretorio}release-{$release}/sprint-{$sprint}/AnaliseFuncionalidadesHistoria{$row->artifact_id}.docx</w:t>
            </w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="1188" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            </w:tcBorders>
            <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p w:rsidR="00D00794" w:rsidRPr="000E67A5" w:rsidRDefault="00D00794" w:rsidP="00FE71AC">
            <w:pPr>
                <w:pStyle w:val="Ttulodatabela"/>
                <w:spacing w:after="0"/>
                <w:ind w:left="72"/>
                <w:rPr>
                    <w:rFonts w:eastAsia="Times New Roman"/>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                    <w:lang w:eastAsia="en-US"/>
                </w:rPr>
            </w:pPr>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="40" w:type="dxa"/>
            <w:tcBorders>
                <w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/>
            </w:tcBorders>
            <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p w:rsidR="00D00794" w:rsidRDefault="00D00794" w:rsidP="00FE71AC">
            <w:pPr>
                <w:snapToGrid w:val="0"/>
                <w:jc w:val="center"/>
            </w:pPr>
        </w:p>
    </w:tc>
</w:tr>
XML;
        }

        $contador++;
        $contadorString = sprintf('%02d', $contador);
        $linhas .= <<<XML
<w:tr w:rsidR="00D00794" w:rsidTr="00FE71AC">
    <w:trPr>
        <w:trHeight w:val="355"/>
    </w:trPr>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="426" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            </w:tcBorders>
            <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p w:rsidR="00D00794" w:rsidRPr="009B5737" w:rsidRDefault="00D00794" w:rsidP="00FE71AC">
            <w:pPr>
                <w:pStyle w:val="Ttulodatabela"/>
                <w:spacing w:after="0"/>
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
            </w:pPr>
            <w:r>
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
                <w:t>{$contadorString}</w:t>
            </w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="2693" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            </w:tcBorders>
            <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p w:rsidR="00B064D7" w:rsidRPr="009B5737" w:rsidRDefault="00D00794" w:rsidP="00B064D7">
            <w:pPr>
                <w:pStyle w:val="Ttulodatabela"/>
                <w:spacing w:after="0"/>
                <w:ind w:left="72"/>
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
            </w:pPr>
            <w:r w:rsidRPr="009B5737">
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
                <w:t xml:space="preserve">Script</w:t>
            </w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="5528" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            </w:tcBorders>
            <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p w:rsidR="00D00794" w:rsidRPr="00D00794" w:rsidRDefault="00D00794" w:rsidP="00FE71AC">
            <w:pPr>
                <w:pStyle w:val="Ttulodatabela"/>
                <w:spacing w:after="0"/>
                <w:ind w:left="72"/>
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
            </w:pPr>
            <w:r>
                <w:rPr>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                </w:rPr>
                <w:t>https://svn.mec.gov.br/simec/simec/trunk/docs/05-Agil/{$diretorio}release-{$release}/sprint-{$sprint}/scripts/</w:t>
            </w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="1188" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>
            </w:tcBorders>
            <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p w:rsidR="00D00794" w:rsidRPr="000E67A5" w:rsidRDefault="00D00794" w:rsidP="00FE71AC">
            <w:pPr>
                <w:pStyle w:val="Ttulodatabela"/>
                <w:spacing w:after="0"/>
                <w:ind w:left="72"/>
                <w:rPr>
                    <w:rFonts w:eastAsia="Times New Roman"/>
                    <w:b w:val="0"/>
                    <w:i w:val="0"/>
                    <w:sz w:val="16"/>
                    <w:szCs w:val="16"/>
                    <w:lang w:eastAsia="en-US"/>
                </w:rPr>
            </w:pPr>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="40" w:type="dxa"/>
            <w:tcBorders>
                <w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/>
            </w:tcBorders>
            <w:shd w:val="clear" w:color="auto" w:fill="auto"/>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p w:rsidR="00D00794" w:rsidRDefault="00D00794" w:rsidP="00FE71AC">
            <w:pPr>
                <w:snapToGrid w:val="0"/>
                <w:jc w:val="center"/>
            </w:pPr>
        </w:p>
    </w:tc>
</w:tr>
XML;

        return $linhas;
    }


    /**
     * @param $dirTmp
     * @param $projeto
     * @param $release
     * @return string
     */
    private static function criarRoteiro ($dirProjeto, $projeto, $release)
    {
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
    }

    /**
     * @param $projeto
     * @param $release
     * @param $dirProjeto
     * @return string
     */
    private static function criarTermoEntrega ($projeto, $release, $storys, $dirRelease)
    {
        self::converterTemplate(
            TEMPLATE_TERMO_ENTREGA,
            [
                '##_NOME_SISTEMA_##',
                '##_UNIX_NAME_##',
                '##_NUMERO_RELEASE_##',
                '##_NUMERO_SPRINT_##',
                '##_TABELA_TERMO_##',
                '##_CAMINHO_MER_##'
            ],
            [
                $projeto->group_name,
                $projeto->unix_group_name,
                $release,
                $_REQUEST['sprint'],
                self::converteEmLinhaTabela($projeto->diretorio, $projeto->unix_group_name, $release, $_REQUEST['sprint'], $storys),
                "https://svn.mec.gov.br/simec/simec/trunk/docs/01-Especificacao/Banco de dados/Modelo de dados/{$projeto->caminho_mer}"
            ],
            "{$dirRelease}/termoDeEntrega-sprint-{$_REQUEST['sprint']}.docx"
        );
    }

    /**
     * @param $projeto
     * @param $release
     * @param $dirRelease
     */
    private static function criarStorys ($projeto, $release, $storys, $dirRelease)
    {
        $story = [];
        foreach ($storys as $row)
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
            //Util::criaPasta("{$dirRelease}/historia-{$key}");
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
                    '##_FUNCIONALIDADE_##',
                    '##_CAMINHO_MER_##'
                ],
                [
                    $projeto->group_name,
                    $projeto->unix_group_name,
                    $release,
                    $_REQUEST['sprint'],
                    $key,
                    "{$row['como_demonstrar']} {$row['acceptance_criteria_1']}",
                    $row['observao'],
                    (!empty($row['in_order_to_1']) ? $row['in_order_to_1'] : $row['i_want_to']),
                    "https://svn.mec.gov.br/simec/simec/trunk/docs/01-Especificacao/Banco de dados/Modelo de dados/{$projeto->caminho_mer}"
                ],
                "{$dirRelease}/AnaliseFuncionalidadesHistoria{$key}.docx");
        }
    }
}