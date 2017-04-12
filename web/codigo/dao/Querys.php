<?php

class Querys
{
    // ############################################################
    // ##........................CREATE..........................##
    // ############################################################
    const CREATE_TABLE = <<<SQL
CREATE TABLE IF NOT EXISTS tb_usuario (
    id_usuario INTEGER PRIMARY KEY AUTOINCREMENT,
    nome,
    usuario,
    senha,
    perfil,
    ativo DEFAULT 'S',
    tuleap_user,
    tuleap_pass,
    data_insercao DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS tb_group (
    group_id INTEGER,
    group_name,
    unix_group_name,
    description,
    data_insercao DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS tb_tracker (
    tracker_id INTEGER,
    group_id   INTEGER,
    name,
    description,
    item_name,
    data_insercao DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS tb_artifact (
    artifact_id INTEGER,
    tracker_id  INTEGER,
    group_id    INTEGER,
    submitted_by DATETIME,
    submitted_on DATETIME,
    last_update_date DATETIME,
    type,
    data_insercao DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS tb_cross_references (
    artifact_id,
    ref,
    url,
    data_insercao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tb_field (
    field_id INTEGER PRIMARY KEY AUTOINCREMENT,
    artifact_id,
    field_name,
    field_label,
    field_value,
    data_insercao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tb_bind (
    bind_id INTEGER PRIMARY KEY AUTOINCREMENT,
    field_id,
    bind_value_id,
    bind_value_label,
    bind_value,
    data_insercao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_cross_id
    ON tb_cross_references (artifact_id);
CREATE INDEX IF NOT EXISTS idx_artefato_id
    ON tb_artifact (artifact_id);
CREATE INDEX IF NOT EXISTS idx_tracker_id
    ON tb_tracker (tracker_id);
CREATE INDEX IF NOT EXISTS idx_grupo_id
    ON tb_group (group_id);
SQL;

    // ############################################################
    // ##........................USUARIO.........................##
    // ############################################################
    const SELECT_LOGIN = 'SELECT * FROM tb_usuario WHERE usuario = ? and senha = ? and ativo = \'S\'';
    const SELECT_USUARIO_BY_USUARIO_ATIVO = 'SELECT * FROM tb_usuario WHERE usuario = ? AND ativo = \'S\'';
    const SELECT_USUARIO = 'SELECT * FROM tb_usuario WHERE 1 = 1 ORDER BY nome';
    const SELECT_USUARIO_BY_ID = 'SELECT * FROM tb_usuario WHERE usuario_id = ? ';

    const INSERT_USUARIO = 'INSERT INTO tb_usuario (nome,usuario,senha,perfil) VALUES (?,?,?,?)';
    const INSERT_USUARIO_TULEAP = 'INSERT INTO tb_usuario (usuario_id,nome,usuario,senha,perfil,tuleap_user) VALUES (?,?,?,?,?,?)';

    const UPDATE_USUARIO = 'UPDATE tb_usuario SET nome = ?, usuario = ? WHERE usuario_id = ?';
    const UPDATE_USUARIO_PERFIL = 'UPDATE tb_usuario SET nome = ?, usuario = ?, perfil = ? WHERE usuario_id = ?';
    const UPDATE_USUARIO_ATIVO = 'UPDATE tb_usuario SET ativo = ? WHERE usuario_id = ?';
    const UPDATE_USUARIO_SENHA = 'UPDATE tb_usuario SET nome = ?, usuario = ?, senha = ?, tuleap_user = ?, tuleap_pass = ? WHERE usuario_id = ?';
    const UPDATE_USUARIO_RESET_SENHA = 'UPDATE tb_usuario SET senha = ? WHERE usuario_id = ?';

    // ############################################################
    // ##.........................GROUP..........................##
    // ############################################################
    const SELECT_PROJETO = 'SELECT group_id, unix_group_name, group_name FROM tb_group ORDER BY 3, 2';
    const SELECT_PROJETO_BY_ID = 'SELECT * FROM tb_group WHERE group_id = ?';
    const SELECT_PROJETO_CONF_BY_ID = 'SELECT g.group_id, g.group_name, g.description, g.data_insercao, ifnull (c.unix_group_name, g.unix_group_name) unix_group_name FROM tb_group g LEFT JOIN tb_configuracao c USING (group_id) WHERE g.group_id = ?';
    const SELECT_PROJETO_BY_ARTIFACT_ID = 'SELECT g.* FROM tb_group g JOIN tb_artifact a USING (group_id) WHERE a.artifact_id = ?';

    const INSERT_PROJETO = 'INSERT INTO tb_group (group_id, group_name, unix_group_name, description) VALUES (?,?,?,?)';

    const UPDATE_PROJETO = 'UPDATE tb_group SET group_name = ?, unix_group_name = ?, description = ? WHERE group_id = ?';

    // ############################################################
    // ##........................TRACKER.........................##
    // ############################################################
    const SELECT_TRACKER_BY_ID = 'SELECT * FROM tb_tracker WHERE tracker_id = ?';

    const INSERT_TRACKER = 'INSERT INTO tb_tracker (tracker_id, group_id, name, description, item_name) VALUES (?,?,?,?,?)';

    const UPDATE_TRACKER = 'UPDATE tb_tracker SET group_id = ?, name = ?, description = ?, item_name = ? WHERE tracker_id = ?';

    // ############################################################
    // ##.......................ARTIFACT.........................##
    // ############################################################
    const SELECT_ARTIFACT_BY_ID = 'SELECT * FROM tb_artifact WHERE artifact_id = ?';
    const SELECT_RELEASE_BY_ARTIFACT_ID = 'SELECT CAST(substr(ref, 6) AS DECIMAL) AS rel FROM tb_artifact a JOIN tb_cross_references c USING (artifact_id) WHERE c.artifact_id = ? AND c.ref LIKE \'rel%\'';

    const INSERT_ARTIFACT = 'INSERT INTO tb_artifact (artifact_id, tracker_id, group_id, submitted_by, submitted_on, last_update_date, type) VALUES (?,?,?,?,?,?,?)';
    const DELETE_ARTIFACT = 'DELETE FROM tb_artifact WHERE artifact_id = ?';
    const UPDATE_ARTIFACT = 'UPDATE tb_artifact SET tracker_id = ?, group_id = ?, submitted_by = ?, submitted_on = ?, last_update_date = ?, type = ? WHERE artifact_id = ?';

    // ############################################################
    // ##.....................CROSS_REFERENCE....................##
    // ############################################################
    const SELECT_CROSS_REFERENCE_BY_ALL = 'SELECT * FROM tb_cross_references WHERE artifact_id = ? AND ref = ? AND url = ?';

    const DELETE_CROSS_REFERENCE = 'DELETE FROM tb_cross_references WHERE artifact_id = ?';

    const INSERT_CROSS_REFERENCE = 'INSERT INTO tb_cross_references (artifact_id, ref, url) VALUES (?,?,?)';

    // ############################################################
    // ##.........................FIELD..........................##
    // ############################################################
    const SELECT_FIELD_BY_ARTIF_ID_FIELD_NAME = 'SELECT * FROM tb_field WHERE artifact_id = ? AND field_name = ?';

    const DELETE_FIELD = 'DELETE FROM tb_field WHERE artifact_id = ?';

    const INSERT_FIELD = 'INSERT INTO tb_field (artifact_id, field_name, field_label, field_value) VALUES (?,?,?,?)';

    const UPDATE_FIELD = 'UPDATE tb_field SET field_value = ? WHERE field_id = ?';

    // ############################################################
    // ##.....................CONFIGURACAO.......................##
    // ############################################################
    const SELECT_CONFIGURACAO_BY_GROUP_ID = 'SELECT * FROM tb_configuracao WHERE group_id = ?';
    
    const INSERT_REPLACE_CONFIGURACAO = 'INSERT OR REPLACE INTO tb_configuracao (group_id, unix_group_name) VALUES (?,?)';

    // ############################################################
    // ##.......................DASHBOARD........................##
    // ############################################################
    const SELECT_DASHBOARD = <<<SQL
SELECT
    group_name
    , group_id
    , type
    , SUM(qtd)                 AS qtd
    , CASE
      WHEN type = 'release'
          THEN '-'
      ELSE SUM(qtd_aberto) END AS qtd_aberto
FROM (
    SELECT
        g.group_name
        , g.group_id
        , a.type
        , COUNT(DISTINCT a.artifact_id) AS qtd
        , sum(CASE
              WHEN (
                  f.field_name LIKE '%status%'
                  AND lower(f.field_value) NOT IN ('done', 'close')
              ) THEN 1
              ELSE 0
              END)                      AS qtd_aberto
    FROM tb_group g
        JOIN tb_tracker t USING (group_id)
        JOIN tb_artifact a USING (tracker_id, group_id)
        JOIN tb_field f USING (artifact_id)
    GROUP BY g.group_name
        , g.group_id
        , a.type
    UNION ALL
    SELECT
        g.group_name
        , g.group_id
        , type
        , 0
        , CASE
          WHEN type = 'release'
              THEN '-'
          ELSE 0 END AS qtd_aberto
    FROM tb_group g, (
                         SELECT DISTINCT type
                         FROM tb_artifact) a
)
GROUP BY group_name
    , group_id
    , type
SQL;

    const SELECT_FIELDS_HTML_BY_ARTIFACT = <<<SQL
    SELECT '<b>' || field_label || ':</b> ' as label,  ifnull(field_value, '') || '<br>' AS value
FROM tb_field f
WHERE artifact_id = ?
ORDER BY 1
SQL;

    const SELECT_SPRINT_RELEASE_BY_GROUP_ID = <<<SQL
SELECT
      a.artifact_id  AS sprint
    , substr(ref, 6) AS rel
FROM tb_group g
    JOIN tb_tracker t USING (group_id)
    JOIN tb_artifact a USING (tracker_id, group_id)
    JOIN tb_cross_references c USING (artifact_id)
WHERE t.item_name = 'sprint'
      AND g.group_id = ?
      AND c.ref LIKE 'rel%'
ORDER BY 2, 1
SQL;

    const SELECT_VALUES_BY_SPRINT = <<<SQL
SELECT
    f.artifact_id
    , f.field_name
    , f.field_value
FROM tb_field f
WHERE f.artifact_id IN (
    SELECT CAST(substr(ref, 8) AS DECIMAL) AS historia
    FROM tb_artifact a
        JOIN tb_cross_references c USING (artifact_id)
    WHERE c.artifact_id = ? AND c.ref LIKE 'story%') AND
      f.field_name IN ('como_demonstrar', 'observao', 'in_order_to_1', 'acceptance_criteria_1', 'i_want_to')
SQL;

    const SELECT_STORY_BY_SPRINT = <<<SQL
SELECT CAST(substr(ref, 8) AS DECIMAL) AS historia
FROM tb_artifact a
    JOIN tb_cross_references c USING (artifact_id)
WHERE c.artifact_id = ?
      AND c.ref LIKE 'story%'
SQL;


    const SELECT_TREE_BY_GROUPID = <<<SQL
WITH cross AS (SELECT
                   *
                   , CAST(substr(c.ref, instr(c.ref, '#') + 1) AS DECIMAL) art_id
               FROM tb_artifact a
                   JOIN tb_cross_references c USING (artifact_id)),
        status AS (
        SELECT
            LOWER(field_value) AS status
            , a.artifact_id
        FROM tb_field f
            JOIN tb_artifact a USING (artifact_id)
        WHERE field_name LIKE '%status%')
SELECT
    rel
    , sprint
    , status_sprint
    , story
    , status_story
    , task
    , status_task
FROM
    (
        SELECT
            rel
            , sprint
            , (
                  SELECT status
                  FROM status s
                  WHERE s.artifact_id = sprint)    AS status_sprint
            , story
            , (
                  SELECT status
                  FROM status s
                  WHERE s.artifact_id = story)     AS status_story
            , tk.art_id                            AS task
            , (
                  SELECT status
                  FROM status s
                  WHERE s.artifact_id = tk.art_id) AS status_task
        FROM (
                 SELECT
                     rel
                     , sprint
                     , st.art_id AS story
                 FROM (
                          SELECT
                                sp.artifact_id AS rel
                              , sp.art_id      AS sprint
                          FROM CROSS sp
                          WHERE sp.type = 'release'
                                AND sp.ref LIKE 'sprint%'
                                AND sp.group_id = ?
                      ) sp
                     LEFT JOIN CROSS st
                         ON sp.sprint = st.artifact_id AND st.type = 'sprint' AND st.ref LIKE 'story%'
             ) st
            LEFT JOIN CROSS tk ON st.story = tk.artifact_id AND tk.type = 'story' AND tk.ref LIKE 'task%')
ORDER BY rel DESC, sprint, story, task
SQL;

    const SELECT_UNRELATED_BY_GROUPID = <<<SQL
WITH cross AS (
    SELECT
        *
        , CAST(substr(c.ref, instr(c.ref, '#') + 1) AS DECIMAL) art_id
    FROM tb_artifact a
        LEFT JOIN tb_cross_references c USING (artifact_id)
),
        status AS (
        SELECT
            LOWER(field_value) AS status
            , a.artifact_id
        FROM tb_field f
            JOIN tb_artifact a USING (artifact_id)
        WHERE field_name LIKE '%status%'
    ),
        relacionados AS (
        SELECT
            rel
            , sprint
            , story
            , tk.art_id AS task
        FROM (
                 SELECT
                     rel
                     , sprint
                     , st.art_id AS story
                 FROM (
                          SELECT
                                sp.artifact_id AS rel
                              , sp.art_id      AS sprint
                          FROM CROSS sp
                          WHERE sp.type = 'release'
                                AND sp.ref LIKE 'sprint%'
                                AND sp.group_id = ?
                      ) sp
                     LEFT JOIN CROSS st
                         ON sp.sprint = st.artifact_id AND st.type = 'sprint' AND st.ref LIKE 'story%'
             ) st
            LEFT JOIN CROSS tk ON st.story = tk.artifact_id AND tk.type = 'story' AND tk.ref LIKE 'task%'
    ),
        todos_ids AS (
        SELECT artifact_id
        FROM tb_artifact
        WHERE group_id = ?
    ),
        relacionados_ids AS (
        SELECT rel
        FROM relacionados
        UNION ALL
        SELECT sprint
        FROM relacionados
        UNION ALL
        SELECT story
        FROM relacionados
        UNION ALL
        SELECT task
        FROM relacionados
    ),
        restantes_id AS (
        SELECT *
        FROM todos_ids
        EXCEPT
        SELECT *
        FROM relacionados_ids
    )
SELECT
      MAX(rel)          rel
    , story
    , MAX(status_story) status_story
    , MAX(task)         task
    , MAX(status_task)  status_task
FROM (
    SELECT
        rel
        , story
        , (
              SELECT status
              FROM status s
              WHERE s.artifact_id = story) AS status_story
        , task
        , (
              SELECT status
              FROM status s
              WHERE s.artifact_id = task)  AS status_task

    FROM (
        SELECT
              CASE WHEN a.type = 'release' THEN sp.art_id END    AS rel
            , CASE WHEN sp.type = 'story' THEN r.artifact_id END AS story
            , CASE WHEN a.type = 'task' THEN sp.art_id END       AS task
        FROM restantes_id r
            LEFT JOIN CROSS sp ON r.artifact_id = sp.artifact_id
            LEFT JOIN tb_artifact a ON (sp.art_id = a.artifact_id)
        WHERE sp.type = 'story'
              OR sp.type IS NULL
    )
)
GROUP BY story
ORDER BY rel desc, story, task
SQL;


}