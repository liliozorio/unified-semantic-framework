<?php

namespace tasks;

class GenderTask implements Task
{
    public function processing($attribute)
    {
        return $attribute;
    }
}
