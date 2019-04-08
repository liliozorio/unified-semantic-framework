<?php

namespace tasks;
require_once 'Task.php';

class DBPediaSpotlightAnnotation implements Task
{
    public function processing($attribute)
    {
        return $attribute;
    }
}
