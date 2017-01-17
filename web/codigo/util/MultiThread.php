<?php

/**
 * Created by PhpStorm.
 * User: saulocorreia
 * Date: 1/16/2017
 * Time: 1:24 PM
 */
class MultiThread
{
    private $threads = [];

    /**
     * Monta uma thread com uma classe Runnable e um nome e a adiciona numa lista de Threads.
     * @param runnable
     * @throws Exception
     */
    public function add ($runnable)
    {
        if (!empty($runnable))
        {
            $this->threads[] = $runnable;
        }
        else
        {
            throw new Exception ("Runnable nao pode ser NULO!");
        }
    }


    /**
     * Monta uma thread com uma classe Runnable e um nome e a adiciona numa lista de Threads.
     * @param runnable
     * @throws Exception
     */
    public function addStarted ($runnable)
    {
        if (!empty($runnable))
        {
            $this->threads[] = $runnable;
            $runnable->start();
        }
        else
        {
            throw new Exception ("Runnable nao pode ser NULO!");
        }
    }

    /**
     * Inicia todas as thread da lista.
     */
    public function start ()
    {
        for ($i = 0; $i < count($this->threads); $i++)
            $this->threads[$i]->start();
    }

    /**
     * Aguarda todas as threads morrem naturalmente.
     */
    public function join ()
    {
        for ($i = 0; $i < count($this->threads); $i++)
        {
            !$this->threads[$i]->join();
        }
    }
}