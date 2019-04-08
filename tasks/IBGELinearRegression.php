<?php

namespace tasks;
require_once 'Task.php';

class IBGELinearRegression implements Task
{
    public function processing($attribute)
    {
        return $attribute;
    }
}