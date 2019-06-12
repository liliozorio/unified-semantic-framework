<?php

namespace model;

use ReflectionClass;
use method\GetIdadeByDtNascimentoMethod;
use method\GetDtNascimentoByIdadeMethod;

class MethodModel
{
    private $methodClass;

    function __construct($methodClass)
    {
        try {
            $this->methodClass = new ReflectionClass('method\\'.$methodClass);
            $this->methodClass = $this->methodClass->newInstance();
        } catch (\ReflectionException $e) {
            echo("<b>Erro:</b> ". $e->getMessage());
        }
    }

    function executeMethod($pessoa){
        $this->methodClass->executeMethod($pessoa);
    }
}