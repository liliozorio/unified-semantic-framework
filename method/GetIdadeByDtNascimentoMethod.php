<?php
namespace method;

require_once 'Method.php';

class GetIdadeByDtNascimentoMethod implements \Method
{
    public function executeMethod($pessoa)
    {
        $data_nascimento = $pessoa->getAttribute('dt_nascimento');

        if($data_nascimento != ""){
            $data_nascimento = explode('/', $data_nascimento);

            $anos = (int)date("Y") - $data_nascimento[2];

            if ((int)date("m") < $data_nascimento[1]) {
                $idade = $anos - 1;
            }

            if ((int)date("m") == $data_nascimento[1] && (int)date("d") < $data_nascimento[0]) {
                $idade = $anos - 1;
            }

            $idade = $anos;

            $pessoa->setAttribute('idade',$idade);
        }
    }
}