<?php

namespace tasks;
require_once __DIR__.'/../tasks/Task.php';

class CityPrepareTask implements Task
{
    public function processing($attribute)
    {
        $lowerCaseNormalizer = new \tasks\LowerCaseNormalizerTask();
        $attribute = $lowerCaseNormalizer->processing($attribute);
        return $attribute;
    }
}
