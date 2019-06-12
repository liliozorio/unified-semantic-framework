<?php

namespace method;

require_once 'Method.php';

class GetDtNascimentoByIdadeMethod implements \Method
{
    public function executeMethod($pessoa){
        $idade = $pessoa->getAttribute('idade');
        if($idade){
           $data_nascimento =  date("d")."/".date("m")."/".(date("Y") - $idade);
           $pessoa->setAttribute('dt_nascimento', $data_nascimento);
        }
    }
}