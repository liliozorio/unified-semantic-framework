<?php
namespace model;

use tasks\CityCanonicaName;
use tasks\DBPediaSpotlightAnnotation;
use tasks\IBGELinearRegression;
use tasks\LowerCaseNormalizerTask;

class SelectTask
{
    /** array com as task que devem ser chamadas para cada atributo
     * o array se encontra no formato
     * [attribute] => task
     */
    protected $arr_AttributeTask;

    /** array com as configurações das CompoundTask
     * o array se encontra no formato
     * [attribute] => [do] => task que deve se assionada
     *                [afterClass] => proxima task que deve ser assionada
     * pode haver casos onde o campo 'afterClass' é igual a null
     */
    protected $arr_TaskConfig;

    function __construct($jsonConfig)
    {
        $this->initArrayTaskConfig($jsonConfig);
        $this->initArrayAttributeTask($jsonConfig);
        //$this->printArray();
    }

    public function getAttributeProcessed($valueAttribute, $attribute)
    {
        $selectedTask = $this->arr_AttributeTask[$attribute];

        $task = $this->getTaskAttribute($selectedTask);

        return $task->processing($valueAttribute);
    }

    public function getTaskAttribute($selectedTask){

        if ( $selectedTask == 'LowerCaseNormalizer') {
            $task = new LowerCaseNormalizerTask();
        }

        elseif ($selectedTask == 'DBPediaSpotlightAnnotation'){
            $task = new DBPediaSpotlightAnnotation();
        }

        elseif ($selectedTask == 'CityCanonicaName'){
            $task = new CityCanonicaName();
        }

        elseif ($selectedTask == 'IBGELinearRegression'){
            $task = new IBGELinearRegression();
        }

        else{
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
    }

}