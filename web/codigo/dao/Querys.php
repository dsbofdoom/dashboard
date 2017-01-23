<?php

class Querys
{
    // ############################################################
    // ##........................CREATE..........................##
    // ############################################################
    const CREATE_TABLE = <<<SQL
CREATE TABLE IF NOT EXISTS tb_usuario (
    id_usuario SERIAL,
    nome,
    usuari,
    senha,
    perfil,
    ativo
);
CREATE TABLE IF NOT EXISTS tb_grupo (
    group_id INTEGER,
    group_name,
    unix_group_name,
    description
);
CREATE TABLE IF NOT EXISTS tb_tracker (
    tracker_id INTEGER,
    group_id   INTEGER,
    name,
    description,
    item_name
);
CREATE TABLE IF NOT EXISTS tb_artefato (
    artifact_id INTEGER,
    tracker_id  INTEGER,
    group_id    INTEGER,
    submitted_by,
    submitted_on,
    last_update_date
);
CREATE TABLE IF NOT EXISTS tb_cross_references (artifact_id, REF, url
);

CREATE INDEX IF NOT EXISTS idx_cross_id
    ON tb_cross_references (artifact_id);
CREATE INDEX IF NOT EXISTS idx_artefato_id
    ON tb_artefato (artifact_id);
CREATE INDEX IF NOT EXISTS idx_tracker_id
    ON tb_tracker (tracker_id);
CREATE INDEX IF NOT EXISTS idx_grupo_id
    ON tb_grupo (group_id);
SQL;

    // ############################################################
    // ##........................USUARIO.........................##
    // ############################################################
    const SELECT_LOGIN = "SELECT * FROM tb_usuario WHERE usuario = ? and senha = ? and ativo = 'S'";
    const SELECT_USUARIO = "SELECT * FROM tb_usuario WHERE 1 = 1 ORDER BY nome";
    const SELECT_USUARIO_BY_ID = "SELECT * FROM tb_usuario WHERE id_usuario = ? ";

    const INSERT_USUARIO = "INSERT INTO tb_usuario (nome,usuario,senha,perfil) VALUES (?,?,?,?)";

    const UPDATE_USUARIO = "UPDATE tb_usuario SET nome = ?, usuario = ? WHERE id_usuario = ?";
    const UPDATE_USUARIO_PERFIL = "UPDATE tb_usuario SET nome = ?, usuario = ?, perfil = ? WHERE id_usuario = ?";
    const UPDATE_USUARIO_ATIVO = "UPDATE tb_usuario SET ativo = ? WHERE id_usuario = ?";
    const UPDATE_USUARIO_SENHA = "UPDATE tb_usuario SET nome = ?, usuario = ?, senha = ? WHERE id_usuario = ?";
    const UPDATE_USUARIO_RESET_SENHA = "UPDATE tb_usuario SET senha = ? WHERE id_usuario = ?";

    // ############################################################
    // ##.........................GRUPO..........................##
    // ############################################################
    const SELECT_PROJETO_BY_ID = "SELECT * FROM tb_grupo WHERE group_id = ?";

    const INSERT_PROJETO = "INSERT INTO tb_grupo (group_id, group_name, unix_group_name, description) VALUES (?,?,?,?)";

    const UPDATE_PROJETO = "UPDATE tb_grupo SET group_name = ?, unix_group_name = ?, description = ? WHERE group_id = ?";

    // ############################################################
    // ##........................TRACKER.........................##
    // ############################################################
    const SELECT_TRACKER_BY_ID = "SELECT * FROM tb_tracker WHERE tracker_id = ?";

    const INSERT_TRACKER = "INSERT INTO tb_tracker (tracker_id, group_id, name, description, item_name) VALUES (?,?,?,?,?)";

    const UPDATE_TRACKER = "UPDATE tb_tracker SET group_id = ?, name = ?, description = ?, item_name = ? WHERE tracker_id = ?";

    // ############################################################
    // ##.......................ARTEFATO.........................##
    // ############################################################
    const SELECT_ARTIFACT_BY_ID = "SELECT * FROM tb_artefato WHERE artifact_id = ?";

    const INSERT_ARTIFACT = "INSERT INTO tb_artefato (artifact_id, tracker_id, group_id, submitted_by, submitted_on, last_update_date) VALUES (?,?,?,?,?,?)";

    const UPDATE_ARTIFACT = "UPDATE tb_artefato SET tracker_id = ?, group_id = ?, submitted_by = ?, submitted_on = ?, last_update_date = ? WHERE artifact_id = ?";

    // ############################################################
    // ##.....................CROSS_REFERENCE....................##
    // ############################################################
    const DELETE_CROSS_REFERENCE = "DELETE FROM tb_cross_references WHERE artifact_id = ?";
    
    const INSERT_CROSS_REFERENCE = "INSERT INTO tb_cross_references (artifact_id, ref, url) VALUES (?,?,?)";

}