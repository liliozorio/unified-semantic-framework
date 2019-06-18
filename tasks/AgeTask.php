<?php
namespace tasks;
require_once __DIR__.'/../tasks/Task.php';

class AgeTask implements Task
{
    public function processing($idade)
    {
        return (int)$idade;
    }

    public function getIdadeByDtNascimento($data_nascimento)
    {
        $data_nascimento = explode('/',$data_nascimento);
        $anos = (int)date("Y") - $data_nascimento[2];

        if((int)date("m") < $data_nascimento[1]){
            return $anos -1;
        }

        if((int)date("m") == $data_nascimento[1] && (int)date("d") < $data_nascimento[0]){
            return $anos -1;
        }

        return $anos;
    }

    public function getDtNascimentoByIdade($idade){
        return date("d")."/".date("m")."/".(date("Y") - $idade);
    }
}
