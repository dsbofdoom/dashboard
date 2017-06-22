<?php

// DEBUG
const DEBUG = true;

// Configuracoes do Portal
const TITULO_PAGINA = "Dashboard";
const NOME_SISTEMA = "Dashboard";
const EMPRESA_SISTEMA = "Cast Group Inc.";
const ANO_SISTEMA = "2017";

const DIRETORIO_RAIZ = DIRECTORY_SEPARATOR . 'dashboard';
const DIRETORIO_VIEW = 'view';
const DIRETORIO_CONTROLE = 'controle';
const DIRETORIO_RAIZ_VIEW = DIRETORIO_RAIZ . DIRECTORY_SEPARATOR . DIRETORIO_VIEW;
const DIRETORIO_RAIZ_CONTROLE = DIRETORIO_RAIZ . DIRECTORY_SEPARATOR . DIRETORIO_CONTROLE;

const CHAMADA_AJAX = DIRETORIO_RAIZ_CONTROLE . DIRECTORY_SEPARATOR . 'JSON';
const PAGINA_PRINCIPAL = DIRETORIO_RAIZ_VIEW . DIRECTORY_SEPARATOR . 'default';

define('PAGINA_DEFAULT', $_SERVER ['DOCUMENT_ROOT'] . DIRETORIO_RAIZ_VIEW . '/pagina.php');
define("DIRETORIO_IMPORT", $_SERVER ['DOCUMENT_ROOT'] . DIRETORIO_RAIZ);
define('ENDERECO_RAIZ', "http://{$_SERVER['HTTP_HOST']}" . DIRETORIO_RAIZ);

// Configuracoes de Banco
const BANCO_USUARIO = 'postgres';
const BANCO_SENHA = 'qwe123';
const BANCO_URL = 'host=localhost port=5432 dbname=postgres user=' . BANCO_USUARIO . ' password=' . BANCO_SENHA;

// Configuracao de Perfil
const PERFIL_0_ADMIN = "0";
const PERFIL_1_ESCRITA = "1";
const PERFIL_2_CONSULTA = "2";

// Constantes dos arquivos de template 
define('TEMPLATE_ROTEIRO', $_SERVER ['DOCUMENT_ROOT'] . DIRETORIO_RAIZ . '/arquivos/roteiro');
define('TEMPLATE_TERMO_ENTREGA', $_SERVER ['DOCUMENT_ROOT'] . DIRETORIO_RAIZ . '/arquivos/termo');
define('TEMPLATE_HISTORIA', $_SERVER ['DOCUMENT_ROOT'] . DIRETORIO_RAIZ . '/arquivos/AnaliseFuncionalidades');