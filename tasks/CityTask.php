<?php

namespace tasks;
require_once 'Task.php';


class CityTask implements Task
{
    public function processing($attribute)
    {
       $cityPrepare = new CityPrepareTask;
       $attribute = $cityPrepare->processing($attribute);
       return $attribute;
    }
}