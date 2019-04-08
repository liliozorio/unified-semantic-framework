<?php

namespace tasks;

use model\SelectTask;

class CompoundTask implements Task
{
    protected $taskDo;
    protected $afterClass;
    protected $selectTask;

    function __construct($doTask, $afterClass, $SelectTask) {
        $this->setTaskDo($doTask);
        $this->setAfterClass($afterClass);
        $this->selectTask = $SelectTask;
    }

    public function processing($attribute)
    {
        $doTask = $this->selectTask->getTaskAttribute($this->getTaskDo());
        $attributeProcessed = $doTask->processing($attribute);

        if($this->getAfterClass() != null) {
            $afterClass = $this->selectTask->getTaskAttribute($this->getAfterClass());
            $attributeProcessed = $afterClass->processing($attributeProcessed);
        }

        return $attributeProcessed;
    }

    public function getAfterClass()
    {
        return $this->afterClass;
    }

    public function setAfterClass($afterClass)
    {
        $this->afterClass = $afterClass;
    }

    public function getTaskDo()
    {
        return $this->taskDo;
    }

    public function setTaskDo($taskDo)
    {
        $this->taskDo = $taskDo;
    }
}