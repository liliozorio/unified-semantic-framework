<?php
namespace model;

use tasks\CityCanonicaName;
use tasks\DBPediaSpotlightAnnotation;
use tasks\IBGELinearRegression;
use tasks\LowerCaseNormalizerTask;
use tasks\ReturnAttributeTask;
use ReflectionClass;

class SelectTask
{
    /**
     * [attribute] => task
     */
    protected $arr_AttributeTask;

    /**
     * [attribute] => [do] => task que deve se assionada
     *                [afterClass] => proxima task que deve ser assionada
     * pode haver casos onde o campo 'afterClass' é igual a null
     */
    protected $arr_TaskConfig;

    /**
     * [] => NameTask
     */
    protected $arr_Task;


    function __construct($jsonConfig)
    {
        $this->initArrayTaskConfig($jsonConfig);
        $this->initArrayAttributeTask($jsonConfig);
        $this->initArrayTask();
    }

    public function getAttributeProcessed($valueAttribute, $attribute)
    {
        $selectedTask = $this->arr_AttributeTask[$attribute];

        $task = $this->getTaskAttribute($selectedTask);

        return $task->processing($valueAttribute);
    }

    public function getTaskAttribute($selectedTask){
        if(in_array($selectedTask,$this->arr_Task)) {
            try {
                $task = new ReflectionClass('tasks\\'.$selectedTask);
                $task = $task->newInstance();
            } catch (\ReflectionException $e) {
                echo("Erro: ". $e->getMessage());
            }

        }else {
            $task = $this->getCompoundTask($selectedTask);
        }

        return $task;
    }

    public function getCompoundTask($selectedTask){
        $doTask = $this->arr_TaskConfig[$selectedTask]['do'];
        $afterTask = $this->arr_TaskConfig[$selectedTask]['afterClass'];

        $task = new \tasks\CompoundTask($doTask, $afterTask, $this);

        return $task;
    }

    public function initArrayAttributeTask($jsonConfig)
    {
        $this->arr_AttributeTask = array();
        foreach ($jsonConfig->attributes as $attribute) {
            $this->arr_AttributeTask[$attribute->name] = $attribute->task;
        }
    }

    public function initArrayTaskConfig($jsonConfig)
    {
        $this->arr_TaskConfig = array();
        foreach ($jsonConfig->task as $task) {
            $this->arr_TaskConfig[$task->id]['do'] = $task->do;
            $this->arr_TaskConfig[$task->id]['afterClass'] = isset($task->afterClass) ? $task->afterClass : null;
        }
    }


    public function initArrayTask(){
        $this->arr_Task = array(
            'CityCanonicaName',
            'DBPediaSpotlightAnnotation',
            'IBGELinearRegression',
            'LowerCaseNormalizerTask',
            'AgeTask',
            'GetDtNascimentoByIdade',
            'ReturnAttributeTask',
            'CityPrepareTask',
        );
    }

    public function printArray(){
        echo "<b>arr_Taskconfig</b><br>";
        foreach ($this->arr_TaskConfig as $task => $taskConfig){
            echo "[".$task."] => <br>";
            echo "<pre>       [do] => ".$taskConfig['do']."<br></pre>";
            echo "<pre>       [afterClass] => ".$taskConfig['afterClass']."<br></pre>";
        }

        echo"<br><br>";

        echo "<b>arr_AttributeTask</b><br>";
        foreach ($this->arr_AttributeTask as $attribute => $task){
            echo "[".$attribute."] =>".$task."<br>";
        }

        echo"<br><br>";

        echo "<b>arr_Task</b><br>";
        foreach ($this->arr_Task as $attribute => $task){
            echo "[".$attribute."] =>".$task."<br>";
        }

        echo "<br><br>";
    }

}