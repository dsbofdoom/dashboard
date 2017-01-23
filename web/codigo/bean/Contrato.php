<?php

class Contrato
{
    // ########################################################################
    // ##..............................PRIVATE...............................##
    // ########################################################################
    private $id_contrato;
    private $versao;
    private $lote;
    private $edital;
    private $programa;
    private $processo_edital;
    private $processo;
    private $uf;
    private $valor_contratual;
    private $id_empresa;
    private $id_consorcio;
    private $contrato;
    private $etapa;
    private $coordenacao;
    private $dat_base;
    private $dat_proposta;
    private $inicio_vigencia;
    private $prazo_vigencia;
    private $prorrogacao_vigencia;
    private $termino_vigencia;
    private $malha_contratada;
    private $valor_contratual_vigente;
    private $valor_reajuste;
    private $valor_pi_r;
    private $dat_homologacao;
    private $dat_assinatura;
    private $representante_legal;
    private $dat_inicio_servico;
    private $dat_termino_servico;
    private $prorrogacao_servico;
    private $observacao;
    private $dat_vigencia_garantia;
    private $percentual_garantia;
    private $valor_garantia;
    private $dat_envio_responsavel;
    private $dat_prevista_responsavel;
    private $dat_recebimento_responsavel;
    private $dat_envio_assinatura;
    private $dat_prevista_assinatura;
    private $dat_recebimento_assinatura;
    private $dat_inicio_civil;
    private $dat_fim_civil;
    private $dat_inicio_risco;
    private $dat_fim_risco;
    private $dat_inicio_seguro;
    private $dat_fim_seguro;
    private $id_usuario;
    private $tipo_contrato;
    private $dat_entrada;
    private $flag_exclusao;

    // ########################################################################
    // ##...............................FUNCOES..............................##
    // ########################################################################
    public function __construct (array $dados)
    {
        foreach (get_object_vars($this) as $key => $value)
        {
            if (isset ($dados [$key]))
            {
                $this->{$key} = $dados [$key];
            }
        }
    }

    public function getValues (...$colunas)
    {
        if ($colunas == null)
        {
            return get_object_vars($this);
        }

        $este = get_object_vars($this);
        $retorno = [];
        foreach ($colunas as $key => $valor)
        {
            if (array_key_exists($valor, $este))
            {
                $retorno [count($retorno)] = $este [$valor];
            }
        }

        return $retorno;
    }

    // ########################################################################
    // ##...............................GETTER...............................##
    // ########################################################################
    public function getId_contrato ()
    {
        return $this->id_contrato;
    }

    public function getVersao ()
    {
        return $this->versao;
    }

    public function getLote ()
    {
        return $this->lote;
    }

    public function getEdital ()
    {
        return $this->edital;
    }

    public function getPrograma ()
    {
        return $this->programa;
    }

    public function getProcesso_edital ()
    {
        return $this->processo_edital;
    }

    public function getProcesso ()
    {
        return $this->processo;
    }

    public function getUf ()
    {
        return $this->uf;
    }

    public function getValor_contratual ()
    {
        return $this->valor_contratual;
    }

    public function getEmpresa ()
    {
        return $this->empresa;
    }

    public function getContrato ()
    {
        return $this->contrato;
    }

    public function getEtapa ()
    {
        return $this->etapa;
    }

    public function getCoordenacao ()
    {
        return $this->coordenacao;
    }

    public function getDat_base ()
    {
        return $this->dat_base;
    }

    public function getDat_proposta ()
    {
        return $this->dat_proposta;
    }

    public function getInicio_vigencia ()
    {
        return $this->inicio_vigencia;
    }

    public function getPrazo_vigencia ()
    {
        return $this->prazo_vigencia;
    }

    public function getProrrogacao_vigencia ()
    {
        return $this->prorrogacao_vigencia;
    }

    public function getTermino_vigencia ()
    {
        return $this->termino_vigencia;
    }

    public function getMalha_contratada ()
    {
        return $this->malha_contratada;
    }

    public function getValor_contratual_vigente ()
    {
        return $this->valor_contratual_vigente;
    }

    public function getValor_reajuste ()
    {
        return $this->valor_reajuste;
    }

    public function getValor_pi_r ()
    {
        return $this->valor_pi_r;
    }

    public function getDat_homologacao ()
    {
        return $this->dat_homologacao;
    }

    public function getDat_assinatura ()
    {
        return $this->dat_assinatura;
    }

    public function getRepresentante_legal ()
    {
        return $this->representante_legal;
    }

    public function getDat_inicio_servico ()
    {
        return $this->dat_inicio_servico;
    }

    public function getDat_termino_servico ()
    {
        return $this->dat_termino_servico;
    }

    public function getProrrogacao_servico ()
    {
        return $this->prorrogacao_servico;
    }

    public function getObservacao ()
    {
        return $this->observacao;
    }

    public function getDat_vigencia_garantia ()
    {
        return $this->dat_vigencia_garantia;
    }

    public function getPercentual_garantia ()
    {
        return $this->percentual_garantia;
    }

    public function getValor_garantia ()
    {
        return $this->valor_garantia;
    }

    public function getDat_envio_responsavel ()
    {
        return $this->dat_envio_responsavel;
    }

    public function getDat_prevista_responsavel ()
    {
        return $this->dat_prevista_responsavel;
    }

    public function getDat_recebimento_responsavel ()
    {
        return $this->dat_recebimento_responsavel;
    }

    public function getDat_envio_assinatura ()
    {
        return $this->dat_envio_assinatura;
    }

