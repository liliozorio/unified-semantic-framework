<?php

namespace tasks;

class ReturnAttributeTask implements Task
{
    public function processing($attribute)
    {
        return $attribute;
    }
}
