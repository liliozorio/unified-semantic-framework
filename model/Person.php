<?php

namespace model;

require_once __DIR__.'/../Controller/../model/DataV0.php';

class Person implements DataV0
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
    private $boletimDeOcorrecia;

    public function setAttribute($attribute, $value)
    {
        $this->$attribute = $value;
    }

    public function getAttribute($attribute)
    {
        return $this->$attribute;
    }

    public function printPerson()
    {
        foreach ($this as $attribute) {
            echo "<b>" . $attribute . ":</b> " . $this->getAttribute($attribute) . "<br>";
        }
        echo "<br><br>";
    }


    public function __toJSon(){
        $json = array();
        foreach($this as $key => $value) {
            $json[$key] = $value;
        }
        return json_encode($json);
    }

}