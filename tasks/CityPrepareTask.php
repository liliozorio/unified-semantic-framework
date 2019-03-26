<?php

namespace tasks;
require_once 'Task.php';

class CityPrepareTask implements Task
{
    public function processing($attribute)
    {
        $lowerCaseNormalizer = new \tasks\LowerCaseNormalizerTask();
        $attribute = $lowerCaseNormalizer->processing($attribute);
        return $attribute;
    }
}
