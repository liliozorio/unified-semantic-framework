<?php

namespace model;

class Pessoa
{
    private $nome;
    private $apelido;
    private $dt_nascimento;
    private $imagem;
    private $sexo;
    private $idade;
    private $cidade;
    private $estado;
    private $altura;
    private $peso;
    private $pele;
    private $cor_cabelo;
    private $cor_olho;
    private $mais_caracteristicas;
    private $dt_desaparecimento;
    private $local_desaparecimento;
    private $circunstancia_desaparecimento;
    private $dados_adicionais;
    private $situacao;
    private $fonte;


    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getApelido()
    {
        return $this->apelido;
    }

    /**
     * @param mixed $apelido
     */
    public function setApelido($apelido)
    {
        $this->apelido = $apelido;
    }

    /**
     * @return mixed
     */
    public function getDtNascimento()
    {
        return $this->dt_nascimento;
    }

    /**
     * @param mixed $dt_nascimento
     */
    public function setDtNascimento($dt_nascimento)
    {
        $this->dt_nascimento = $dt_nascimento;
    }

    /**
     * @return mixed
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * @param mixed $sexo
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }

    /**
     * @return mixed
     */
    public function getIdade()
    {
        return $this->idade;
    }

    /**
     * @param mixed $idade
     */
    public function setIdade($idade)
    {
        $this->idade = $idade;
    }

    /**
     * @return mixed
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @param mixed $cidade
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getAltura()
    {
        return $this->altura;
    }

    /**
     * @param mixed $altura
     */
    public function setAltura($altura)
    {
        $this->altura = $altura;
    }

    /**
     * @return mixed
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * @param mixed $thiseso
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;
    }

    /**
     * @return mixed
     */
    public function getPele()
    {
        return $this->pele;
    }

    /**
     * @param mixed $thisele
     */
    public function setPele($pele)
    {
        $this->pele = $pele;
    }

    /**
     * @return mixed
     */
    public function getCorCabelo()
    {
        return $this->cor_cabelo;
    }

    /**
     * @param mixed $cor_cabelo
     */
    public function setCorCabelo($cor_cabelo)
    {
        $this->cor_cabelo = $cor_cabelo;
    }

    /**
     * @return mixed
     */
    public function getCorOlho()
    {
        return $this->cor_olho;
    }

    /**
     * @param mixed $cor_olho
     */
    public function setCorOlho($cor_olho)
    {
        $this->cor_olho = $cor_olho;
    }

    /**
     * @return mixed
     */
    public function getMaisCaracteristicas()
    {
        return $this->mais_caracteristicas;
    }

    /**
     * @param mixed $mais_caracteristicas
     */
    public function setMaisCaracteristicas($mais_caracteristicas)
    {
        $this->mais_caracteristicas = $mais_caracteristicas;
    }

    /**
     * @return mixed
     */
    public function getDtDesaparecimento()
    {
        return $this->dt_desaparecimento;
    }

    /**
     * @param mixed $dt_desaparecimento
     */
    public function setDtDesaparecimento($dt_desaparecimento)
    {
        $this->dt_desaparecimento = $dt_desaparecimento;
    }

    /**
     * @return mixed
     */
    public function getLocalDesaparecimento()
    {
        return $this->local_desaparecimento;
    }

    /**
     * @param mixed $local_desaparecimento
     */
    public function setLocalDesaparecimento($local_desaparecimento)
    {
        $this->local_desaparecimento = $local_desaparecimento;
    }

    /**
     * @return mixed
     */
    public function getCircunstanciaDesaparecimento()
    {
        return $this->circunstancia_desaparecimento;
    }

    /**
     * @param mixed $circunstancia_desaparecimento
     */
    public function setCircunstanciaDesaparecimento($circunstancia_desaparecimento)
    {
        $this->circunstancia_desaparecimento = $circunstancia_desaparecimento;
    }

    /**
     * @return mixed
     */
    public function getDadosAdicionais()
    {
        return $this->dados_adicionais;
    }

    /**
     * @param mixed $dados_adicionais
     */
    public function setDadosAdicionais($dados_adicionais)
    {
        $this->dados_adicionais = $dados_adicionais;
    }

    /**
     * @return mixed
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @param mixed $situacao
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
    }

    /**
     * @return mixed
     */
    public function getFonte()
    {
        return $this->fonte;
    }

    /**
     * @param mixed $fonte
     */
    public function setFonte($fonte)
    {
        $this->fonte = $fonte;
    }

    /**
     * @return mixed
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * @param mixed $fonte
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    public function printPessoa(){
        echo "<b>Imagem:</b> " . $this->imagem . "<br>";
        echo "<b>Situacao:</b> " . $this->situacao . "<br>";
        echo "<b>Nome:</b> " . $this->nome . "<br>";
        echo "<b>Apelido:</b> " . $this->apelido . "<br>";
        echo "<b>Idade:</b> " . $this->idade . "<br>";
        echo "<b>dataNasc:</b> " . $this->dt_nascimento . "<br>";
        echo "<b>sexo:</b> " . $this->sexo . "<br>";
        echo "<b>mais carac:</b> " . $this->mais_caracteristicas . "<br>";
        echo "<b>altura:</b> " . $this->altura . "<br>";
        echo "<b>peso:</b> " . $this->peso . "<br>";
        echo "<b>cor_olho:</b> " . $this->cor_olho . "<br>";
        echo "<b>cor_cabelo:</b> " . $this->cor_cabelo . "<br>";
        echo "<b>cor_pele:</b> " . $this->pele . "<br>";
        echo "<b>estado:</b> " . $this->estado . "<br>";
        echo "<b>cidade:</b> " . $this->cidade . "<br>";
        echo "<b>data_des:</b> " . $this->dt_desaparecimento . "<br>";
        echo "<b>local_des:</b> " . $this->local_desaparecimento . "<br>";
        echo "<b>dados adiconais:</b> " . $this->dados_adicionais . "<br>";
        echo "<b>circunstancia_des:</b> " . $this->circunstancia_desaparecimento . "<br>";
        echo "<b>fonte:</b> " . $this->fonte . "<br>";
        echo "<hn>";
    }

    public function insertPessoa(){
        $BD = "http://127.0.0.1:10035/#/repositories/TESTE";
    }
}