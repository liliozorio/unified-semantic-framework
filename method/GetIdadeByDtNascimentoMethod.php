<?php
namespace method;

require_once 'Method.php';

class GetIdadeByDtNascimentoMethod implements \Method
{
    public function executeMethod($person)
    {
        $date_birth = $person->getAttribute('dt_nascimento');

        if($date_birth != ""){
            $date_birth = explode('/', $date_birth);

            $years = (int)date("Y") - $date_birth[2];

            if ((int)date("m") < $date_birth[1]) {
                $age = $years - 1;
            }

            elseif((int)date("m") == $date_birth[1] && (int)date("d") < $date_birth[0]) {
                $age = $years - 1;
            }

            else{
                $age = $years;
            }

            $person->setAttribute('idade',$age);
        }
    }
}