<?php

namespace tasks;
require_once __DIR__.'/../tasks/Task.php';

class LowerCaseNormalizerTask implements Task
{
    public function processing($attribute)
    {
        return strtolower($attribute);
    }

}
