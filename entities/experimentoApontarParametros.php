<?php

class ExperimentoApontarParametros {
    
    public $codSessaoExperimento;
    public $objetivoX;
    public $objetivoY;
    public $algoritmoBusca;
    public $obstaculos;
    public $kp;
    public $kd;
    public $ki;
    public $tamanhoMapaBusca;
    public $tamanhoAreaSeguranca;
    public $dtCriacao;
    
    function __construct($codSessaoExperimento = null, $algoritmoBusca = null, $obstaculos = null, $kp = null, $kd = null, $ki = null, $tamanhoMapaBusca = null, $tamanhoAreaSeguranca = null, $dtCriacao = null) {
        $this->codSessaoExperimento = $codSessaoExperimento;
        $this->algoritmoBusca = $algoritmoBusca;
        $this->obstaculos = $obstaculos;
        $this->kp = $kp;
        $this->kd = $kd;
        $this->ki = $ki;
        $this->tamanhoMapaBusca = $tamanhoMapaBusca;
        $this->tamanhoAreaSeguranca = $tamanhoAreaSeguranca;
        $this->dtCriacao = $dtCriacao;
    }

    function getCodSessaoExperimento() {
        return $this->codSessaoExperimento;
    }

    function getAlgoritmoBusca() {
        return $this->algoritmoBusca;
    }

    function getObstaculos() {
        return $this->obstaculos;
    }

    function getKp() {
        return $this->kp;
    }

    function getKd() {
        return $this->kd;
    }

    function getKi() {
        return $this->ki;
    }

    function getTamanhoMapaBusca() {
        return $this->tamanhoMapaBusca;
    }

    function getTamanhoAreaSeguranca() {
        return $this->tamanhoAreaSeguranca;
    }

    function getDtCriacao() {
        return $this->dtCriacao;
    }

    function setCodSessaoExperimento($codSessaoExperimento) {
        $this->codSessaoExperimento = $codSessaoExperimento;
    }

    function setAlgoritmoBusca($algoritmoBusca) {
        $this->algoritmoBusca = $algoritmoBusca;
    }

    function setObstaculos($obstaculos) {
        $this->obstaculos = $obstaculos;
    }

    function setKp($kp) {
        $this->kp = $kp;
    }

    function setKd($kd) {
        $this->kd = $kd;
    }

    function setKi($ki) {
        $this->ki = $ki;
    }

    function setTamanhoMapaBusca($tamanhoMapaBusca) {
        $this->tamanhoMapaBusca = $tamanhoMapaBusca;
    }

    function setTamanhoAreaSeguranca($tamanhoAreaSeguranca) {
        $this->tamanhoAreaSeguranca = $tamanhoAreaSeguranca;
    }

    function setDtCriacao($dtCriacao) {
        $this->dtCriacao = $dtCriacao;
    }
    
    function getObjetivoX() {
        return $this->objetivo_x;
    }

    function getObjetivoY() {
        return $this->objetivo_y;
    }

    function setObjetivoX($objetivo_x) {
        $this->objetivo_x = $objetivo_x;
    }

    function setObjetivoY($objetivo_y) {
        $this->objetivo_y = $objetivo_y;
    }




}
