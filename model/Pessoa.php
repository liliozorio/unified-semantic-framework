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
    private $cidade_uri;
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
    private $data_localizacao;

    private $arr_attributes = array(
        'nome',
        'apelido',
        'dt_nascimento',
        'imagem',
        'sexo',
        'idade',
        'cidade',
        'cidade_uri',
        'estado',
        'altura',
        'peso',
        'pele',
        'cor_cabelo',
        'cor_olho',
        'mais_caracteristicas',
        'dt_desaparecimento',
        'local_desaparecimento',
        'data_localizacao',
        'circunstancia_desaparecimento',
        'dados_adicionais',
        'situacao',
        'fonte');


    public function setAttribute($attribute, $value)
    {
        $this->$attribute = $value;
    }

    public function getAttribute($attribute)
    {
        return $this->$attribute;
    }

    public function printPessoa()
    {
        foreach ($this->arr_attributes as $attribute) {
            echo "<b>" . $attribute . ":</b> " . $this->getAttribute($attribute) . "<br>";
        }
        echo "<br><br>";
    }
}