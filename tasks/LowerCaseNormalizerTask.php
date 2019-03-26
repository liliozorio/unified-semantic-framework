<?php

namespace tasks;
require_once 'Task.php';


class LowerCaseNormalizerTask implements Task
{
    public function processing($attribute)
    {
        return strtolower($attribute);
    }

}