    public function getDat_prevista_assinatura ()
    {
        return $this->dat_prevista_assinatura;
    }

    public function getDat_recebimento_assinatura ()
    {
        return $this->dat_recebimento_assinatura;
    }

    public function getDat_inicio_civil ()
    {
        return $this->dat_inicio_civil;
    }

    public function getDat_fim_civil ()
    {
        return $this->dat_fim_civil;
    }

    public function getDat_inicio_risco ()
    {
        return $this->dat_inicio_risco;
    }

    public function getDat_fim_risco ()
    {
        return $this->dat_fim_risco;
    }

    public function getDat_inicio_seguro ()
    {
        return $this->dat_inicio_seguro;
    }

    public function getDat_fim_seguro ()
    {
        return $this->dat_fim_seguro;
    }

    public function getId_usuario ()
    {
        return $this->id_usuario;
    }

    public function getDat_entrada ()
    {
        return $this->dat_entrada;
    }

    public function getFlag_exclusao ()
    {
        return $this->flag_exclusao;
    }

    // ########################################################################
    // ##...............................SETTER...............................##
    // ########################################################################
    public function setVersao ($value)
    {
        $this->versao = $value;
    }

    public function setLote ($value)
    {
        $this->lote = $value;
    }

    public function setEdital ($value)
    {
        $this->edital = $value;
    }

    public function setPrograma ($value)
    {
        $this->programa = $value;
    }

    public function setProcesso_edital ($value)
    {
        $this->processo_edital = $value;
    }

    public function setProcesso ($value)
    {
        $this->processo = $value;
    }

    public function setUf ($value)
    {
        $this->uf = $value;
    }

    public function setValor_contratual ($value)
    {
        $this->valor_contratual = $value;
    }

    public function setEmpresa ($value)
    {
        $this->empresa = $value;
    }

    public function setContrato ($value)
    {
        $this->contrato = $value;
    }

    public function setEtapa ($value)
    {
        $this->etapa = $value;
    }

    public function setCoordenacao ($value)
    {
        $this->coordenacao = $value;
    }

    public function setDat_base ($value)
    {
        $this->dat_base = $value;
    }

    public function setDat_proposta ($value)
    {
        $this->dat_proposta = $value;
    }

    public function setInicio_vigencia ($value)
    {
        $this->inicio_vigencia = $value;
    }

    public function setPrazo_vigencia ($value)
    {
        $this->prazo_vigencia = $value;
    }

    public function setProrrogacao_vigencia ($value)
    {
        $this->prorrogacao_vigencia = $value;
    }

    public function setTermino_vigencia ($value)
    {
        $this->termino_vigencia = $value;
    }

    public function setMalha_contratada ($value)
    {
        $this->malha_contratada = $value;
    }

    public function setValor_contratual_vigente ($value)
    {
        $this->valor_contratual_vigente = $value;
    }

    public function setValor_reajuste ($value)
    {
        $this->valor_reajuste = $value;
    }

    public function setValor_pi_r ($value)
    {
        $this->valor_pi_r = $value;
    }

    public function setDat_homologacao ($value)
    {
        $this->dat_homologacao = $value;
    }

    public function setDat_assinatura ($value)
    {
        $this->dat_assinatura = $value;
    }

    public function setRepresentante_legal ($value)
    {
        $this->representante_legal = $value;
    }

    public function setDat_inicio_servico ($value)
    {
        $this->dat_inicio_servico = $value;
    }

    public function setDat_termino_servico ($value)
    {
        $this->dat_termino_servico = $value;
    }

    public function setProrrogacao_servico ($value)
    {
        $this->prorrogacao_servico = $value;
    }

    public function setObservacao ($value)
    {
        $this->observacao = $value;
    }

    public function setDat_vigencia_garantia ($value)
    {
        $this->dat_vigencia_garantia = $value;
    }

    public function setPercentual_garantia ($value)
    {
        $this->percentual_garantia = $value;
    }

    public function setValor_garantia ($value)
    {
        $this->valor_garantia = $value;
    }

    public function setDat_envio_responsavel ($value)
    {
        $this->dat_envio_responsavel = $value;
    }

    public function setDat_prevista_responsavel ($value)
    {
        $this->dat_prevista_responsavel = $value;
    }

    public function setDat_recebimento_responsavel ($value)
    {
        $this->dat_recebimento_responsavel = $value;
    }

    public function setDat_envio_assinatura ($value)
    {
        $this->dat_envio_assinatura = $value;
    }

    public function setDat_prevista_assinatura ($value)
    {
        $this->dat_prevista_assinatura = $value;
    }

    public function setDat_recebimento_assinatura ($value)
    {
        $this->dat_recebimento_assinatura = $value;
    }

    public function setDat_inicio_civil ($value)
    {
        $this->dat_inicio_civil = $value;
    }

    public function setDat_fim_civil ($value)
    {
        $this->dat_fim_civil = $value;
    }

    public function setDat_inicio_risco ($value)
    {
        $this->dat_inicio_risco = $value;
    }

    public function setDat_fim_risco ($value)
    {
        $this->dat_fim_risco = $value;
    }

    public function setDat_inicio_seguro ($value)
    {
        $this->dat_inicio_seguro = $value;
    }

    public function setDat_fim_seguro ($value)
    {
        $this->dat_fim_seguro = $value;
    }

    public function setId_usuario ($value)
    {
        $this->id_usuario = $value;
    }

    public function setFlag_exclusao ($value)
    {
        $this->flag_exclusao = $value;
    }
}