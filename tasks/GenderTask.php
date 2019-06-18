<?php

namespace tasks;
require_once __DIR__.'/../tasks/Task.php';

class GenderTask implements Task
{
    public function processing($attribute)
    {
        return $attribute;
    }
}
