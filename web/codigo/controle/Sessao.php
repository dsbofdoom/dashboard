<?php
if (!isset ($_SESSION ['ID_USUARIO']))
{
    session_destroy();

    header("Location: " . DIRETORIO_RAIZ);
    exit ();
}
else
{
    if (DEBUG)
    {
        $retorno = UtilDAO::getResult(Querys::SELECT_USUARIO_BY_ID, $_SESSION ['ID_USUARIO']);

        $_SESSION ['NOME_USUARIO'] = $retorno [0]->nome;
        $_SESSION ['ID_USUARIO'] = $retorno [0]->usuario_id;
        $_SESSION ['USUARIO'] = $retorno [0]->usuario;
        $_SESSION ['PERFIL'] = $retorno [0]->perfil;
        $_SESSION ['TULEAP_USER'] = $retorno [0]->tuleap_user;
        $_SESSION ['TULEAP_PASS'] = $retorno [0]->tuleap_pass;
    }
}
?>