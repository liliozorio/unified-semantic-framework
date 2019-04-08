<?php

namespace tasks;
require_once 'Task.php';

class CityCanonicaName implements Task
{
    public function processing($attribute)
    {
       return $attribute;
    }
}