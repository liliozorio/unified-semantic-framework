<?php

namespace method;

require_once 'Method.php';

class GetDtNascimentoByIdadeMethod implements \Method
{
    public function executeMethod($person)
    {
        $age = $person->getAttribute('idade');
        if ($age) {
            $date_birth = date("d") . "/" . date("m") . "/" . (date("Y") - $age);
            $person->setAttribute('dt_nascimento', $date_birth);
        }
    }
}