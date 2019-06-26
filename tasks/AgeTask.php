<?php
namespace tasks;
require_once __DIR__.'/../tasks/Task.php';

class AgeTask implements Task
{
    public function processing($idade)
    {
        return (int)$idade;
    }
}
