<?php
Portal\Template::carregarTemplate($tpl);

$tpl->CAMINHO_PAGINA = '';
$tpl->NOME_PAGINA = 'Carregar dados do Tuleap';

$tpl->addFile('CONTEUDO_CENTRAL', DIRETORIO_VIEW . '/carga.html');

$cargas = [
    [
        'TIPO' => 'artifacts',
        'NOME' => 'Carregar Artefato',
        'FUNC' => 'inserirDadosArtifacts'
    ],
    [
        'TIPO' => 'usuario',
        'NOME' => 'Carregar Usuário',
        'FUNC' => 'inserirDadosUsuario'
    ]
];

$saida = '';
$time_start = microtime(true);

$tuleap = new Tuleap\Tuleap($_SESSION ['TULEAP_USER'], $_SESSION ['TULEAP_PASS']);
foreach ($cargas as $index => $item)
{
    if (isset($_POST[$item['TIPO']]))
    {
        // verifica se a um processo registrado no momento
        if (!Portal\Cache::getMemcache(Portal\Gestao::CARGA_TULEAP))
        {
            // registra o processo do Tuleap
            Portal\Cache::setMemcache(Portal\Gestao::CARGA_TULEAP, true);

            // executa a carga do Tuleap
            $saida = $tuleap->{$item['FUNC']}();

            // desregistra o processo do Tuleap
            Portal\Cache::setMemcache(Portal\Gestao::CARGA_TULEAP, false);
        }
        else
        {
            $saida = 'Carga do Tuleap já está em execução no momento para outro usuário.';
        }
    }

    $tpl->TIPO = $item['TIPO'];
    $tpl->NOME = $item['NOME'];
    $tpl->block('BLOCK_CARGA');

}
$time = microtime(true) - $time_start;

$tpl->SAIDA_WS = "Tempo de Processamento: {$time} segundos<pre>{$saida}</pre>";

$tpl->show();